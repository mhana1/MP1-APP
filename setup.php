<?php
// Start the session 
require 'vendor/autoload.php';

$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

#create db instance if not exist
mapfile -t dbInstanceARR < <(aws rds describe-db-instances --output json | grep "\"DBInstanceIdentifier" | sed "s/[\"\:\, ]//g" | sed "s/DBInstanceIdentifier//g" )

if [ ${#dbInstanceARR[@]} -gt 0 ]
   then
   LENGTH=${#dbInstanceARR[@]}

       for (( i=0; i<${LENGTH}; i++));
       do
        if [ ${dbInstanceARR[i]} == "mh-db"]
        then
         echo "Database already exists"
        else
        echo "creating new database"

result = $rds->createDBInstance([
    'AllocatedStorage' => 10,
    'DBInstanceClass' => 'db.t1.micro',
    'DBInstanceIdentifier' => 'mh-db',
    'DBName' => 'users',
    'Engine' => 'MySQL',
    'EngineVersion' => '5.5.41',
    'MasterUserPassword' => 'letmein888',
    'MasterUsername' => 'controller',
    'PubliclyAccessible' => true,
]);

        fi
   done
fi

print "Create RDS DB results: \n";
# print_r($rds);

$result = $rds->waitUntil('DBInstanceAvailable',['DBInstanceIdentifier' => 'mh-db',
]);
// Create a table 
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mh-db',
]);

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print "============\n". $endpoint . "================\n";

$link = mysqli_connect($endpoint,"controller","letmein888","3306") or die("Error " . mysqli_error($link)); 
echo "Here is the result: " . $link;
$sql = "CREATE TABLE data 
(
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
?>
