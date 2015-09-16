<?php
require_once 'config.php';

$output = "";
$table = "fecthed_videos"; // Enter Your Table Name
$sql = mysql_query("SELECT * from $table WHERE id = '" . $_GET['id'] . "' ");
$columns_total = mysql_num_fields($sql);

// Get The Field Name

for ($i = 0; $i < $columns_total; $i++) {
	$heading = mysql_field_name($sql, $i);
	$output .= '"' . $heading . '",';
}
$output .= "\n";

// Get Records from the table

while ($row = mysql_fetch_array($sql)) {
	$file_name = preg_replace("/[^a-zA-Z]+/", "", $row['title']);
	for ($i = 0; $i < $columns_total; $i++) {
		$output .= '"' . $row["$i"] . '",';
	}
	$output .= "\n";
}

// Download the file

$filename = "myFile.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $file_name . '.csv');

echo $output;
exit;

?>