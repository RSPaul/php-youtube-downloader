<?php

$checkVideo = mysqli_query($con, 'SELECT COUNT(id) as total FROM fecthed_videos WHERE link = "' . $_POST['link'] . '"');
$checkVideo = mysqli_fetch_array($checkVideo);
$duplicate = $checkVideo['total'];

if ($duplicate == 0) {

	$insert2 = mysqli_query($con, 'INSERT INTO fecthed_videos (title,link,tags,des,image,channel_id,is_downloaded, download_link, is_singular)VALUES("' . mysql_escape_string($_POST['title']) . '", "' . $_POST['link'] . '", "' . $_POST['tags'] . '", "' . $_POST['description'] . '", "' . $_POST['image'] . '", "' . 0 . '", 0, "' . $_POST['download_link'] . '", "1")');

	if (!$insert2) {
		echo 'Error inserting ' . 'INSERT INTO fecthed_videos (title,link,tags,des,image,channel_id,is_downloaded, download_link, is_singular)VALUES("' . mysql_escape_string($_POST['title']) . '", "' . $_POST['link'] . '", "' . $_POST['tags'] . '", "' . $_POST['description'] . '", "' . $_POST['image'] . '", "' . 0 . '", 0, "' . $_POST['download_link'] . '", "1")';
	} else {
		$respone = array('success' => 1, 'msg' => 'Video added to dwonload page');
		echo json_encode($respone);
	}

} else {
	$respone = array('success' => 0, 'msg' => 'Video is already added to dwonload page');
	echo json_encode($respone);
}

?>