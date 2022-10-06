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

if(!isset($_POST["submit"]))
{
	$sql = "Select * from `unique_devices`";
	$result = $dblink->query($sql) or
		die("Something went wrong with $sql");
	$devices = array();
	while($data = $result->fetch_array(MYSQLI_ASSOC)) {
		$devices[$data['device_id']] = $data['device_type'];
	}
	$devStr = implode(",", $devices);

	echo '<form method="post" action="">';
	echo '<input type="hidden" name="devices" value="'.$devStr.'">';
	echo '<p>Please select a device type to query: </p>';
	echo '<div><select name="device">';
	foreach($devices as $key=>$value)
	{
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
	echo '</select></div>';
	echo '<hr>';
	echo '<div><button type="submit" name="submit" value="lookUp">Submit</button></div>';
	echo '</form>';
	
	echo '<form method="post" action="">';
	echo '<div><button type="submit" name="add-device" value="add-device">Add Device</button></div>';
	echo '</form>';
	
	echo '<form method="post" action="">';
	echo '<div><button type="submit" name="update-device" value="update-device">Update Device</button></div>';
	echo '</form>';
	
	echo '<form method="post" action="">';
	echo '<div><button type="submit" name="delete-device" value="delete-device">Delete Device</button></div>';
	echo '</form>';

}

if(isset($_POST["add-device"]) && $_POST["add-device"] == "add-device") {
	redirect("https://ec2-18-224-246-6.us-east-2.compute.amazonaws.com/add_device.php");
}

if(isset($_POST["delete-device"]) && $_POST["delete-device"] == "delete-device") {
	redirect("https://ec2-18-224-246-6.us-east-2.compute.amazonaws.com/delete_device.php");
}

if(isset($_POST["submit"]) && $_POST["submit"] == "lookUp") 
{
	$device = $_POST["device"];
	$tmp = $_POST['devices'];
	$devices = explode(",", $tmp);

	echo '<p>Device chosen is: '. $device. '</p>';
	echo '<p>These are the results: </p>';
	echo '<div class="panel-body">';
	echo '<table id="invDetails" class="display" cellspacing="0" width="100%">;
			<thead>
				<tr>
					<th>Manufacturer</th>
					<th>Serial Number</th>
					<th>Action</th>
				</tr>
			</thead>';

	$sql = "Select `auto_id`, `manufacturer`, `serial_number` from `devices` where `device_type` = '$device' order by `serial_number` limit 1000";
	$result = $dblink->query($sql) or
		die ("Something went wrong with $sql");
	$count = $result->num_rows;

	echo '<tbody>';
	while($data=$result->fetch_array(MYSQLI_ASSOC))
	{
		echo '<tr>';
			echo '<td>'.$data["manufacturer"].'</td>';
			echo '<td>'.$data["serial_number"].'</td>';
			echo '<td><a href="view_device.php?did='.$data["auto_id"].'">More Info</a></td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	echo '</div>';

}

?>
