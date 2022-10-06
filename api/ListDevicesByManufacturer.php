<?php

//connect to database
$dblink=db_iconnect("equipment");

//request inputs
$manufacturer = $_REQUEST['manufacturer'];

//validate inputs
if($manufacturer == NULL) {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device Manufacturer must not be blank.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
else {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: OK";
	$output[]="MSG: ";
	$data = array();
	$data_arr = array();
	$sql = "Select * from `devices` where `manufacturer` = '$manufacturer' order by `serial_number` limit 10";
	$result = $dblink->query($sql) or
		die ("Something went wrong with $sql");
	while($device=$result->fetch_array(MYSQLI_ASSOC))
	{
		$data_arr[] = array(
		$data[]='Device ID: '.$device['auto_id'],
		$data[]='Device Type: '.$device['device_type'],
		$data[]='Device Manufacturer: '.$device['manufacturer'],
		$data[]='Serial Number: '.$device['serial_number'],
		$data[]='Status: '.$device['status']
		);
	}
	$output[]=$data_arr;
	$responseData=json_encode($output);
	echo $responseData;
}

?>