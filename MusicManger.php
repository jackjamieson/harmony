<?php

class MusicManager
{
	private $user_id, $database;

	function __construct($user_id)
	{
		//Save user id for queries.
		$this->user_id = $user_id;
	}

	public function connectToDatabase()
	{
		
		$servername = "tcnj-csc470-preussr1-mysql1.cmlmk2o0jbuf.us-east-1.rds.amazonaws.com:3306";
		$username = "preussr1";
		$password = "mypassword";
		$dbname = "MySql1";

		//Create connection
		$this->database = new mysqli($servername, $username, $password, $dbname);

		//Check connection
		if($this->database->connect_error)
			return FALSE;
		else
			return TRUE;
	}

	//Search for songs with input params stored in the user's collection.
	//If query successful, returns the query result. Else returns false.
	public function searchSongs($title, $artist)
	{
		$queryString = "SELECT owns.song_id, owns.title, owns.artist, song.location FROM owns INNER JOIN song ON owns.song_id=song.song_id WHERE owns.user_id=? AND owns.title LIKE ? AND owns.artist LIKE ? ORDER BY artist, title";

		//If a parameter is empty, do not use it to narrow search results.
		if(empty($title))
			$title = "%";
		if(empty($artist))
			$artist="%";
		
		//Bind the query parameters to the statement.
		$searchQuery = $this->database->prepare($queryString);
		$searchQuery->bind_param("iss", $this->user_id, $title, $artist);

		//If query is successful, return the result. 
		//Otherwise, return false.
		if($searchQuery->execute())
			return $searchQuery->get_result();
		else
			return FALSE;
	}

	//Adds a new song to the database.
	public function addSong($title, $artist, $album, $genre, $location)
	{
		//Title and location cannot be null
		if(empty($title))
			return "No title";
		if(empty($location))
			return "No location";

		//Prepare the query and execute it.		
		$insertQuery = $this->database->prepare("INSERT INTO song (title, artist, album, genre, location) VALUES (?, ?, ?, ?, ?)");
		$insertQuery->bind_param("sssss", $title, $artist, $album, $genre, $location);
		$result = $insertQuery->execute();

		//If query failed, return false.
		if($result === FALSE)	
			return "Insert into song failed:\n" . $insertQuery->error;


		//If insert was successful, insert into owns table.

		//First must get song_id of the song we just inserted.
		//We do this by selecting row with same location.
		$searchQuery = $this->database->prepare("SELECT song_id FROM song WHERE location = ?");
		$searchQuery->bind_param("s", $location);
		$searchQuery->execute();

		$result = $searchQuery->get_result();

		if($result === FALSE)
			return "Select song_id failed";
		else
		{
			$row = $result->fetch_assoc();
			$song_id = $row['song_id'];
		}

		//We can now construct the owns relation with this song_id
		$ownsQuery = $this->database->prepare("INSERT INTO owns (user_id, song_id, title, artist) VALUES (?, ?, ?, ?)");
		$ownsQuery->bind_param("iiss", $this->user_id, $song_id, $title, $artist);

		$result = $ownsQuery->execute();

		//If this fails, should we rollback? Idk about that atm.		
		if($result===FALSE)
			return "Insert into owns failed";
		else
			return "Result works";
	}

	public function deleteSong($song_id)
	{

		//First, remove song from user's collection.
		$deleteOwnsQuery = $this->database->prepare("DELETE FROM owns WHERE user_id = ? AND song_id = ?");
		$deleteOwnsQuery->bind_param("ii", $this->user_id, $song_id);
		
		if($deleteOwnsQuery->execute() === FALSE)
			return FALSE;

		//Next, check if anyone else owns the song in their collection.
		$selectQuery = $this->database->prepare("SELECT COUNT(*) AS count FROM owns WHERE song_id = ?");
		$selectQuery->bind_param("i", $song_id);
		
		if($selectQuery->execute() === FALSE)
			return FALSE;
		$result = $selectQuery->get_result();

		$row = $result->fetch_assoc();
		$count = $row['count'];
		
		//If count > 0, we return without doing anything further.
		//Means someone else has song in collection.
		if($count > 0)
			return TRUE;
		
		//Get the location of the target song
		$locationQuery = $this->database->prepare("SELECT location FROM song WHERE song_id = ?");
		$locationQuery->bind_param("i", $song_id);
		
		if($locationQuery->execute() === FALSE)
			return FALSE;

		$row = $locationQuery->get_result()->fetch_assoc();
		$location = $row['location'];

		//If count is 0, that means nobody has song in collection.
		//We should therefore delete it from S3 and remove from DB.
		$deleteSongQuery = $this->database->prepare("DELETE FROM song WHERE song_id = ?");
		$deleteSongQuery->bind_param("i", $song_id);
		
		if($deleteSongQuery->execute() === FALSE)
			return FALSE;

		//Delete song from S3.
		else
		{
			include "template/aws.php";
			$aws = new AWS();
			$client = $aws->authS3();
			$aws->deleteSong($client, $location);
			return TRUE;
		}
	}

	//Selects the title and artist of a user's song.
	//Returns an array with columns ['title'] and ['artist'] on success.
	//Returns FALSE on failure.
	//TODO: Unsure if still used.
	public function getSongInfo($song_id)
	{
		$searchQuery = $this->database->prepare("SELECT title, artist FROM owns WHERE user_id = ? AND song_id = ?");
		$searchQuery->bind_param("ii", $this->user_id, $song_id);
		
		//If query fails, return false.
		if($searchQuery->execute() === FALSE)
			return FALSE;
		
		//Otherwise, return an array with the title and artist.
		return $searchQuery->get_result()->fetch_assoc();
	}

	//Edits the title and/or artist of a song in the user's collection.
	//Returns FALSE if error with either query.
	//Returns TRUE otherwise.
	public function editSong($song_id, $newTitle, $newArtist)
	{	
		//Update title if not null.
		if(!empty($newTitle))
		{
			$titleQuery = $this->database->prepare("UPDATE owns SET title = ? WHERE user_id = ? AND song_id = ?" );
			$titleQuery->bind_param("sii", $newTitle, $this->user_id, $song_id);
			if($titleQuery->execute() === FALSE)
				return FALSE;
		}

		if(!empty($newArtist))
		{
			$artistQuery = $this->database->prepare("UPDATE owns SET artist = ? WHERE user_id = ? AND song_id = ?");

			$artistQuery->bind_param("sii", $newArtist, $this->user_id, $song_id);
			if($artistQuery->execute() === FALSE)
				return FALSE;
		}

		return TRUE;
	}

	//Returns the S3 location of the input song on success.
	//Returns FALSE on failure.
	public function getSongLocation($song_id)
	{
		$searchQuery = $this->database->prepare("SELECT location FROM song WHERE song_id=?");
		$searchQuery->bind_param("i", $song_id);
		
		if($searchQuery->execute() === FALSE)
			return FALSE;

		$row = $searchQuery->get_result()->fetch_assoc();
		return $row['location'];
	}

	//Returns 0 on failure.
	//Returns 1 on success.
	//Returns 2 if user has already voted.
	public function checkIfLiked($song_id, $owner_id)
	{
		
		//Check if user has liked the song already.
		$checkStmt = "SELECT COUNT(*) AS count FROM likes WHERE user_id=? AND song_id=? AND owner_id=?";
		$query = $this->database->prepare($checkStmt);
		$query->bind_param("iii", $this->user_id, $song_id, $owner_id);
		
		if($query->execute() === FALSE)
			return 0;
		
		//Return '1' if user hasn't liked/disliked song.
		if($query->get_result()->fetch_assoc()['count'] === 0)
			return 1;

		//Return '2' if user has already liked this song.
		else
			return 2;
	}

	//Returns TRUE on success, FALSE on failure
	public function likeSong($song_id, $owner_id)
	{
		//TODO: Rating no longer in song. Rework database in future.
		//If user hasn't liked song, first increment the song's rating.
		$query = $this->database->prepare("UPDATE owns SET rating = rating + 1 WHERE song_id = ? AND user_id = ?");
		$query->bind_param("ii", $song_id, $owner_id);

		if($query->execute() === FALSE)
			return FALSE;

		//Add new entry to likes table
		$stmt = "INSERT INTO likes(user_id, song_id, owner_id, like_value) VALUES (?,?,?,?)";
		$query = $this->database->prepare($stmt);
		$query->bind_param("iiii", $this->user_id, $song_id, $owner_id, 1);	
		return $query->execute();
	}

	//Decrements the rating of a song by one.
	//Returns TRUE on success, FALSE on failure.
	public function dislikeSong($song_id, $owner_id)
	{
		//TODO: Rating no longer in song. Rework database in future.
		//If user hasn't liked song, first increment the song's rating.
		$query = $this->database->prepare("UPDATE owns SET rating = rating - 1 WHERE song_id = ? AND user_id = ?");
		$query->bind_param("ii", $song_id, $owner_id);

		if($query->execute() === FALSE)
			return FALSE;

		//Add new entry to likes table, with a 0 value for dislike.
		$stmt = "INSERT INTO likes(user_id, song_id, owner_id, like_value) VALUES (?,?,?,?)";
		$query = $this->database->prepare($stmt);
		$query->bind_param("iiii", $this->user_id, $song_id, $owner_id, 0);
		return $query->execute();

	}

	//Grabs song and adds to user's collection.
	//Returns 0 on failure
	//Returns 1 on successful grab
	//Returns 2 if user already has song in collection.
	public function grabSong($song_id, $title, $artist)
	{
		$stmt = "INSERT INTO owns (user_id, song_id, title, artist) VALUES (?,?,?,?)";

		$grabQuery = $this->database->prepare($stmt);
		$grabQuery->bind_param("iiss", $this->user_id, $song_id, $title, $artist);
		if($grabQuery->execute() === TRUE)
			return 1;

		//If failure, check error code.
		if($grabQuery->errno === 1062)
			return 2;
		else
			return 0;
	}

	//Returns an array with the title, artist, and rating of a song
	//given the location and user_id on success.
	//Returns FALSE on failure.
	public function getUserSongFromLocation($location, $user_id)
	{
		$stmt = "SELECT owns.title, owns.artist, owns.song_id, owns.rating FROM song INNER JOIN owns ON song.song_id = owns.song_id WHERE song.location=? AND owns.user_id=?";
		$query = $this->database->prepare($stmt);

		$query->bind_param("si", $location, $user_id);

		if($query->execute() === FALSE)
			return FALSE;

		return $query->get_result()->fetch_assoc();
	}

}

?>
