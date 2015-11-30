<?php
session_start();

$found = false;
$email = $_POST["email"];
if (empty($_POST["email"])){
    $email = $_SESSION["email"];
    if(!empty($email)){
        $found = true;
}
}
else $found = true;


require 'vendor/autoload.php';

$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);


$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mh-db',
]);

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
//    echo "============". $endpoint . "================";

//echo "begin database";
$link = mysqli_connect($endpoint,"controller","letmein888","mhana1DB") or die("Error " . mysqli_error($link));

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if($found != true){
        $sql = "SELECT * FROM users";
}
else{
        $sql = "SELECT * FROM users WHERE email = '$email'";
}
//below line is unsafe - $email is not checked for SQL injection -- don't do this in real life or use an ORM instead
$link->real_query($sql);

//$res = $link->use_result();
//echo "Result set order...\n";

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

        <h1>Gallery of Images</h1>
        <h2>For user with email:</h2>
        <p class="lead"><?php echo $email; ?></p>
    </div>
        <?php
        
 if ($result = $link->use_result()) {
    $row = $result->fetch_assoc() ;
        if(empty($row)){
                echo "<font color='red'><h3><b>No records found for this user</h3>";
        }
        while ($row) {
                if($found != false){
                echo "<img src =\" " . $row['s3url'] . "\"  height='400' width='400'/><img src=\"".$row['fs3url']."\"/>";


            }

        if ($found ==false){
         echo "<img src =\" " . $row['s3url'] . "\" height='400' width='400'/>";
        }
$row = $result->fetch_assoc() ;
}} 

            $result->close();
       
        session_destroy();
        ?>
</div>

</body>
</html>
