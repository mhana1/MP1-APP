<?php
$uname = $email = $phone = $file ="";
if(isset($_GET['error'])){
	$error = $_GET['error'];
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles  -->
	<link href="css/jumbotron-narrow.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="header clearfix">
      <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" ><a "#">Home</a></li>
		  </ul>
	  </nav>
	</div>
	
<div class="container">
    
    
        <?php
        if(isset($_GET['error'])){
        print "<font color='red' size='2'>".$error."</font><br><br>";
        }
        require 'vendor/autoload.php';
        $rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mh-db',
]);

//echo $result['DBInstances'][0]['DBInstanceStatus'];
if ($result['DBInstances'][0]['DBInstanceStatus'] != "available"){
echo '<div class="jumbotron">';
    echo ' <h2> Wait till the Database is created... </h2></div>';

}

$result = $rds->waitUntil('DBInstanceAvailable',['DBInstanceIdentifier' => 'mh-db',]);

 ?>
<form enctype="multipart/form-data" action="submit.php" method="POST">    
    
	<label >User Name:</label>
	<input class="form-control" type="text" name="uname" value="<?php echo isset($_POST['uname']) ? $_POST['uname'] : '' ?>"></input><br><br>
	<label >Email Address:</label>
	<input class="form-control" type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>"></input><br><br>
	<label >Phone (1-XXX-XXX-XXXX):</label>
	<input class="form-control" type="phone" name="phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : '' ?>"></input><br><br>
	<label >File to send:</label>
	<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
	<input  type="file" name="file" value="<?php echo isset($_POST['file']) ? $_POST['file'] : '' ?>"></input><br><br>
	<button type="submit" class="btn btn-default">Send File</button>
	<input type="hidden" name="submit"/><br><br><br><br>
</form>

<footer class='footer'>
<form enctype="multipart/form-data" action="gallery.php" method="POST">
    
Enter Email of user for gallery to browse: <input type="email" name="email">
<input type="submit" value="Load Gallery" />
</form>
</footer>

</div>
</body>
</html>
