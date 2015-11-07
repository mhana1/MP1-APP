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
	
<?php
require 'vendor/autoload.php'; 
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mh-db',
]);


if ($result['DBInstances'][0]['DBInstanceStatus'] != "available"){
echo '<div class="jumbotron">';
    echo ' <h2> Wait till the Database is created... </h2></div>';

}

$rds->waitUntil('DBInstanceAvailable',['DBInstanceIdentifier' => 'mh-db',]);


$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

$link = mysqli_connect($endpoint,"controller","letmein888","mhana1DB") or die("Error " . mysqli_error($link)); 

$sql = "CREATE TABLE users(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
uname VARCHAR(50),
email VARCHAR(50),
phone VARCHAR(50),
s3url VARCHAR(256),
fs3url VARCHAR(256),
filename VARCHAR(256),
state TINYINT(3),
date VARCHAR(256)
)";
$link->query($sql);

header("Location: index.php");
?>
