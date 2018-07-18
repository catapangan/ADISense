<?php

header("Content-Type: application/json");

$db_name = "adisense";	
$db_host = "mysql6.gear.host";
$db_user = "adisense";
$db_pass = "AD1_facilities";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn)
{
	$response = array('status' => -1, 'message' => mysqli_connect_error(), 'numsamples' => 0);
	echo json_encode($response);
	exit(0);
}

$query = "SELECT (UNIX_TIMESTAMP(NOW())*1000)";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$now = floatval($row['(UNIX_TIMESTAMP(NOW())*1000)']);
$response = array('now' => $now);
echo json_encode($response);

?>
