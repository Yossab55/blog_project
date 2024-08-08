<?php 

$dsn = "mysql:host=localhost;dbname=projects";
$user = 'root';

try {
  $database = new PDO($dsn, $user ); 
} catch(PDOException $e) {
  echo "you have an error in the data base " ;
}