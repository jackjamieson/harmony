<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<meta name="description" content="Cloud PHP Music Collaboration Project" />
<meta name="keywords" content="cloud, php, project" />
<meta name="author" content="Jack Jamieson">
<meta name="robots" content="index, follow">
<title>Cloud PHP</title>
<!-- jack, jamieson -->

<head>
    
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
    <script src="/player/audio.min.js"></script>

</head>

<html>

<body>
    
    <script>    
        //Init the player
      audiojs.events.ready(function() {
        var as = audiojs.createAll();
      });
    </script>
    
    
	<nav class="navbar navbar-default">
 		<div class="container">
    			<div class="navbar-header">

				<a class="navbar-brand" href="index.php">Harmony</a>
			</div>
			 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home<span class="sr-only">(current)</span></a></li>
        <li><a href="#">Sign in</a></li>
</ul>
  		</div>
	</nav>

	<div class="content">
    <?php 
        error_reporting(E_ALL);
        ini_set( 'display_errors', 'On');// Turn on debugging.
    
        include ('aws.php');// Log in to AWS

        $bucket = "com.cloud.php.data";

        //check whether a form was submitted
        if(isset($_POST['Submit'])){

            //retreive post variables
            $fileName = $_FILES['theFile']['name'];
            $fileTempName = $_FILES['theFile']['tmp_name'];
            $awsFileName = time();


            if($_FILES['theFile']['error'] > 0){
                echo "return code: " . $_FILES['theFile']['error'];
            }	
            
            $result = $s3Client->putObject(array(
                'ACL' => 'public-read',
                'SourceFile' => $fileTempName,
                'Bucket' => "com.cloud.php.data",
                'Key' => "Music/" . $awsFileName . ".mp3"      
            ));
            

            
            

        }
    ?>



<div class="jumbotron">
  <h1>Upload an MP3</h1>

    <?php         
            if(isset($_POST['Submit'])){
                echo '<p><audio id="audiobox" src="' . $result['ObjectURL'] . '" preload="auto"/></p>'; 
            }
    ?>


<form action="" method="post" enctype="multipart/form-data">
  <input name="theFile" type="file" />
  <input name="Submit" type="submit" value="Upload">
</form>
</div>
        
        
</div>


</body>

</html>
