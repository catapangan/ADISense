<?php

$num_samples = 1;

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

if (!isset($_GET['q']) || !isset($_GET['f']) || !isset($_GET['lt']))
{
	$response = array('status' => -2, 'message' => 'No specified query option or device', 'numsamples' => 0);
	echo json_encode($response);
	exit(0);
}

$q = $_GET['q'];
$fp = $_GET['f'];
$lt = $_GET['lt'];

$tbl = 'oxygen_data';

if ($q == 0) 
{
	$query = "SELECT oxygen1, temp1, (UNIX_TIMESTAMP(timestamp)*1000) FROM oxygen_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'oxygen1';
	$col_temp = 'temp1';
	
	//$response = array('status' => 0, 'numsamples' => "Testing");
	//echo json_encode($response);
	//exit(0);
}
else if ($q == 1)
{
	$query = "SELECT oxygen2, temp2, (UNIX_TIMESTAMP(timestamp)*1000) FROM oxygen_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'oxygen2';
	$col_temp = 'temp2';
}
else if ($q == 2) 
{
	$query = "SELECT oxygen3, temp3, (UNIX_TIMESTAMP(timestamp)*1000) FROM oxygen_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'oxygen3';
	$col_temp = 'temp3';
}
else if ($q == 3) 
{
	$query = "SELECT oxygen4, temp4, (UNIX_TIMESTAMP(timestamp)*1000) FROM oxygen_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'oxygen4';
	$col_temp = 'temp4';
}
else if ($q == 4) 
{
	$query = "SELECT oxygen5, temp5, (UNIX_TIMESTAMP(timestamp)*1000) FROM oxygen_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'oxygen5';
	$col_temp = 'temp5';
}
else if ($q == 5) 
{
	$query = "SELECT oxygen6, temp6, (UNIX_TIMESTAMP(timestamp)*1000) FROM oxygen_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'oxygen6';
	$col_temp = 'temp6';
}
else if ($q == 6) 
{
	$query = "SELECT oxygen7, temp7, (UNIX_TIMESTAMP(timestamp)*1000) FROM oxygen_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'oxygen7';
	$col_temp = 'temp7';
}
else if ($q == 7) 
{
	$query = "SELECT oxygen8, temp8, (UNIX_TIMESTAMP(timestamp)*1000) FROM oxygen_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'oxygen8';
	$col_temp = 'temp8';
}
else if ($q == 8) 
{
	$query = "SELECT oxygen9, temp9, (UNIX_TIMESTAMP(timestamp)*1000) FROM oxygen_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'oxygen9';
	$col_temp = 'temp9';
}
else if ($q == 9) 
{
	$query = "SELECT oxygen10, temp10, (UNIX_TIMESTAMP(timestamp)*1000) FROM oxygen_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'oxygen10';
	$col_temp = 'temp10';
}
else if ($q == 10) 
{
	$query = "SELECT timestamp FROM oxygen_data ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'timestamp';
	$col_temp = '';
}
else
{
	$response = array('status' => -2, 'message' => 'Query option is unavailable', 'numsamples' => 0);
	echo json_encode($response);
	exit(0);
}

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
	$xdata = array();
	$ydata = array();
	$i = 0;
	
	while($row = mysqli_fetch_assoc($result))
	{
		$ydata[$i] = $row[$col_name];
		$xdata[$i/2] = $row['(UNIX_TIMESTAMP(timestamp)*1000)'];
		$ydata[$i+1] = $row[$col_temp];
		$i = $i + 2;
	}
	
	//$usq = "UPDATE " . $tbl . " SET status=1 WHERE device='" . $fp . "' AND status=0";
	//$usr = mysqli_query($conn, $usq);
	
	/*
	$xvdata = array_values($xdata);
	$xvdata = array_map('floatval', $xvdata);
	$yvdata = array_values($ydata);
	$yvdata = array_map('floatval', $yvdata);
	*/
	if ($q != 10) 
	{
		$xvdata = floatval($xdata[0]);
		$yvdata = floatval($ydata[0]);
		$ytdata = floatval($ydata[1]);
	}
	else
	{
		$xvdata = $xdata[0];
		$yvdata = $ydata[0];
		$ytdata = $ydata[1];
	}
	$response = array('status' => 1, 'numsamples' => $num_data, 'xdata' => $xvdata, 'ydata' => $yvdata, 'ytdata' => $ytdata);
	echo json_encode($response);
	exit(0);
}

?>
