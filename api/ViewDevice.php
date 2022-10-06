<?php
$dblink=db_iconnect("equipment");
$did=$_REQUEST['did'];
if (!is_numeric($did) && $did!=NULL)
{
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device ID must be numbers only.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
elseif ($did==NULL)
{
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device ID must not be blank.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
else
{
	$sql="Select * from `devices` where `auto_id`='$did'";
	$result=$dblink->query($sql) or
		die("Something went wrong with $sql");
	$device=$result->fetch_array(MYSQLI_ASSOC);
	if ($result->num_rows>0)
	{
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		$output[]="Status: OK";
		$output[]="MSG: ";
		$data[]='Manufacturer: '.$device['manufacturer'];
		$data[]='Device Type: '.$device['device_type'];
		$data[]='Serial Number: '.$device['serial_number'];
		$data[]='Status: '.$device['status'];
		$output[]=$data;
		$responseData=json_encode($output);
		echo $responseData;
	}
	else
	{
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		$output[]="Status: Not Found";
		$output[]="MSG: Device Id: $did not in database";
		$data[]="";
		$output[]=$data;
		$responseData=json_encode($output);
		echo $responseData;
	}
}
?>