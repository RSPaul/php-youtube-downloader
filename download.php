<?php

require_once 'header.php';

ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

$num_rec_per_page = 50;

if (isset($_GET["page"])) {
	$page = $_GET["page"];
} else {
	$page = 1;
}

$start_from = ($page - 1) * $num_rec_per_page;
//echo 'SELECT * FROM fecthed_videos LIMIT $start_from, $num_rec_per_page';
$videos = mysqli_query($con, 'SELECT * FROM fecthed_videos LIMIT ' . $start_from . ', ' . $num_rec_per_page . '');

$total = mysqli_query($con, 'SELECT COUNT(id)  AS total FROM fecthed_videos');
$checkVideo = mysqli_fetch_array($total);
$total_records = $checkVideo['total'];

$total_pages = ceil($total_records / $num_rec_per_page);

?>
<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.btn-download').click(function (e){
			$('.wait-div').slideDown(500);
			e.preventDefault();
			var videoId = $(this).attr('href');
			var rowId = $(this).attr('id');
			$.ajax({
				url:'download_video.php',
				type:'GET',
				data:{videoid:videoId},
				success: function(response){
					console.log('success', response);
					var newHtml = "<td><a href='"+ response +"' >Click to Download Video</a></td>";
					$("tr#"+rowId).append(newHtml);
					$('.wait-div').slideUp(2000);
				},error: function(error){
					console.log('Something wrong',error);
				}
			});
		});
	});
</script>
<style type="text/css">
	.wait-div{
		display: none;
	}
</style>
<div class="container">
<div class="wait-div"style="text-align: center; font-size: 20px; color: green;">Please wait while system generated download link</div ><br><br>
<table class="table">
	<tr>
		<th>S. No</th>
		<th width="25%">Title</th>
		<th>Meta Data</th>
		<th>Image</th>
		<th>Watch</th>
		<th>Download</th>
		<th>Download Link</th>
	</tr>
		<tbody>
		<?php
$counter = 1;
while ($video = mysqli_fetch_array($videos)) {
	$image_name = preg_replace("/[^a-zA-Z]+/", "", $video['title']);
	?>
			<tr id="<?php echo $counter;?>">
				<td><?php echo $counter;?></td>
				<td><?php echo $video['title'];?></td>
				<td><a target="_blank" href="download_meta_data.php?id=<?php echo $video['id'];?>" class='btn btn default'>Download</a></td>
				<td>
					<a href="<?php echo $video['image'];?>" download="<?php echo $video['image'];?>" class="btn btn-default">Download
				    	<!-- <img width="150px" src="<?php echo $video['image'];?>"> -->
					</a>
				</td>
				<td><a href="<?php echo $video['link'];?>" target="_blank" >Watch</a></td>
				<td><a id="<?php echo $counter;?>" href="<?php echo $video['link'];?>" class="btn btn-default btn-download">Download</a>
				</td>
			</tr>
			<?php //exit;
	$counter++;
}
?>
		</tbody>
</table>
<ul class="pagination">
<li <?php if ($_GET['page'] == 1) {echo "class='disabled'";}
?>><a href='?page=1'>First Page</a></li>
<?php
for ($i = 1; $i <= $total_pages; $i++) {
	?>
<li <?php if ($_GET['page'] == $i) {echo "class='active'";}
	?> >  <a href='?page=<?php echo $i;?>'><?php echo $i;?> </a></li>
<?php
}
?>
<li <?php if ($_GET['page'] == $total_pages) {echo "class='disabled'";}
?>><a href='?page=<?php echo $total_pages;?>'>Last Page</a></li>

</ul>
</div>