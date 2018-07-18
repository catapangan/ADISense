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

/* Check GET Request Values */
if (!isset($_GET['pi']))
{
	$response = array('status' => 104, 'message' => 'No Specified Station ID Value');
	echo json_encode($response);
	mysqli_close($conn);
	exit(0);
}
if (!isset($_GET['token']))
{
	$response = array('status' => 101, 'message' => 'No Specified TOKEN Value');
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
if ($_GET['token'] != base64_encode($tk_ref['token']))
{
	$response = array('status' => 102, 'message' => 'Wrong TOKEN Value');
	echo json_encode($response);
	mysqli_close($conn);
	exit(0);
}

/* Check Station Connection Status */
$station_id = mysqli_escape_string($conn, $_GET['pi']);
$connq = "SELECT * FROM conn_log WHERE pi='" . $station_id . "' LIMIT 1";
if (!($connr = mysqli_query($conn, $connq)))
{
	$response = array('status' => 108, 'message' => 'Failed to fetch connection data');
	echo json_encode($response);
	mysqli_close($conn);
	exit(0);
}
else
{
	$conn_row = mysqli_fetch_assoc($connr);
	$now = new DateTime();
	$last_conn = new DateTime($conn_row['date_time']);
	$diff_time = $now->diff($last_conn);
	
	if ($conn_row['status'] != 0 || $diff_time->h > 1)
	{
		$response = array('status' => 109, 'message' => 'Station is down');
		echo json_encode($response);
		mysqli_close($conn);
		exit(0);
	}
}

/* Setup Query */
if (isset($_GET['qnum']))
{
	if ($_GET['qnum'] == 1 || $_GET['qnum'] == 2)
	{
		$trq = "SELECT trace_id, starttime, endtime, intensity, pga, pgv, data_status FROM " . $station_id . "_trace_data ORDER BY trace_id DESC LIMIT 1";
	}
	elseif ($_GET['qnum'] == 3)
	{
		$today		= new DateTime($now->format('Y-m-d'));
		$nowdate	= mysqli_escape_string($conn, $today->format('Y-m-d H:i:s'));
		$trq = "SELECT trace_id, starttime, endtime, intensity, pga, pgv, data_status FROM " . $station_id . "_trace_data WHERE starttime > '" . $nowdate . "' ORDER BY starttime DESC LIMIT 300";
	}
	else
	{
		$trq = "SELECT trace_id, starttime, endtime, intensity, pga, pgv, data_status FROM " . $station_id . "_trace_data ORDER BY trace_id DESC";
	}
}
else
{
	$trq = "SELECT trace_id, starttime, endtime, intensity, pga, pgv, data_status FROM " . $station_id . "_trace_data ORDER BY trace_id DESC";
}

/* Database Query */
$num_data = 0;
$trr = mysqli_query($conn, $trq);
if (mysqli_num_rows($trr) <= 0)
{
	$response = array('status' => 106, 'message' => 'No trace data available');
	echo json_encode($response);
	mysqli_close($conn);
	exit(0);
}
$response = array();
while($tr_row = mysqli_fetch_assoc($trr))
{
	if ($_GET['qnum'] == 1 || $_GET['qnum'] == 3)
	{
		$response[$num_data] = array(	'status' => 4,
										'starttime' => $tr_row['starttime'],
										'endtime' => $tr_row['endtime'],
										'intensity' => $tr_row['intensity'],
										'pga' => $tr_row['pga'],
										'pgv' => $tr_row['pgv']);
			
		
		$num_data = $num_data + 1;
	}
	else
	{
		if ($tr_row['data_status'] == 0)
		{
			$response[$num_data] = array(	'status' => 4,
											'starttime' => $tr_row['starttime'],
											'endtime' => $tr_row['endtime'],
											'intensity' => $tr_row['intensity'],
											'pga' => $tr_row['pga'],
											'pgv' => $tr_row['pgv']);
			
			$num_data = $num_data + 1;
			$dsq = "UPDATE " . $station_id . "_trace_data SET data_status = 1 WHERE trace_id = " . $tr_row['trace_id'];
			if (!(mysqli_query($conn, $dsq)))
			{	
				$response = array('status' => 103, 'message' => mysqli_error($conn));
				echo json_encode($response);
				mysqli_close($conn);
				exit(0);
			}
		}
		else
		{
			break;
		}
	}
}

/* Handle No New Data */
if ($num_data == 0)
{
	$response = array(	'status' => 5,
						'message' => 'No New Data Found');
	echo json_encode($response);
	mysqli_close($conn);
	exit(0);
}
else
{
	echo json_encode($response);
}

/* Close Connection */
mysqli_close($conn);
exit(0);

?>