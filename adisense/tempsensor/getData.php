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

if (!isset($_GET['f']) || !isset($_GET['lt']))
{
	$response = array('status' => -2, 'message' => 'No specified query option or device', 'numsamples' => 0);
	echo json_encode($response);
	exit(0);
}

$fp = $_GET['f'];
$lt = $_GET['lt'];

$query = "SELECT intensity, pga, pgv, timestamp FROM calc_data WHERE device='" . $fp . "' AND timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT 1";
$result = mysqli_query($conn, $query);

$num_data = mysqli_num_rows($result);
if ($num_data <= 0)
{
	$response = array('status' => 0, 'numsamples' => $num_data);
	echo json_encode($response);
	exit(0);
}
else
{
	$row = mysqli_fetch_assoc($result);
	$xdata = $row['timestamp'];
	$pga = $row['pga'];
	$pgv = $row['pgv'];
	$intensity = $row['intensity'];
	
	//$usq = "UPDATE calc_data SET status=1 WHERE device='" . $fp . "' AND status=0";
	//$usr = mysqli_query($conn, $usq);
	
	$response = array('status' => 1, 'numsamples' => $num_data, 'xdata' => $xdata, 'pga' => floatval($pga), 'pgv' => floatval($pgv), 'intensity' => floatval($intensity));
	echo json_encode($response);
	exit(0);
}

?>
