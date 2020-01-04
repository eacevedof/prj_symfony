<?php

echo "testpdons.php";
$dsn="mysql:host=172.30.0.2;port=3306;charset=utf8;";
$user="root";
$password="1234";
$options=[];    
$ocon = new \Doctrine\DBAL\Driver\PDOConnection($dsn,$user,$password,$options);
var_dump($ocon);
die("testpdo.ph");