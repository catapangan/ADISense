<?php

header("Content-Type: application/json");

/* Connection Properties */
$hostname	= "mysql6.gear.host";	
$database	= "adisense";
$user		= "adisense";
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
if (!isset($_POST['pi']) || !isset($_POST['mac_addr']) || !isset($_POST['panid']) || !isset($_POST['orbit']) || !isset($_POST['active_channel']) || !isset($_POST['tx_power']) || !isset($_POST['rssi']) || !isset($_POST['sleep_dur']) || !isset($_POST['temp1']) || !isset($_POST['temp2']) || !isset($_POST['temp3']) || !isset($_POST['temp4']) || !isset($_POST['temp5']) || !isset($_POST['temp6']) || !isset($_POST['temp7']) || !isset($_POST['temp8']))
{
	$response = array('status' => 104, 'message' => 'Missing Post Values');
	echo json_encode($response);
	mysqli_close($conn);
	exit(0);
}

$pi_id = $_POST['pi'];
$mac_addr = $_POST['mac_addr'];
$panid = $_POST['panid'];
$orbit = $_POST['orbit'];
$active_channel = $_POST['active_channel'];
$tx_power = $_POST['tx_power'];
$rssi = $_POST['rssi'];
$sleep_dur = $_POST['sleep_dur'];
$temp1 = $_POST['temp1'];
$temp2 = $_POST['temp2'];
$temp3 = $_POST['temp3'];
$temp4 = $_POST['temp4'];
$temp5 = $_POST['temp5'];
$temp6 = $_POST['temp6'];
$temp7 = $_POST['temp7'];
$temp8 = $_POST['temp8'];


$trq = "INSERT INTO temp_data (pi_id, mac_addr, panid, orbit, active_channel, tx_power, rssi, sleep_dur, temp1, temp2, temp3, temp4, temp5, temp6, temp7, temp8, timestamp) VALUES (" . $pi_id . ", '" . $mac_addr . "', '" . $panid . "', " . $orbit . ", " . $active_channel . ", " . $tx_power . ", " . $rssi . ", " . $sleep_dur . ", " . $temp1 . ", " . $temp2 . ", " . $temp3 . ", " . $temp4 . ", " . $temp5 . ", " . $temp6 . ", " . $temp7 . ", " . $temp8 . ", NOW())";
$trr = mysqli_query($conn, $trq);

$response = array('message' => $trq);
echo json_encode($response);

mysqli_close($conn);
exit(0);



