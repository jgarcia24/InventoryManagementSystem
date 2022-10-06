<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<?php

function redirect($uri)
{
	?>
	<script type="text/javascript">
		<!--
		document.location.href="<?php echo $uri; ?>";
		-->
	</script>
	<?php die;
}

$username = "web user";
$password = "JIZ2VsmC4FfuqwUt";
$db = "equipment";
$hostname = "localhost";
$dblink = new mysqli($hostname, $username, $password, $db);

if(!isset($_POST["submit"])) {

	echo '<p>Update Device</p>';
	echo '<hr>';
	
	echo '
	<form method="post" action="">
		  <label for="serial-number">Enter Serial Number of Device to be Updated:</label><br>
		  <input type="text" id="serial-number" name="serial-number"><br>
		  <div><button type="submit" name="get-sn" value="get-sn">Submit</button></div>;
	</form>';
}

if(isset($_POST["get-sn"]) && $_POST["get-sn"] == "get-sn") {

	$serial_number = $_POST["serial-number"];
	$sql = "select * from `devices` where `serial_number` = '$serial_number'";
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql");
	
	echo '<p>Enter New Values for the Device</p>';
	echo '<hr>';
	
	echo '
	<form method="post" action="">
		  <label for="device-type">Device Type:</label><br>
		  <input type="text" id="device-type" name="device-type"><br>
		  <label for="device-manufacturer">Manufacturer:</label><br>
		  <input type="text" id="device-manufacturer" name="device-manufacturer"><br>
		  <label for="serial-number">Serial Number:</label><br>
		  <input type="text" id="serial-number" name="serial-number"><br>
		  <div><button type="submit" name="update-device" value="update-device">Update</button></div>;
	</form>';
}

	
?>