<?php

$dsn = "mysql:host=localhost;dbname=Dashboard";
$dbusername ="root";
$dbpassword = "";

try{
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(error $e){
    $message = $e->getMessage();
    echo "<h1>Database connection Failed" . $message . "</h1>";

}