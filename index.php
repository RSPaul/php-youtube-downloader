<?php

require_once 'header.php';

$client = new Google_Client();
$client->setDeveloperKey($DEVELOPER_KEY);
$youtube = new Google_YoutubeService($client);

?>
<style type="text/css">
	.no-style, .no-style2 {
		list-style: none;
		padding: 0;
	}
	.no-style > li {
	float: left;
		margin-right: 10px;
	width: 22%;
	}
	.mrgn-left {
		margin-left: 10px;
	}
	.video-info {
	word-wrap: break-word;
	}
	.text-left {
	}
</style>
<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.btn-default-move').click(function (e){
			e.preventDefault();
			var formId = $(this).attr('id');
			//alert(formId);
			$.ajax({
				url:'add.php',
				type:'POST',
				data:$('#'+formId).serialize(),
				dataType: 'json',
				success: function(response){
					console.log('Sucess', response);
					if(response.success == true){
						alert(response.msg);
					}else{
						alert(response.msg);

					}
				},
				error: function(error){
					console.log('Error', error);
				}

			});
		});
	});
</script>
<form method="GET">
<div class="form-group">
	<label>Search Keywords</label>
	<input type="search" id="q" name="q" placeholder="Enter Search Term" class="form-control" required>
</div>
<div class="form-group">
	<input type="submit" value="Search" class="btn btn-default">
</div>
</form>
<strong>
Or Enter direct video link
</strong>
<form method="GET">
<div class="form-group">
	<!-- <label></label> -->
	<input type="url" id="videoid" name="videoid" placeholder="Enter Video Url" class="form-control" required>
</div>
<div class="form-group">
	<input type="submit" value="Search" class="btn btn-default">
</div>
</form>
<div id="response"></div>
<?php
$config['ThumbnailImageMode'] = 1;
$config['VideoLinkMode'] = 'direct';
$config['feature']['browserExtensions'] = true;
date_default_timezone_set("Asia/Kolkata");
$debug = false;function curlGet($URL) {
	$ch = curl_init();
	$timeout = 3;
	curl_setopt($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$tmp = curl_exec($ch);
	curl_close($ch);return $tmp;}function get_location($url) {
	$Uixyullll0 = curl_init();
	curl_setopt($Uixyullll0, CURLOPT_URL, $url);
	curl_setopt($Uixyullll0, CURLOPT_HEADER, true);
	curl_setopt($Uixyullll0, CURLOPT_NOBODY, true);
	curl_setopt($Uixyullll0, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($Uixyullll0, CURLOPT_TIMEOUT, 10);
	$r = curl_exec($Uixyullll0);foreach (explode("\n", $r) as $header) {if (strpos($header, 'Location: ') === 0) {return trim(substr($header, 10));}}
	return '';}function get_size($url) {
	$Uixyullll0 = curl_init();
	curl_setopt($Uixyullll0, CURLOPT_URL, $url);
	curl_setopt($Uixyullll0, CURLOPT_HEADER, true);
	curl_setopt($Uixyullll0, CURLOPT_NOBODY, true);
	curl_setopt($Uixyullll0, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($Uixyullll0, CURLOPT_TIMEOUT, 10);
	$r = curl_exec($Uixyullll0);foreach (explode("\n", $r) as $header) {if (strpos($header, 'Content-Length:') === 0) {return trim(substr($header, 16));}}
	return '';}
function get_description($url) {
	$fullpage = curlGet($url);
	$dom = new DOMDocument();@$dom->loadHTML($fullpage);
	$xpath = new DOMXPath($dom);
	$tags = $xpath->query('//div[@class="info-description-body"]');foreach ($tags as $tag) {$my_description .= (trim($tag->nodeValue));}
	return utf8_decode($my_description);}ob_start();function clean($uixuixuixY) {$uixuixuixY = str_replace(' ', '-', $uixuixuixY);return preg_replace('/[^A-Za-z0-9\-]/', '', $uixuixuixY);}
function formatBytes($bytes, $precision = 2) {
	$units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);
	$bytes /= pow(1024, $pow);return round($bytes, $precision) . '' . $units[$pow];}function is_chrome() {
	$agent = $_SERVER['HTTP_USER_AGENT'];if (preg_match("/like\sGecko\)\sChrome\//", $agent)) {
		if (!strstr($agent, 'Iron')) {
			return true;
		}
	}
	return false;}

if (isset($_REQUEST['videoid'])) {
	$xYxuioxuiVxi = $_REQUEST['videoid'];if (strpos($xYxuioxuiVxi, "https://youtu.be/") !== false) {$xYxuioxuiVxi = str_replace("https://youtu.be/", "", $xYxuioxuiVxi);}if ($xYxuioxuiVxi == "") {echo 'Please Enter Youtube Video URL';exit;}if (strlen($xYxuioxuiVxi) > 11) {
		$url = parse_url($xYxuioxuiVxi);
		$xYxuioxuiVxi = NULL;if (is_array($url) && count($url) > 0 && isset($url['query']) && !empty($url['query'])) {
			$parts = explode('&', $url['query']);if (is_array($parts) && count($parts) > 0) {
				foreach ($parts as $p) {
					$pattern = '/^v\=/';if (preg_match($pattern, $p)) {
						$xYxuioxuiVxi = preg_replace($pattern, '', $p);
						break;}}}if (!$xYxuioxuiVxi) {
			}} else {echo '<p>Invalid url</p>';exit;}}} else {

}

if (isset($_GET['q'])) {

	$counter = 1;
	$searchResponse = $youtube->search->listSearch('id,snippet', array(
		'q' => $_GET['q'],
		'maxResults' => 20,
	));
	$pageToken = $searchResponse['nextPageToken'];
	$totalPages = $searchResponse['pageInfo']['totalResults'];
	$channelName = $searchResponse['channelTitle'];
	echo '<table class="table">';
	echo '<tr><th>S No.</th><th>Title</th><th>Watch</th><th>Move</th><tr>';

	foreach ($searchResponse['items'] as $searchResult) {
		$channelId = $searchResult['snippet']['channelId'];
		if ($channelId == '') {
			$channelDetails = file_get_contents('https://www.googleapis.com/youtube/v3/channels?key=' . API_KEY . '&forUsername=' . $channelName . '&part=id');
			$channelDecoded = json_decode($channelDetails, true);
			$channelId = $channelDecoded['items'][0]['id'];

		}
		switch ($searchResult['id']['kind']) {
			case 'youtube#video':
				echo '<tr><td>' . $counter . '</td>';
				$oO0oO0qoQo0O = 'http://www.youtube.com/get_video_info?&video_id=' . $searchResult['id']['videoId'] . '&asv=3&el=detailpage&hl=en_US';
				$oO0oO0qoQo0O = curlGet($oO0oO0qoQo0O);
				$thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';
				parse_str($oO0oO0qoQo0O);

				$connect = file_get_contents("https://www.youtube.com/watch?v=" . $searchResult['id']['videoId'] . "");
				preg_match_all('|<meta property="og\:video\:tag" content="(.+?)">|si', $connect, $tags, PREG_SET_ORDER);
				preg_match_all('|<meta property="og\:description" content="(.+?)">|si', $connect, $descriptions, PREG_SET_ORDER);
				echo '<form action="" method="POST" id="' . $counter . '">';

				$videoThumb = "https://i.ytimg.com/vi/" . $searchResult['id']['videoId'] . "/hqdefault.jpg";
				$image_name = preg_replace("/[^a-zA-Z]+/", "", $title);
				$contentImage = file_get_contents($videoThumb);
				$fileName = "images/" . $image_name . ".jpg";
				$fp = fopen($fileName, "w");
				fwrite($fp, $contentImage);
				fclose($fp);

				echo '<input type="hidden" name="title" value="' . $title . '">';
				echo '<td>' . $title . '</td>';
				echo '<td><a href="https://www.youtube.com/watch?v=' . $searchResult['id']['videoId'] . '" target="_blank">Watch</a></td>';

				if (isset($tags) && is_array($tags)) {
					foreach ($tags as $tag) {
						$hiddenTag .= $tag[1] . ",";
					}
				}

				if (isset($descriptions) && is_array($descriptions)) {
					foreach ($descriptions as $description) {
						$hiddenDes .= $description[1];
					}
				}
				echo '<input type="hidden" name="tags" value="' . $hiddenTag . '">';
				echo '<input type="hidden" name="description" value="' . $hiddenDes . '">';
				echo '<input type="hidden" name="image" value="' . $fileName . '">';
				echo '<input type="hidden" name="link" value="https://www.youtube.com/watch?v=' . $searchResult['id']['videoId'] . '">';
				$ttototot = $title;
				$cleanedtitle = clean($title);
				if (isset($url_encoded_fmt_stream_map)) {
					$loOqoQ0qOqpo = explode(',', $url_encoded_fmt_stream_map);
					if ($debug) {
						echo '<pre>';
						print_r($loOqoQ0qOqpo);
						echo '</pre>';
					}
				} else {
					echo '<p>No encoded format stream found.</p>';
					echo '<p>Here is what we got from YouTube:</p>';
					echo $oO0oO0qoQo0O;
				}
				if (count($loOqoQ0qOqpo) == 0) {
					echo '<p>No format stream map found - was the video id correct?</p>';
					exit;
				}
				$yYiI1opQqPllhv[] = '';
				$xuiV1oj0lnoP = 0;
				$xuiV1oj0lnoPpbits = $xuiV1oj0lnoPp = $xuiV1oj0lnoPtag = $sig = $quality = '';
				$expire = time();
				foreach ($loOqoQ0qOqpo as $format) {
					parse_str($format);
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['itag'] = $xuiV1oj0lnoPtag;
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] = $quality;
					$type = explode(';', $type);
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['type'] = $type[0];
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['url'] = urldecode($url) . '&signature=' . $sig;
					parse_str(urldecode($url));
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['expires'] = date("G:i:s T", $expire);
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['ipbits'] = $xuiV1oj0lnoPpbits;
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['ip'] = $xuiV1oj0lnoPp;
					$xuiV1oj0lnoP++;
				}
				for ($xuiV1oj0lnoP = 0; $xuiV1oj0lnoP < count($yYiI1opQqPllhv); $xuiV1oj0lnoP++) {

					if ($yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] == 'hd720' || $yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] == 'medium') {

						$size_video = formatBytes(get_size($yYiI1opQqPllhv[$xuiV1oj0lnoP]['url']));
						if ($size_video != '0B') {

							if ($yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] == 'hd720') {
								echo '<input type="hidden" name="download_link" value="' . $yYiI1opQqPllhv[$xuiV1oj0lnoP]['url'] . '">';
							} else {
								echo '<input type="hidden" name="download_link" value="' . $yYiI1opQqPllhv[$xuiV1oj0lnoP]['url'] . '">';

							}
						}
					}
				}

				echo '<td><button type="submit" name="Move_To_Download" class="btn btn-default btn-default-move"  id="' . $counter . '">Move To Download</button></td>';
				echo '</form>';
				$counter++;
				echo '</tr>';

		}
	}
	echo '</table>';
	if ($totalPages > 1) {
		echo "<a href='?page=nextpage&page_token=" . $pageToken . "&channel_id=" . $channelId . "'>Next Page</a>";
	}

} elseif (isset($_GET['page']) && isset($_GET['page_token'])) {

	echo '<table class="table">';
	echo '<tr><th>S No.</th><th>Title</th><th>Watch</th><th>Move</th><tr>';
	$counter = 1;
	$nextPageToken = $_GET['page_token'];
	$channelId = $_GET['channel_id'];
	$channelVideos = file_get_contents('https://www.googleapis.com/youtube/v3/search?pageToken=' . $nextPageToken . '&part=snippet&maxResults=20&key=' . API_KEY . '');
	$videosDecoded = json_decode($channelVideos, true);
	$channelId = $channelDecoded['items'][0]['id'];

	$pageToken = $videosDecoded['nextPageToken'];
	$totalPages = $videosDecoded['pageInfo']['totalResults'];

	foreach ($videosDecoded['items'] as $searchResult) {

		switch ($searchResult['id']['kind']) {
			case 'youtube#video':
				echo '<tr><td>' . $counter . '</td>';
				$oO0oO0qoQo0O = 'http://www.youtube.com/get_video_info?&video_id=' . $searchResult['id']['videoId'] . '&asv=3&el=detailpage&hl=en_US';
				$oO0oO0qoQo0O = curlGet($oO0oO0qoQo0O);
				$thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';
				parse_str($oO0oO0qoQo0O);

				$connect = file_get_contents("https://www.youtube.com/watch?v=" . $searchResult['id']['videoId'] . "");
				preg_match_all('|<meta property="og\:video\:tag" content="(.+?)">|si', $connect, $tags, PREG_SET_ORDER);
				preg_match_all('|<meta property="og\:description" content="(.+?)">|si', $connect, $descriptions, PREG_SET_ORDER);
				echo '<form action="" method="POST" id="' . $counter . '">';

				$videoThumb = "https://i.ytimg.com/vi/" . $searchResult['id']['videoId'] . "/hqdefault.jpg";
				$image_name = preg_replace("/[^a-zA-Z]+/", "", $title);
				$contentImage = file_get_contents($videoThumb);
				$fileName = "images/" . $image_name . ".jpg";
				$fp = fopen($fileName, "w");
				fwrite($fp, $contentImage);
				fclose($fp);

				echo '<input type="hidden" name="title" value="' . $title . '">';
				echo '<td>' . $title . '</td>';
				echo '<td><a href="https://www.youtube.com/watch?v=' . $searchResult['id']['videoId'] . '" target="_blank">Watch</a></td>';

				if (isset($tags) && is_array($tags)) {
					foreach ($tags as $tag) {
						$hiddenTag .= $tag[1] . ",";
					}
				}

				if (isset($descriptions) && is_array($descriptions)) {
					foreach ($descriptions as $description) {
						$hiddenDes .= $description[1];
					}
				}
				echo '<input type="hidden" name="tags" value="' . $hiddenTag . '">';
				echo '<input type="hidden" name="description" value="' . $hiddenDes . '">';
				echo '<input type="hidden" name="image" value="' . $fileName . '">';
				echo '<input type="hidden" name="link" value="https://www.youtube.com/watch?v=' . $searchResult['id']['videoId'] . '">';
				$ttototot = $title;
				$cleanedtitle = clean($title);
				if (isset($url_encoded_fmt_stream_map)) {
					$loOqoQ0qOqpo = explode(',', $url_encoded_fmt_stream_map);
					if ($debug) {
						echo '<pre>';
						print_r($loOqoQ0qOqpo);
						echo '</pre>';
					}
				} else {
					echo '<p>No encoded format stream found.</p>';
					echo '<p>Here is what we got from YouTube:</p>';
					echo $oO0oO0qoQo0O;
				}
				if (count($loOqoQ0qOqpo) == 0) {
					echo '<p>No format stream map found - was the video id correct?</p>';
					exit;
				}
				$yYiI1opQqPllhv[] = '';
				$xuiV1oj0lnoP = 0;
				$xuiV1oj0lnoPpbits = $xuiV1oj0lnoPp = $xuiV1oj0lnoPtag = $sig = $quality = '';
				$expire = time();
				foreach ($loOqoQ0qOqpo as $format) {
					parse_str($format);
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['itag'] = $xuiV1oj0lnoPtag;
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] = $quality;
					$type = explode(';', $type);
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['type'] = $type[0];
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['url'] = urldecode($url) . '&signature=' . $sig;
					parse_str(urldecode($url));
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['expires'] = date("G:i:s T", $expire);
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['ipbits'] = $xuiV1oj0lnoPpbits;
					$yYiI1opQqPllhv[$xuiV1oj0lnoP]['ip'] = $xuiV1oj0lnoPp;
					$xuiV1oj0lnoP++;
				}
				for ($xuiV1oj0lnoP = 0; $xuiV1oj0lnoP < count($yYiI1opQqPllhv); $xuiV1oj0lnoP++) {

					if ($yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] == 'hd720' || $yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] == 'medium') {

						$size_video = formatBytes(get_size($yYiI1opQqPllhv[$xuiV1oj0lnoP]['url']));
						if ($size_video != '0B') {

							if ($yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] == 'hd720') {
								echo '<input type="hidden" name="download_link" value="' . $yYiI1opQqPllhv[$xuiV1oj0lnoP]['url'] . '">';
							} else {
								echo '<input type="hidden" name="download_link" value="' . $yYiI1opQqPllhv[$xuiV1oj0lnoP]['url'] . '">';

							}
						}
					}
				}

				echo '<td><button type="submit" name="Move_To_Download" class="btn btn-default btn-default-move"  id="' . $counter . '">Move To Download</button></td>';
				echo '</form>';
				$counter++;
				echo '</tr>';

		}
	}
	echo '</table>';
	if ($totalPages > 1) {
		echo "<a href='?page=nextpage&page_token=" . $pageToken . "&channel_id=" . $channelId . "'>Next Page</a>";
	}
} elseif (isset($_GET['videoid'])) {
	?>
<div class="download_cotainer">
<?php
$oO0oO0qoQo0O = 'http://www.youtube.com/get_video_info?&video_id=' . $xYxuioxuiVxi . '&asv=3&el=detailpage&hl=en_US';
	$oO0oO0qoQo0O = curlGet($oO0oO0qoQo0O);
	$counter = 1;
	echo '<table class="table">';
	echo '<tr><th>S No.</th><th>Title</th><th>Watch</th><th>Move</th><tr>';
	echo '<tr>';
	$thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';
	parse_str($oO0oO0qoQo0O);

	$connect = file_get_contents("https://www.youtube.com/watch?v=" . $xYxuioxuiVxi . "");
	preg_match_all('|<meta property="og\:video\:tag" content="(.+?)">|si', $connect, $tags, PREG_SET_ORDER);
	preg_match_all('|<meta property="og\:description" content="(.+?)">|si', $connect, $descriptions, PREG_SET_ORDER);
	echo '<br><br>';
	echo '<form action="" method="POST" id="' . $counter . '">';
	echo '<td>1</td>';
	echo '<td>' . $title . '</td>';
	echo '<input type="hidden" name="title" value="' . $title . '">';

	if (isset($tags) && is_array($tags)) {
		foreach ($tags as $tag) {
			$hiddenTag .= $tag[1] . ",";
		}
	}

	if (isset($descriptions) && is_array($descriptions)) {
		foreach ($descriptions as $description) {
			$hiddenDes .= $description[1];
		}
	}
	$ttototot = $title;
	$cleanedtitle = clean($title);
	if (isset($url_encoded_fmt_stream_map)) {
		$loOqoQ0qOqpo = explode(',', $url_encoded_fmt_stream_map);
		if ($debug) {
			echo '<pre>';
			print_r($loOqoQ0qOqpo);
			echo '</pre>';
		}
	} else {
		echo '<p>No encoded format stream found.</p>';
		echo '<p>Here is what we got from YouTube:</p>';
		echo $oO0oO0qoQo0O;
	}
	if (count($loOqoQ0qOqpo) == 0) {
		echo '<p>No format stream map found - was the video id correct?</p>';
		exit;
	}
	$yYiI1opQqPllhv[] = '';
	$xuiV1oj0lnoP = 0;
	$xuiV1oj0lnoPpbits = $xuiV1oj0lnoPp = $xuiV1oj0lnoPtag = $sig = $quality = '';
	$expire = time();
	foreach ($loOqoQ0qOqpo as $format) {
		parse_str($format);
		$yYiI1opQqPllhv[$xuiV1oj0lnoP]['itag'] = $xuiV1oj0lnoPtag;
		$yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] = $quality;
		$type = explode(';', $type);
		$yYiI1opQqPllhv[$xuiV1oj0lnoP]['type'] = $type[0];
		$yYiI1opQqPllhv[$xuiV1oj0lnoP]['url'] = urldecode($url) . '&signature=' . $sig;
		parse_str(urldecode($url));
		$yYiI1opQqPllhv[$xuiV1oj0lnoP]['expires'] = date("G:i:s T", $expire);
		$yYiI1opQqPllhv[$xuiV1oj0lnoP]['ipbits'] = $xuiV1oj0lnoPpbits;
		$yYiI1opQqPllhv[$xuiV1oj0lnoP]['ip'] = $xuiV1oj0lnoPp;
		$xuiV1oj0lnoP++;
	}

	$videoThumb = "https://i.ytimg.com/vi/" . $xYxuioxuiVxi . "/hqdefault.jpg";
	$image_name = preg_replace("/[^a-zA-Z]+/", "", $cleanedtitle);
	$contentImage = file_get_contents($videoThumb);
	$fileName = "images/" . $image_name . ".jpg";
	$fp = fopen($fileName, "w");
	fwrite($fp, $contentImage);
	fclose($fp);

	echo '<input type="hidden" name="tags" value="' . $hiddenTag . '">';
	echo '<input type="hidden" name="description" value="' . $hiddenDes . '">';
	echo '<input type="hidden" name="image" value="' . $fileName . '">';
	echo '<input type="hidden" name="link" value="https://www.youtube.com/watch?v=' . $xYxuioxuiVxi . '">';

	echo '<td><a href="https://www.youtube.com/watch?v=' . $xYxuioxuiVxi . '">Watch</a></td>';

	for ($xuiV1oj0lnoP = 0; $xuiV1oj0lnoP < count($yYiI1opQqPllhv); $xuiV1oj0lnoP++) {

		if ($yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] == 'hd720' || $yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] == 'medium') {

			$size_video = formatBytes(get_size($yYiI1opQqPllhv[$xuiV1oj0lnoP]['url']));
			if ($size_video != '0B') {

				if ($yYiI1opQqPllhv[$xuiV1oj0lnoP]['quality'] == 'hd720') {
					echo '<input type="hidden" name="download_link" value="' . $yYiI1opQqPllhv[$xuiV1oj0lnoP]['url'] . '">';
				} else {
					echo '<input type="hidden" name="download_link" value="' . $yYiI1opQqPllhv[$xuiV1oj0lnoP]['url'] . '">';
				}
			}
		}
	}
	echo '<td><button name="Move_To_Download" class="btn btn-default btn-default-move" value="Move To Download" id="' . $counter . '">Move To Download</button></td>';
	echo '</tr>';
	echo "</form>";
	echo '</table>';

}

?>