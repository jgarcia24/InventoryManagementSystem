<?php

//connect to database
$dblink=db_iconnect("equipment");

//request inputs
$did=$_REQUEST['did'];
$data = json_decode(file_get_contents("php://input"), true); // collect input parameters and convert into readable format

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
		//check file size
		if($_FILES["userfile"]["size"] > 0) {
			//get file information
			$fileName = $_FILES["userfile"]["name"];
			$tmpName = $_FILES["userfile"]["tmp_name"];
			$fileSize = $_FILES["userfile"]["size"];
			$fileType = $_FILES["userfile"]["type"];
			$fp = fopen($tmpName, "r");
			$content = fread($fp, filesize($tmpName));
			$content = addslashes($content);
			fclose($fp);
			//insert into DB
			$sql = "Insert into `files` (`file_name`, `file_type`, `file_size`, `content`, `device`) values";
			$sql.= "('$fileName', '$fileType', '$fileSize', '$content', '$did')";
			$dblink->query($sql) or
				die("Something went wrong with $sql");
			//get file info for json output
			$sql="Select * from `files` where `device` = '$did'";
			$result=$dblink->query($sql) or
				die("Something went wrong with $sql");
			$uploaded_file=$result->fetch_array(MYSQLI_ASSOC);
			if ($result->num_rows>0) {
				//output json
				header('Content-Type: application/json');
				header('HTTP/1.1 200 OK');
				$output[]="Status: OK";
				$output[]="MSG: File successfully uploaded to database for Device ID: $did";
				$data[]='File Name: '.$uploaded_file['file_name'];
				$data[]='File Type: '.$uploaded_file['file_type'];
				$data[]='File Size: '.$uploaded_file['file_size'];
				$data[]='File Device: '.$uploaded_file['device'];
				$output[]=$data;
				$responseData=json_encode($output);
				echo $responseData;
			}
			else { //file not found
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
		else {
				header('Content-Type: application/json');
				header('HTTP/1.1 200 OK');
				$output[]="Status: Invalid File";
				$output[]="MSG: Empty or Null File";
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
		$output[]="MSG: Device Id: $did not in database";
		$data[]="";
		$output[]=$data;
		$responseData=json_encode($output);
		echo $responseData;
	}
}

?>