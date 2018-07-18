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

$tbl = 'temp_data';

if ($q == 3) //3
{
	$query = "SELECT temp1, (UNIX_TIMESTAMP(timestamp)*1000) FROM temp_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'temp1';
	
	//$response = array('status' => 0, 'numsamples' => "Testing");
	//echo json_encode($response);
	//exit(0);
}
else if ($q == 2)  //4
{
	$query = "SELECT temp2, (UNIX_TIMESTAMP(timestamp)*1000) FROM temp_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'temp2';
}
else if ($q == 0) //2
{
	$query = "SELECT temp3, (UNIX_TIMESTAMP(timestamp)*1000) FROM temp_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'temp3';
}
else if ($q == 1) //1
{
	$query = "SELECT temp4, (UNIX_TIMESTAMP(timestamp)*1000) FROM temp_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'temp4';
}
else if ($q == 7) //7
{
	$query = "SELECT temp5, (UNIX_TIMESTAMP(timestamp)*1000) FROM temp_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'temp5';
}
else if ($q == 6) //8
{
	$query = "SELECT temp6, (UNIX_TIMESTAMP(timestamp)*1000) FROM temp_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'temp6';
}
else if ($q == 4) //6
{
	$query = "SELECT temp7, (UNIX_TIMESTAMP(timestamp)*1000) FROM temp_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'temp7';
}
else if ($q == 5) //5
{
	$query = "SELECT temp8, (UNIX_TIMESTAMP(timestamp)*1000) FROM temp_data WHERE timestamp > NOW() - INTERVAL 2 MINUTE ORDER BY timestamp DESC LIMIT " . $num_samples;
	$col_name = 'temp8';
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
		$xdata[$i] = $row['(UNIX_TIMESTAMP(timestamp)*1000)'];
		$i = $i + 1;
	}
	
	//$usq = "UPDATE " . $tbl . " SET status=1 WHERE device='" . $fp . "' AND status=0";
	//$usr = mysqli_query($conn, $usq);
	
	/*
	$xvdata = array_values($xdata);
	$xvdata = array_map('floatval', $xvdata);
	$yvdata = array_values($ydata);
	$yvdata = array_map('floatval', $yvdata);
	*/
	
	$xvdata = floatval($xdata[0]);
	$yvdata = floatval($ydata[0]);
	
	$response = array('status' => 1, 'numsamples' => $num_data, 'xdata' => $xvdata, 'ydata' => $yvdata);
	echo json_encode($response);
	exit(0);
}

?>
