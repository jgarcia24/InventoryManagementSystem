<?php

//connect to database
$dblink=db_iconnect("equipment");

//request inputs
$serial_number = $_REQUEST['serialnumber'];

//validate input
if ($serial_number==NULL)
{
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device Serial Number must not be blank.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
else
{
	//check if device is in the DB first using select
	$sql="Select * from `devices` where `serial_number`='$serial_number'";
	$result=$dblink->query($sql) or
		die("Something went wrong with $sql");
	$device=$result->fetch_array(MYSQLI_ASSOC);
	//delete found device
	if ($result->num_rows>0)
	{
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		$output[]="Status: OK";
		$output[]="MSG: Device $serial_number successfully deleted from database";
		$data[]='Manufacturer: '.$device['manufacturer'];
		$data[]='Device Type: '.$device['device_type'];
		$data[]='Serial Number: '.$device['serial_number'];
		$data[]='Status: '.$device['status'];
		$output[]=$data;
		$sql = "delete from `devices` where `serial_number` = '$serial_number'";
		$dblink->query($sql) or
			die("Something went wrong with $sql");
		$responseData=json_encode($output);
		echo $responseData;
	}
	else //device not found
	{
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		$output[]="Status: Not Found";
		$output[]="MSG: Device Serial Number: $serial_number not in database";
		$data[]="";
		$output[]=$data;
		$responseData=json_encode($output);
		echo $responseData;
	}
}

?>