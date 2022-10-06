<?php
//connect to database
$dblink=db_iconnect("equipment");

//request inputs
$did = $_REQUEST['did'];
$new_status = $_REQUEST['newStatus'];
$status_answers = array(0, 1);

//validate input (checks if empty for now)
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
elseif ($new_status == NULL) {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device Status must not be blank.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
elseif(in_array($new_status, $status_answers) == false) {
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: Invalid Data";
	$output[]="MSG: Device Status must be 0 or 1.";
	$output[]="";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
else
{
	//get current device info
	$sql="Select * from `devices` where `auto_id`='$did'";
	$result=$dblink->query($sql) or
		die("Something went wrong with $sql");
	$device=$result->fetch_array(MYSQLI_ASSOC);
	if ($result->num_rows>0) //if exists, update device
	{
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		$output[]="Status: OK";
		$output[]="MSG: ";
		//update device
		$sql = "Update `devices` set `status` = '$new_status' where `auto_id` = '$did'";
		$result=$dblink->query($sql) or
			die("Something went wrong with $sql");
		
		//grab device for display
		$sql="Select * from `devices` where `auto_id`='$did'";
		$result=$dblink->query($sql) or
			die("Something went wrong with $sql");
		$updated_device=$result->fetch_array(MYSQLI_ASSOC);
		
		$data[]='Manufacturer: '.$updated_device['manufacturer'];
		$data[]='Device Type: '.$updated_device['device_type'];
		$data[]='Serial Number: '.$updated_device['serial_number'];
		$data[]='Status: '.$updated_device['status'];
		
		$output[]=$data;
		$responseData=json_encode($output);
		echo $responseData;
	}
	else //device not found
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