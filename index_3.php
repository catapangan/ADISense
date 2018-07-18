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
if (!isset($_GET['pi']))
{
	$response = array('status' => 104);
	echo json_encode($response);
	exit(0);
}
if (!isset($_GET['token']))
{
	$response = array('status' => 101);
	echo json_encode($response);
	exit(0);
}

/* Check Token Validity */
$tkq = "SELECT token FROM token_list LIMIT 1";
$tkr = mysqli_query($conn, $tkq);
if (mysqli_num_rows($tkr) <= 0)
{
	$response = array('status' => 100);
	echo json_encode($response);
	exit(0);
}
$tk_ref = mysqli_fetch_assoc($tkr);
if ($_GET['token'] != base64_encode($tk_ref['token']))
{
	$response = array('status' => 102);
	echo json_encode($response);
	exit(0);
}

if (isset($_GET['qnum']))
{
	if ($_GET['qnum'] == 1)
	{
		$trq = "SELECT trace_id, starttime, endtime, intensity, pga, pgv, data_status FROM " . $_GET['pi'] . "_trace_data ORDER BY trace_id DESC LIMIT 1";
	}
	else
	{
		$trq = "SELECT trace_id, starttime, endtime, intensity, pga, pgv, data_status FROM " . $_GET['pi'] . "_trace_data ORDER BY trace_id DESC";
	}
}
else
{
	$trq = "SELECT trace_id, starttime, endtime, intensity, pga, pgv, data_status FROM " . $_GET['pi'] . "_trace_data ORDER BY trace_id DESC";
}
$num_data = 0;
$trr = mysqli_query($conn, $trq);
if (mysqli_num_rows($trr) <= 0)
{
	$response = array('status' => 106);
	echo json_encode($response);
	exit(0);
}
$response = array();
while($tr_row = mysqli_fetch_assoc($trr))
{
	if ($tr_row['data_status'] == 0)
	{
		$response[] = array(	'status' => 4,
								'starttime' => $tr_row['starttime'],
								'endtime' => $tr_row['endtime'],
								'intensity' => $tr_row['intensity'],
								'pga' => $tr_row['pga'],
								'pgv' => $tr_row['pgv']);
		
		echo json_encode($response);
		$num_data = $num_data + 1;
		$dsq = "UPDATE " . $_GET['pi'] . "_trace_data SET data_status = 1 WHERE trace_id = " . $tr_row['trace_id'];
		if (!(mysqli_query($conn, $dsq)))
		{	
			$response = array('status' => 103);
			echo json_encode($response);
			exit(0);
		}
	}
	else
	{
		break;
	}
}

if ($num_data == 0)
{
	$response = array(	'status' => 5,
						'message' => 'No New Data Found');
	echo json_encode($response);
	exit(0);
}

mysqli_close($conn);

?>