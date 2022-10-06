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

$did = $_REQUEST['did'];

//view device info
$sql = "Select * from `devices` where `auto_id` = '$did'";
$result = $dblink->query($sql) or
	die("Something went wrong with $sql");
$device = $result->fetch_array(MYSQLI_ASSOC);

echo '
<div id="page-inner">
	<div class="panel panel-primary">
		<div class="panel-heading">Device Info</div>
		<div class="panel-body">
			<p>Maufacturer: '.$device['manufacturer'].'</p>
			<p>Device Type: '.$device['device_type'].'</p>
			<p>Serial Number: '.$device['serial_number'].' </p>';

//view files DB
$sql = "Select * from `files` where `device`='$device[auto_id]'";
$result = $dblink->query($sql) or
	die("Something went wrong with $sql");
if($result->num_rows > 0) {
	echo '<p>Device Record Files Found: </p>';
	while($data=$result->fetch_array(MYSQLI_ASSOC)) {
		$name = str_replace(" ", "_", $data["file_name"]);
		$fp = fopen("/var/www/html/files/$name", "wb");
		fwrite($fp, $data["content"]);
		fclose($fp);
		echo '<div><a class="btn btn-sm btn-primary" href="./files/'.$name.'" target="_blank">View Record DB</a></div>';
	}
}

//view files FS
$sql = "Select * from `files_link` where `device`='$device[auto_id]'";
$result = $dblink->query($sql) or
	die("Something went wrong with $sql");
if($result->num_rows > 0) {
	echo '<p>Device Record Files Found: </p>';
	while($data=$result->fetch_array(MYSQLI_ASSOC)) {
		echo '<p><a class="btn btn-sm btn-primary" href="./files/'.$data['file_name'].'" target="_blank">View Record FS</a></p>';
	}
}

echo '
		</div>
	</div>';

echo '
	<div class="panel panel-primary">
		<div class="panel-heading">Upload File to Database</div>
		<div class="panel-body">
			<form role="form" method="post" enctype="multipart/form-data" action="">
				<input type="hidden" name="MAX_FILE_SIZE" value="50000000">
				<input type="hidden" name="did" value="'.$did.'">
				<div class="form-group">
					<label class="control-label col-lg-4">File Upload</label>
					<div class="">
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>
							<div class="row">
								<div class="col-md-2"><span class="btn btn-file btn-primary"><span class="fileupload-new">Select File</span><span class="fileupload-exists">Change</span>
									<input name="userfile" type="file">
									</span>
								</div>
								<div class="col-md-2"><a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a></div>
							</div>
						</div>
					</div>
					<hr>
					<div class="col-md-2">
						<button class="btn btn-success" name="UploadAppDoc" type="submit" value="UploadAppDoc" />Upload</button>
					</div>
					<div class="col-md-2"><a class="btn btn-danger" href="">Cancel</a></div>
			</form>
			</div>
		</div>
	</div>';

//upload file DB
if(isset($_POST["UploadAppDoc"]) && $_FILES["userfile"]["size"] > 0) {
	$start_time = microtime(true);
	$did = $_POST["did"];
	$fileName = $_FILES["userfile"]["name"];
	$tmpName = $_FILES["userfile"]["tmp_name"];
	$fileSize = $_FILES["userfile"]["size"];
	$fileType = $_FILES["userfile"]["type"];
	$fp = fopen($tmpName, "r");
	$content = fread($fp, filesize($tmpName));
	$content = addslashes($content);
	fclose($fp);
	$sql = "Insert into `files` (`file_name`, `file_type`, `file_size`, `content`, `device`) values";
	$sql.= "('$fileName', '$fileType', '$fileSize', '$content', '$did')";
	$dblink->query($sql) or
		die("Something went wrong with $sql");
	$end_time = microtime(true);
	$execution_time = ($end_time - $start_time);
	redirect("https://ec2-18-224-246-6.us-east-2.compute.amazonaws.com/view_device.php?did=$did&execTime=$execution_time");
	
}

//upload file FS
if(isset($_POST["UploadFileSys"]) && $_FILES["userfile2"]["size"] > 0) {
	$start_time = microtime(true);
	$uploadDir = "/var/www/html/files";
	$did = $_POST["did"];
	$fileName = $_FILES["userfile2"]["name"];
	$tmpName = $_FILES["userfile2"]["tmp_name"];
	$fileSize = $_FILES["userfile2"]["size"];
	$fileType = $_FILES["userfile2"]["type"];
	$location = "$uploadDir/$fileName";
	move_uploaded_file($tmpName, $location);
	$sql = "Insert into `files_link` (`file_name`, `file_type`, `file_size`, `location`, `device`) values";
	$sql.= "('$fileName', '$fileType', '$fileSize', '$location', '$did')";
	$dblink->query($sql) or
		die("Something went wrong with $sql");
	$end_time = microtime(true);
	$execution_time = ($end_time - $start_time);
	redirect("https://ec2-18-224-246-6.us-east-2.compute.amazonaws.com/view_device.php?did=$did&execTime=$execution_time");
	
}


echo '
	<div class="panel panel-primary">
		<div class="panel-heading">Upload File to Filesystem</div>
		<div class="panel-body">
			<form role="form" method="post" enctype="multipart/form-data" action="">
				<input type="hidden" name="MAX_FILE_SIZE" value="50000000">
				<input type="hidden" name="did" value="'.$did.'">
				<div class="form-group">
					<label class="control-label col-lg-4">File Upload</label>
					<div class="">
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>
							<div class="row">
								<div class="col-md-2"><span class="btn btn-file btn-primary"><span class="fileupload-new">Select File</span><span class="fileupload-exists">Change</span>
									<input name="userfile2" type="file">
									</span>
								</div>
								<div class="col-md-2"><a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a></div>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-md-2">
					<button class="btn btn-success" name="UploadFileSys" type="submit" value="UploadFileSys" />Upload</button>
				</div>
				<div class="col-md-2"><a class="btn btn-danger" href="">Cancel</a></div>
			</form>
		</div>
	</div>
</div>
</div>';


	
?>