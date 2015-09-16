<?php
require_once 'header.php';

if (isset($_GET['delete_id']) && $_GET['delete_id'] != '') {
	$delete = mysqli_query($con, 'DELETE FROM fecthed_videos WHERE id = "' . $_GET['delete_id'] . '"');
}
if (isset($_GET['delete_all']) && $_GET['delete_id'] != 'all') {
	$delete = mysqli_query($con, 'DELETE FROM fecthed_videos WHERE is_singular = 1');
}
?>

<div class="container">
	<a href="?delete_all=delete" class="btn btn-default pull-right">Delete All</a><br><br>
	<table class="table">
	<tr>
		<th>S. No</th>
		<th width="70%">Title</th>
		<th>Delete</th>
	</tr>
		<tbody>
		<?php
$videos = mysqli_query($con, 'SELECT * FROM fecthed_videos WHERE is_singular = 1');
$counter = 1;
while ($video = mysqli_fetch_array($videos)) {
	?>
			<tr>
				<td><?php echo $counter;?></td>
				<td><?php echo $video['title'];?></td>
				<td><a href="?delete_id=<?php echo $video['id'];?>" class="btn btn-default">Delete</a></td>
			</tr>
			<?php //exit;
	$counter++;
}
?>
		</tbody>
</table>

</div>