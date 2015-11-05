<?php
session_start();
$email = $_POST["email"];
//echo $email;
require 'vendor/autoload.php';

$client = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);


$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'mh-db',
));

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
//    echo "============". $endpoint . "================";

//echo "begin database";
$link = mysqli_connect($endpoint,"controller","letmein888","mhana1DB") or die("Error " . mysqli_error($link));

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//below line is unsafe - $email is not checked for SQL injection -- don't do this in real life or use an ORM instead
$link->real_query("SELECT * FROM users WHERE email = '$email'");

$res = $link->use_result();
//echo "Result set order...\n";

$link->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/jumbotron-narrow.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation" ><a href="index.php">Home</a></li>
            </ul>
        </nav>
    </div>

    <div class="jumbotron">
        <h2> Images </h2>
        <p class="lead"><?php while ($row = $res->fetch_assoc()) {
    echo "<img src =\" " . $row['s3url'] . "\" /><img src =\"" .$row['fs3url'] . "\"/>";
echo $row['id'] . "Email: " . $row['email'];}
?></p>
    </div>


</div>
</body>

</html>
