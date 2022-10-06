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

	echo '<p>Delete Device</p>';
	echo '<hr>';
	
	echo '
	<form method="post" action="">
		  <label for="serial-number">Serial Number:</label><br>
		  <input type="text" id="serial-number" name="serial-number"><br>
		  <div><button type="submit" name="delete-device" value="delete-device">Delete</button></div>;
	</form>';
}

if(isset($_POST["delete-device"]) && $_POST["delete-device"] == "delete-device") {

	$serial_number = $_POST["serial-number"];
	
	$sql = "delete from `devices` where `serial_number` = '$serial_number'";
	$dblink->query($sql) or
		die("Something went wrong with $sql");
	redirect("https://ec2-18-224-246-6.us-east-2.compute.amazonaws.com");
}

	
?>