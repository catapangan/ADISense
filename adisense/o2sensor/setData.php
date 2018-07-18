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
if (!isset($_POST['pi']) || !isset($_POST['mac_addr']) || !isset($_POST['panid']) || !isset($_POST['orbit']) || !isset($_POST['active_channel']) || !isset($_POST['tx_power']) || !isset($_POST['rssi']) || !isset($_POST['sleep_dur']) || !isset($_POST['oxygen1']) || !isset($_POST['oxygen2']) || !isset($_POST['oxygen3']) || !isset($_POST['oxygen4']) || !isset($_POST['oxygen5']) || !isset($_POST['oxygen6']) || !isset($_POST['oxygen7']) || !isset($_POST['oxygen8']) || !isset($_POST['oxygen9']) || !isset($_POST['oxygen10']) ||  !isset($_POST['temp1']) || !isset($_POST['temp2']) || !isset($_POST['temp3']) || !isset($_POST['temp4']) || !isset($_POST['temp5']) || !isset($_POST['temp6']) || !isset($_POST['temp7']) || !isset($_POST['temp8']) || !isset($_POST['temp9']) || !isset($_POST['temp10']))
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
$oxygen1 = $_POST['oxygen1'];
$oxygen2 = $_POST['oxygen2'];
$oxygen3 = $_POST['oxygen3'];
$oxygen4 = $_POST['oxygen4'];
$oxygen5 = $_POST['oxygen5'];
$oxygen6 = $_POST['oxygen6'];
$oxygen7 = $_POST['oxygen7'];
$oxygen8 = $_POST['oxygen8'];
$oxygen9 = $_POST['oxygen9'];
$oxygen10 = $_POST['oxygen10'];
$temp1 = $_POST['temp1'];
$temp2 = $_POST['temp2'];
$temp3 = $_POST['temp3'];
$temp4 = $_POST['temp4'];
$temp5 = $_POST['temp5'];
$temp6 = $_POST['temp6'];
$temp7 = $_POST['temp7'];
$temp8 = $_POST['temp8'];
$temp9 = $_POST['temp9'];
$temp10 = $_POST['temp10'];



$trq = "INSERT INTO oxygen_data (pi_id, mac_addr, panid, orbit, active_channel, tx_power, rssi, sleep_dur, oxygen1, oxygen2, oxygen3, oxygen4, oxygen5, oxygen6, oxygen7, oxygen8, oxygen9, oxygen10, temp1, temp2, temp3, temp4, temp5, temp6, temp7, temp8, temp9, temp10, timestamp) VALUES (" . $pi_id . ", '" . $mac_addr . "', '" . $panid . "', " . $orbit . ", " . $active_channel . ", " . $tx_power . ", " . $rssi . ", " . $sleep_dur . ", " . $oxygen1 . ", " . $oxygen2 . ", " . $oxygen3 . ", " . $oxygen4 . ", " . $oxygen5 . ", " . $oxygen6 . ", " . $oxygen7 . ", " . $oxygen8 . ", " . $oxygen9 . ", " . $oxygen10 . ", " . $temp1 . ", " . $temp2 . ", " . $temp3 . ", " . $temp4 . ", " . $temp5 . ", " . $temp6 . ", " . $temp7 . ", " . $temp8 . ", " . $temp9 . ", " . $temp10 . ",  NOW())";
$trr = mysqli_query($conn, $trq);

$response = array('message' => $trq);
echo json_encode($response);

mysqli_close($conn);
exit(0);



