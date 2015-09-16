<?php
require_once 'header.php';

ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

if (isset($_POST['submit']) && isset($_POST['channel_name']) && $_POST['channel_name'] !== '') {

	$channelDetails = file_get_contents('https://www.googleapis.com/youtube/v3/channels?key=' . API_KEY . '&forUsername=' . $_POST['channel_name'] . '&part=id');
	$channelDecoded = json_decode($channelDetails, true);
	$channelId = $channelDecoded['items'][0]['id'];

	$checkChannel = mysqli_query($con, 'SELECT COUNT(id) as total FROM channels WHERE channel_id = "' . $channelId . '"');
	$checkChannel = mysqli_fetch_array($checkChannel);
	$duplicateChannel = $checkChannel['total'];

	if ($duplicateChannel != 0) {
		echo "Channel is added already";
		exit;
	}

	/* get the channels videos and insert data into DB */
	$insert = mysqli_query($con, "INSERT INTO channels (channel_name,channel_id)VALUES('" . $_POST['channel_name'] . "', '$channelId')");

	if (!$insert) {
		echo "Error inserting new channel";
	}

	$channelVideos = file_get_contents('https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' . $channelId . '&maxResults=50&order=date&key=' . API_KEY . '');
	$videosDecoded = json_decode($channelVideos, true);
	if (isset($videosDecoded) && is_array($videosDecoded)) {

		//next page videos
		if ($channelVideos) {
			$totalHits = round($videosDecoded['pageInfo']['totalResults'] / $videosDecoded['pageInfo']['resultsPerPage']);
		}
		$pageToken = $videosDecoded['nextPageToken'];

		foreach ($videosDecoded['items'] as $searchResult) {
			switch ($searchResult['id']['kind']) {
				case 'youtube#video':
					$videoDetails = file_get_contents("https://www.youtube.com/watch?v=" . $searchResult['id']['videoId'] . "");
					$videoThumb = $searchResult['snippet']['thumbnails']['high']['url'];
					$videoUrl = 'http://www.youtube.com/watch?v=' . $searchResult['id']['videoId'];
					$videoTitle = $searchResult['snippet']['title'];

					$connect = file_get_contents("https://www.youtube.com/watch?v=" . $searchResult['id']['videoId'] . "");
					preg_match_all('|<meta property="og\:video\:tag" content="(.+?)">|si', $connect, $tags, PREG_SET_ORDER);
					preg_match_all('|<meta property="og\:description" content="(.+?)">|si', $connect, $descriptions, PREG_SET_ORDER);

					if (isset($tags) && is_array($tags)) {
						$videoTags = "";
						foreach ($tags as $tag) {
							$videoTags .= $tag[1] . ",";
						}
					}
					if (isset($descriptions) && is_array($descriptions)) {
						$videoDescription = '';
						foreach ($descriptions as $description) {
							$videoDescription .= $description[1];
						}
					}

					$checkVideo = mysqli_query($con, 'SELECT COUNT(id) as total FROM fecthed_videos WHERE link = "' . $videoUrl . '"');
					$checkVideo = mysqli_fetch_array($checkVideo);
					$duplicate = $checkVideo['total'];

					if ($duplicate == 0) {
						$image_name = preg_replace("/[^a-zA-Z]+/", "", $videoTitle);
						$contentImage = file_get_contents($videoThumb);
						$fileName = "images/" . $image_name . ".jpg";
						$fp = fopen($fileName, "w");
						fwrite($fp, $contentImage);
						fclose($fp);

						$downloadLink = 0; //generateDownload($videoUrl);
						$videoTitle = mysql_escape_string($videoTitle);

						$insert2 = mysqli_query($con, 'INSERT INTO fecthed_videos (title,link,tags,des,image,channel_id,is_downloaded, download_link,is_singular)VALUES("' . $videoTitle . '", "' . $videoUrl . '", "' . $videoTags . '", "' . $videoDescription . '", "' . $fileName . '", "' . $channelId . '", 0, "' . $download_link . ', 0")');

						if (!$insert2) {
							echo "Error inserting video" . mysqli_error($con);
						}
					}
					break;
			}
		}
		if ($totalHits > 1) {
			for ($i = 0; $i < $totalHits; $i++) {

				$channelVideos = file_get_contents('https://www.googleapis.com/youtube/v3/search?pageToken=' . $pageToken . '&part=snippet&channelId=' . $channelId . '&maxResults=50&order=date&key=' . $API_KEY . '');
				$videosDecoded = json_decode($channelVideos, true);

				$pageToken = $videosDecoded['nextPageToken'];

				foreach ($videosDecoded['items'] as $searchResult) {
					switch ($searchResult['id']['kind']) {
						case 'youtube#video':
							$videoDetails = file_get_contents("https://www.youtube.com/watch?v=" . $searchResult['id']['videoId'] . "");
							$videoThumb = $searchResult['snippet']['thumbnails']['high']['url'];
							$videoUrl = 'http://www.youtube.com/watch?v=' . $searchResult['id']['videoId'];
							$videoTitle = $searchResult['snippet']['title'];

							$connect = file_get_contents("https://www.youtube.com/watch?v=" . $searchResult['id']['videoId'] . "");
							preg_match_all('|<meta property="og\:video\:tag" content="(.+?)">|si', $connect, $tags, PREG_SET_ORDER);
							preg_match_all('|<meta property="og\:description" content="(.+?)">|si', $connect, $descriptions, PREG_SET_ORDER);

							if (isset($tags) && is_array($tags)) {
								$videoTags = "";
								foreach ($tags as $tag) {
									$videoTags .= $tag[1] . ",";
								}
							}
							if (isset($descriptions) && is_array($descriptions)) {
								$videoDescription = '';
								foreach ($descriptions as $description) {
									$videoDescription .= $description[1];
								}
							}

							$checkVideo = mysqli_query($con, 'SELECT COUNT(id) as total FROM fecthed_videos WHERE link = "' . $videoUrl . '"');
							$checkVideo = mysqli_fetch_array($checkVideo);
							$duplicate = $checkVideo['total'];

							if ($duplicate == 0) {

								$image_name = preg_replace("/[^a-zA-Z]+/", "", $videoTitle);
								$contentImage = file_get_contents($videoThumb);
								$fileName = "images/" . $image_name . ".jpg";
								$fp = fopen($fileName, "w");
								fwrite($fp, $contentImage);
								fclose($fp);

								$downloadLink = 0;
								$videoTitle = mysql_escape_string($videoTitle);

								$insert2 = mysqli_query($con, 'INSERT INTO fecthed_videos (title,link,tags,des,image,channel_id,is_downloaded, download_link, is_singular)VALUES("' . $videoTitle . '", "' . $videoUrl . '", "' . $videoTags . '", "' . $videoDescription . '", "' . $fileName . '", "' . $channelId . '", 0, "' . $download_link . ',0")');
								if (!$insert2) {
									echo "Error inserting video" . mysqli_error($con);
								}
							}
							break;
					}
				}
			}
		}
	}
} else if (isset($_POST['submit']) && isset($_POST['channel_id']) && $_POST['channel_id'] !== '') {

	$channelDetails = file_get_contents('https://www.googleapis.com/youtube/v3/channels?part=snippet,contentDetails&id=' . $_POST['channel_id'] . '&key=' . API_KEY . '');
	$channelDecoded = json_decode($channelDetails, true);
	$channelName = $channelDecoded['items'][0]['snippet']['title'];
	$channelId = $_POST['channel_id'];

	$checkChannel = mysqli_query($con, 'SELECT COUNT(id) as total FROM channels WHERE channel_id = "' . $channelId . '"');
	$checkChannel = mysqli_fetch_array($checkChannel);
	$duplicateChannel = $checkChannel['total'];

	if ($duplicateChannel != 0) {
		echo "Channel is added already";
		exit;
	}

	/* get the channels videos and insert data into DB */
	$insert = mysqli_query($con, "INSERT INTO channels (channel_name,channel_id)VALUES('" . $channelName . "', '$channelId')");

	if (!$insert) {
		echo "Error inserting new channel";
	}

	$channelVideos = file_get_contents('https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' . $channelId . '&maxResults=50&order=date&key=' . $API_KEY . '');
	$videosDecoded = json_decode($channelVideos, true);
	if (isset($videosDecoded) && is_array($videosDecoded)) {

		//next page videos
		if ($channelVideos) {
			$totalHits = round($videosDecoded['pageInfo']['totalResults'] / $videosDecoded['pageInfo']['resultsPerPage']);
		}
		$pageToken = $videosDecoded['nextPageToken'];

		foreach ($videosDecoded['items'] as $searchResult) {
			switch ($searchResult['id']['kind']) {
				case 'youtube#video':
					$videoDetails = file_get_contents("https://www.youtube.com/watch?v=" . $searchResult['id']['videoId'] . "");
					$videoThumb = $searchResult['snippet']['thumbnails']['high']['url'];
					$videoUrl = 'http://www.youtube.com/watch?v=' . $searchResult['id']['videoId'];
					$videoTitle = $searchResult['snippet']['title'];

					$connect = file_get_contents("https://www.youtube.com/watch?v=" . $searchResult['id']['videoId'] . "");
					preg_match_all('|<meta property="og\:video\:tag" content="(.+?)">|si', $connect, $tags, PREG_SET_ORDER);
					preg_match_all('|<meta property="og\:description" content="(.+?)">|si', $connect, $descriptions, PREG_SET_ORDER);

					if (isset($tags) && is_array($tags)) {
						$videoTags = "";
						foreach ($tags as $tag) {
							$videoTags .= $tag[1] . ",";
						}
					}
					if (isset($descriptions) && is_array($descriptions)) {
						$videoDescription = '';
						foreach ($descriptions as $description) {
							$videoDescription .= $description[1];
						}
					}

					$checkVideo = mysqli_query($con, 'SELECT COUNT(id) as total FROM fecthed_videos WHERE link = "' . $videoUrl . '"');
					$checkVideo = mysqli_fetch_array($checkVideo);
					$duplicate = $checkVideo['total'];

					if ($duplicate == 0) {

						$image_name = preg_replace("/[^a-zA-Z]+/", "", $videoTitle);
						$contentImage = file_get_contents($videoThumb);
						$fileName = "images/" . $image_name . ".jpg";
						$fp = fopen($fileName, "w");
						fwrite($fp, $contentImage);
						fclose($fp);

						$downloadLink = 0; //generateDownload($videoUrl);
						$videoTitle = mysql_escape_string($videoTitle);

						$insert2 = mysqli_query($con, 'INSERT INTO fecthed_videos (title,link,tags,des,image,channel_id,is_downloaded, download_link, is_singular)VALUES("' . $videoTitle . '", "' . $videoUrl . '", "' . $videoTags . '", "' . $videoDescription . '", "' . $fileName . '", "' . $channelId . '", 0, "' . $downloadLink . ',0")');
						if (!$insert2) {
							echo "Error inserting video" . mysqli_error($con);
						}
					}
					break;
			}
		}
		if ($totalHits > 1) {
			for ($i = 0; $i < $totalHits; $i++) {

				$channelVideos = file_get_contents('https://www.googleapis.com/youtube/v3/search?pageToken=' . $pageToken . '&part=snippet&channelId=' . $channelId . '&maxResults=50&order=date&key=' . API_KEY . '');
				$videosDecoded = json_decode($channelVideos, true);

				$pageToken = $videosDecoded['nextPageToken'];

				foreach ($videosDecoded['items'] as $searchResult) {
					switch ($searchResult['id']['kind']) {
						case 'youtube#video':
							$videoDetails = file_get_contents("https://www.youtube.com/watch?v=" . $searchResult['id']['videoId'] . "");
							$videoThumb = $searchResult['snippet']['thumbnails']['high']['url'];
							$videoUrl = 'http://www.youtube.com/watch?v=' . $searchResult['id']['videoId'];
							$videoTitle = $searchResult['snippet']['title'];

							$connect = file_get_contents("https://www.youtube.com/watch?v=" . $searchResult['id']['videoId'] . "");
							preg_match_all('|<meta property="og\:video\:tag" content="(.+?)">|si', $connect, $tags, PREG_SET_ORDER);
							preg_match_all('|<meta property="og\:description" content="(.+?)">|si', $connect, $descriptions, PREG_SET_ORDER);

							if (isset($tags) && is_array($tags)) {
								$videoTags = "";
								foreach ($tags as $tag) {
									$videoTags .= $tag[1] . ",";
								}
							}
							if (isset($descriptions) && is_array($descriptions)) {
								$videoDescription = '';
								foreach ($descriptions as $description) {
									$videoDescription .= $description[1];
								}
							}

							$checkVideo = mysqli_query($con, 'SELECT COUNT(id) as total FROM fecthed_videos WHERE link = "' . $videoUrl . '"');
							$checkVideo = mysqli_fetch_array($checkVideo);
							$duplicate = $checkVideo['total'];

							if ($duplicate == 0) {

								$image_name = preg_replace("/[^a-zA-Z]+/", "", $videoTitle);
								$contentImage = file_get_contents($videoThumb);
								$fileName = "images/" . $image_name . ".jpg";
								$fp = fopen($fileName, "w");
								fwrite($fp, $contentImage);
								fclose($fp);

								$downloadLink = 0; //generateDownload($videoUrl);
								$videoTitle = mysql_escape_string($videoTitle);

								$insert2 = mysqli_query($con, 'INSERT INTO fecthed_videos (title,link,tags,des,image,channel_id,is_downloaded,download_link,is_singular)VALUES("' . $videoTitle . '", "' . $videoUrl . '", "' . $videoTags . '", "' . $videoDescription . '", "' . $fileName . '", "' . $channelId . '", 0, "' . $downloadLink . ',0")');
								if (!$insert2) {
									echo "Error inserting video" . mysqli_error($con);
								}
							}
							break;
					}
				}
			}
		}
	}
}

if (isset($_GET['delete_id'])) {

	$deleteId = mysqli_query($con, "SELECT channel_id FROM channels WHERE id = '" . $_GET['delete_id'] . "'");

	$deleteId = mysqli_fetch_array($deleteId);
	$deleteId = $deleteId['channel_id'];

	$delete = mysqli_query($con, "DELETE FROM channels WHERE id = '" . $_GET['delete_id'] . "'");
	$delete = mysqli_query($con, "DELETE FROM fecthed_videos WHERE channel_id = '" . $deleteId . "'");
}

$query = mysqli_query($con, 'SELECT * FROM channels');
?>

<div class="container">
<form action="" method="POST">
	<div class="form-group col-lg-3">
		<label>Channel UserName</label>
		<input type="text" name="channel_name" placeholder="Enter Channel User Name" class="form-control"  >
		Or
		<input type="text" name="channel_id" placeholder="Enter Channel Id" class="form-control"  ><br>
		<input type="submit" name="submit" value="Add New" class="btn btn-default">
	</div>
</form>
<table class="table">
	<tr>
		<th>S. No</th>
		<th>Channel Name</th>
		<th>Channel Id</th>
		<th>Delete</th>
	</tr>
		<tbody>
		<?php $counter = 1;while ($res = mysqli_fetch_array($query)) {?>
			<tr>
				<td><?php echo $counter;?></td>
				<td><?php echo $res['channel_name'];?></td>
				<td><?php echo $res['channel_id'];?></td>
				<td><a href="?delete_id=<?php echo $res['id'];?>">X</a></td>
			</tr>
			<?php $counter++;}
?>
		</tbody>
</table>
</div>