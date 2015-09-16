<?php require_once 'config.php';?>

<!DOCTYPE html>
<html>
<head>
  <title>Youtube Scapper</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
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
            <a class="navbar-brand" href="index.php">Youtube Scapper</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="index.php">Home</a></li>
              <li><a href="add_channel.php">Add Channel</a></li>
              <li><a href="download.php">Download</a></li>
              <li><a href="delete_scrapper_videos.php">Scrapper Videos</a></li>

            </ul>

          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
