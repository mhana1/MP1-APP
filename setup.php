<?php
require 'vendor/autoload.php'; 
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);



$result = $rds->waitUntil('DBInstanceAvailable',['DBInstanceIdentifier' => 'mh-db',]);

$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mh-db',
]);

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
subscribed TINYINT(2),
date VARCHAR(256)
)";
$link->query($sql);

$sns = new Aws\Sns\SnsClient([
'version' => 'latest',
'region' => 'us-east-1'
]);

$result = $sns->createTopic([
'Name' => 'SNS-MP2',
]);

#echo $result;

$topicAttributes = $sns->setTopicAttributes([
'AttributeName'=>'DisplayName',
'AttributeValue'=>'MP2-SNS-Display',
'TopicArn'=>$result['TopicArn']
]);

header("Location: index.php");
?>
