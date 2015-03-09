<?php

$page = $_SERVER['PHP_SELF'];


echo '
<div class="container">
 <!-- Static navbar -->
 <nav class="navbar navbar-default">
   <div class="container-fluid">
     <div class="navbar-header">
       <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
         <span class="sr-only">Toggle navigation</span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
       </button>
       <a class="navbar-brand" href="http://45.56.101.195"><span class="glyphicon glyphicon-music" aria-hidden="true"></span> Harmony</a>
     </div>
     <div id="navbar" class="navbar-collapse collapse">
       <ul class="nav navbar-nav">

         <li class="active"><a href="#">Home</a></li>

       </ul>
       <ul class="nav navbar-nav navbar-right">

         <li><a href="#">Sign In<span class="sr-only">(current)</span></a></li>
         <li><a href="#">Register</a></li>
         
       </ul>
     </div><!--/.nav-collapse -->
   </div><!--/.container-fluid -->
 </nav>
 ';


?>
