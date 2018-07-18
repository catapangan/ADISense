<?php

header("Content-Type: application/json");

/* Connection Properties */
$hostname	= "mysql3.gear.host";	
$database	= "shakefacility";
$user		= "shakefacility";
$pass		= "AD1_facilities";

/* Start Connection */
$conn = mysqli_connect($hostname, $user, $pass, $database);
if (!$conn)
{
	$response = array('status' => 105, 'message' => mysqli_connect_error());
	echo json_encode($response);
	exit(0);
}

/* Check POST Request Values */
if (!isset($_POST['token']))
{
	$response = array('status' => 101, 'message' => 'No Specified TOKEN Value');
	echo json_encode($response);
	mysqli_close($conn);
	exit(0);
}
if (!isset($_POST['pi']) || !isset($_POST['starttime']) || !isset($_POST['endtime']) || !isset($_POST['intensity']) || !isset($_POST['pga']) || !isset($_POST['pgv']))
{
	$response = array('status' => 107, 'message' => 'Missing POST Request Values');
	echo json_encode($response);
	mysqli_close($conn);
	exit(0);
}

/* Check Token Validity */
$tkq = "SELECT token FROM token_list LIMIT 1";
$tkr = mysqli_query($conn, $tkq);
if (mysqli_num_rows($tkr) <= 0)
{
	$response = array('status' => 100, 'message' => 'No TOKEN Entry in Database');
	echo json_encode($response);
	mysqli_close($conn);
	exit(0);
}
$tk_ref = mysqli_fetch_assoc($tkr);
if ($_POST['token'] != base64_encode($tk_ref['token']))
{
	$response = array('status' => 102, 'message' => 'Wrong TOKEN Value');
	echo json_encode($response);
	mysqli_close($conn);
	exit(0);
}

/* Collect POST Request Values */
$station 	= mysqli_escape_string($conn, $_POST['pi']);
$starttime 	= mysqli_escape_string($conn, $_POST['starttime']);
$endtime 	= mysqli_escape_string($conn, $_POST['endtime']);
$intensity 	= mysqli_escape_string($conn, $_POST['intensity']);
$pga 		= mysqli_escape_string($conn, $_POST['pga']);
$pgv 		= mysqli_escape_string($conn, $_POST['pgv']);

/* Insert Data */
$inq = "INSERT INTO " . $station . "_trace_data(starttime, endtime, intensity, pga, pgv) VALUES('" . $starttime . "','" . $endtime . "'," . $intensity . "," . $pga . ","  . $pgv . ")";
if (mysqli_query($conn, $inq))
{
	$response = array('status' => 4, 'message' => 'STATUS OK');
}
else
{
	$response = array('status' => 103, 'message' => mysqli_error($conn));
}
echo json_encode($response);

/* Close Connection */
mysqli_close($conn);
exit(0);

?>