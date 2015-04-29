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
		$queryString = "SELECT song.* FROM song INNER JOIN owns ON song.song_id = owns.song_id WHERE owns.user_id=? AND song.title LIKE ? AND song.artist LIKE ?";
// AND song.album LIKE ? AND song.genre LIKE ?";

		//If a parameter is empty, do not use it to narrow search results.
		if(empty($title))
			$title = "%";
		if(empty($artist))
			$artist="%";
//		if(empty($album))
//			$album="%";
//		if(empty($genre))
//			$genre="%";
		
		//Bind the query parameters to the statement.
		$searchQuery = $this->database->prepare($queryString);
		$searchQuery->bind_param("sss", $this->user_id, $title, $artist);

		//If query is successful, return the result. 
		//Otherwise, return false.
		if($searchQuery->execute())
			return $searchQuery->get_result();
		else
			return FALSE;
	}

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
		$deleteOwnsQuery = $database->prepare("DELETE FROM owns WHERE user_id = ? AND song_id = ?");
		$deleteOwnsQuery->bind_param("ii", $this->user_id, $song_id);
		
		if($deleteOwnsQuery->execute() === FALSE)
			return FALSE;

		//Next, check if anyone else owns the song in their collection.
		$selectQuery = $database->prepare("SELECT COUNT(*) AS count FROM owns WHERE song_id = ?");
		$selectQuery->bind_param("i", $song_id);
		$selectQuery->execute();
		$result = $selectQuery->get_result();

		$row = $result->fetch_accoc();
		$count = $row['count'];
		
		//If count > 0, we return without doing anything further.
		//Means someone else has song in collection.
		if($count > 0)
			return TRUE;
		
		//If count is 0, that means nobody has song in collection.
		//We should therefore delete it from S3 and remove from DB.
		$deleteSongQuery = $database->prepare("DELETE FROM song WHERE song_id = ?");
		$deleteSongQuery->bind_param("i", $song_id);
		
		if($deleteSongQuery->execute() === FALSE)
			return FALSE;
		
		//TODO: REMOVE SONG FROM S3 HERE.
	}

	public function editSong($song_id, $newTitle, $newArtist)
	{
		//
		$selectQuery = $this->database->prepare("SELECT" );
	}


	public function likeSong()
	{
		//TODO: Need to implement.
	}

}

?>
