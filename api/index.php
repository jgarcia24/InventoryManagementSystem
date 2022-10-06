<?php
include("functions.php");
$uri=parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY);
$uri=explode('&',$uri);
$endPoint=$uri[0];
//die("End Point: $endPoint");
switch ($endPoint){
	case "ViewDevice":
		include("ViewDevice.php");
		break;
	case "ListDevicesByType":
		include("ListDevicesByType.php");
		break;
	case "ListDevicesByManufacturer":
		include("ListDevicesByManufacturer.php");
		break;
	case "ListDevicesBySerialNumber":
		include("ListDevicesBySerialNumber.php");
		break;
	case "ListDevicesTypeAndManufacturer":
		include("ListDevicesTypeAndManufacturer.php");
		break;
	case "ListDevicesByStatus":
		include("ListDevicesByStatus.php");
		break;
	case "UploadFileDB":
		include("UploadFileDB.php");
		break;
	case "UploadFileFS":
		include("UploadFileFS.php");
		break;
	case "ViewFileDB":
		include("ViewFileDB.php");
		break;
	case "ViewFileFS":
		include("ViewFileFS.php");
		break;
	case "UpdateDeviceType":
		include("UpdateDeviceType.php");
		break;
	case "UpdateDeviceManufacturer":
		include("UpdateDeviceManufacturer.php");
		break;
	case "UpdateDeviceSerialNumber":
		include("UpdateDeviceSerialNumber.php");
		break;
	case "UpdateDeviceStatus":
		include("UpdateDeviceStatus.php");
		break;
	case "DeleteDevice":
		include("DeleteDevice.php");
		break;
	case "AddDevice":
		include("AddDevice.php");
		break;
	default:
		header('Content-Type: application/json');
		header("HTTP/1.1 404 Not Found");
		$message[]="Status: Error";
		$message[]="MSG: Endpoint not found";
		$message[]="";
		echo json_encode($message);
		die();
}

//3 consistent variables
//STATUS
//MESSAGE
//OUTPUT
?>

