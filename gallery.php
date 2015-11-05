<?php
session_start();
$email = $_POST["email"];
echo $email;
require 'vendor/autoload.php';

use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
'region'  => 'us-east-1'
));

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'mh-db',
));

$endpoint = "";

foreach ($result->getPath('DBInstances/*/Endpoint/Address') as $ep) {
    // Do something with the message
    echo "============". $ep . "================";
    $endpoint = $ep;
}   
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
echo "Result set order...\n";
}
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
	<style>
		#delete{
			float:right;
			
		}
		h3{
			font-style: italic;
		}
	
	</style>
	</head>

<body>
<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation" ><a href="#">Home</a></li>
            </ul>
        </nav>
    </div>

    <a id="delete" class="btn btn-danger" href="../delete.php/?id=<?php echo $a->getId(); ?>" role="button">Delete</a>
    <div class="jumbotron">
        <h2> <?php echo $subject?> </h2>
        <h3> by <?php echo $author?> </h3>
        <p class="lead"><?php while ($row = $res->fetch_assoc()) {
    echo "<img src =\" " . $row['s3url'] . "\" /><img src =\"" .$row['fs3url'] . "\"/>";
echo $row['id'] . "Email: " . $row['email'];
?></p>
    </div>
    <a class="btn btn-lg btn-success" href="../edit.php/?id=<?php echo $a->getId(); ?>" role="button">Edit</a>



</div>
</body>

</html>
