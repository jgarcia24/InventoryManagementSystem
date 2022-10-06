<?php
//connect to database
$dblink=db_iconnect("equipment");

//request inputs
$device_type = $_REQUEST['type'];
$manufacturer = $_REQUEST['manufacturer'];
$serial_number = $_REQUEST['serialnumber'];
$status = $_REQUEST["status"];
$status_answers = array(0, 1);

//validate input (checks if empty for now)
if($device_type == NULL) {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device Type must not be blank.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
elseif ($manufacturer == NULL) {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device Manufacturer must not be blank.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
elseif ($serial_number == NULL) {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device Serial Number must not be blank.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
elseif ($status == NULL) {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device Status must not be blank.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
elseif(in_array($status, $status_answers) == false) {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device Status must be 0 or 1.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
else {
	//insert device into database
	$sql = "insert into `devices` (`device_type`, `manufacturer`, `serial_number`, `status`) values";
	$sql.="('$device_type', '$manufacturer', '$serial_number', '$status')";
	$dblink->query($sql) or
		die("Something went wrong with $sql");
	//echo added device by selecting it from the database
	$sql="Select * from `devices` where `serial_number`='$serial_number'";
	$result=$dblink->query($sql) or
		die("Something went wrong with $sql");
	$device=$result->fetch_array(MYSQLI_ASSOC);
	//check if device was successfully added to database
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
		$output[]="MSG: Device Serial Number: $serial_number not in database";
		$data[]="";
		$output[]=$data;
		$responseData=json_encode($output);
		echo $responseData;
	}
}

?>