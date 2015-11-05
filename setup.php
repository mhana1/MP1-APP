<?php
// Start the session 
require 'vendor/autoload.php';
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
print "Create RDS DB results: \n";
# print_r($rds);
$result = $rds->waitUntil('DBInstanceAvailable',['DBInstanceIdentifier' => 'mh-db',
]);
// Create a table 
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mh-db',
]);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print "============". $endpoint . "================\n";
$link = mysqli_connect($endpoint,"controller","letmein888","mhana1DB",3306) or die("Error " . mysqli_error($link)); 
echo "Here is the result: " . $link;
$sql = "CREATE TABLE users IF NOT EXISTS(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
uname VARCHAR(20),
email VARCHAR(20),
phone VARCHAR(20),
s3url VARCHAR(256),
fs3url VARCHAR(256),
filename VARCHAR(256),
state TINYINT(3),
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$con->query($sql);
shell-exec("chmod 600 setup.php");
?>
