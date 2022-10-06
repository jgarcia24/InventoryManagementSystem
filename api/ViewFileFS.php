<?php

//connect to database
$dblink=db_iconnect("equipment");

//request inputs
$did=$_REQUEST['did'];
$requested_file=$_REQUEST['myFile'];
//$data = json_decode(file_get_contents("php://input"), true); // collect input parameters and convert into readable format

//validate inputs
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
elseif ($did==NULL) //$did is empty
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
else //check if device exists
{
	$sql="Select * from `devices` where `auto_id`='$did'";
	$result=$dblink->query($sql) or
		die("Something went wrong with $sql");
	$device=$result->fetch_array(MYSQLI_ASSOC);
	if ($result->num_rows>0) //device found
	{
		//get file info for json output
		$sql="Select * from `files_link` where `file_name` = '$requested_file'";
		$result=$dblink->query($sql) or
			die("Something went wrong with $sql");
		$retrieved_file=$result->fetch_array(MYSQLI_ASSOC);
		if ($result->num_rows>0)
		{
			//output json
			header('Content-Type: application/json');
			header('HTTP/1.1 200 OK');
			
			$output[]="Status: OK";
			$output[]="MSG: File successfully retrieved from file system for Device ID: $did";
			
			$data[]='File Name: '.$retrieved_file['file_name'];
			$data[]='File Type: '.$retrieved_file['file_type'];
			$data[]='File Size: '.$retrieved_file['file_size'];
			$data[]='File Location: '.$retrieved_file['location'];
			$data[]='File Device: '.$retrieved_file['device'];
			
			//$file = $retrieved_file["location"].$retrieved_file["file_name"];
			//header('Content-type: application/pdf');
			//header('Content-Disposition: inline; filename="' .$file. '"'); 
			//header('Content-Transfer-Encoding: binary'); 
			//header('Accept-Ranges: bytes');
			
			//readfile($file);
					
			$output[]=$data;
			$responseData=json_encode($output);
			echo $responseData;
		}
		else
		{ //file not found
			header('Content-Type: application/json');
			header('HTTP/1.1 200 OK');
			$output[]="Status: Not Found";
			$output[]="MSG: File not in database";
			$data[]="";
			$output[]=$data;
			$responseData=json_encode($output);
			echo $responseData;
			die();
		}
	}
	else //device not found
	{
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
		$output[]="Status: Not Found";
		$output[]="MSG: Device ID: $did not in database";
		$data[]="";
		$output[]=$data;
		$responseData=json_encode($output);
		echo $responseData;
	}
}

?>