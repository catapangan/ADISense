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

if (!isset($_POST['qnum']))
{
	$response = array('status' => 104);
	echo json_encode($response);
	exit(0);
}

switch($_POST['qnum'])
{
	case 0:
		$trq = "SELECT starttime, endtime, intensity, pga, pgv FROM trace_data ORDER BY trace_id DESC LIMIT 1";
		$trr = mysqli_query($conn, $trq);
		if (mysqli_num_rows($trr) <= 0)
		{
			$response = array('status' => 106);
			echo json_encode($response);
			exit(0);
		}

		$tr_row = mysqli_fetch_assoc($trr);
		$response = array(	'status' => 4,
							'starttime' => $tr_row['starttime'],
							'endtime' => $tr_row['endtime'],
							'intensity' => $tr_row['intensity'],
							'pga' => $tr_row['pga'],
							'pgv' => $tr_row['pgv']);
		echo json_encode($response);
		break;
	
	default:
		break;
}

mysqli_close($conn);

?>