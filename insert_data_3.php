<?php

header("Content-Type: application/json");

$hostname	= "mysql3.gear.host";	
$database	= "shakefacility";
$user		= "shakefacility";
$pass		= "AD1_facilities";

$conn = mysqli_connect($hostname, $user, $pass, $database);

if (!$conn)
{
	$response = array('status' => 105, 'message' => mysqli_connect_error());
	echo json_encode($response);
	exit(0);
}

$tkq = "SELECT token FROM token_list LIMIT 1";
$tkr = mysqli_query($conn, $tkq);

if (mysqli_num_rows($tkr) <= 0)
{
	$response = array('status' => 100);
	echo json_encode($response);
	exit(0);
}

$tk_ref = mysqli_fetch_assoc($tkr);

if (!isset($_POST['token']))
{
	$response = array('status' => 101);
	echo json_encode($response);
	exit(0);
}

$token=$_POST['token'];

if ($token != base64_encode($tk_ref['token']))
{
	$response = array('status' => 102);
	echo json_encode($response);
	exit(0);
}

$starttime = $_POST['starttime'];
$endtime = $_POST['endtime'];
$intensity = $_POST['intensity'];
$pga = $_POST['pga'];
$pgv = $_POST['pgv'];

$inq = "INSERT INTO trace_data(starttime, endtime, intensity, pga, pgv) VALUES(" . $starttime . "," . $endtime . "," . $intensity . "," . $pga . ","  . $pgv . ")";

if (mysqli_query($conn, $inq))
{
	$response = array('status' => 4, 'message' => $inq);
}
else
{
	$response = array('status' => 103);
}

echo json_encode($response);

mysqli_close($conn);
exit(0);

?>