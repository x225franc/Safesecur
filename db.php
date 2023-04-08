<?php
//Connexion aux bases de données
$user = "root";
$pass = "";
$host = "localhost";
$db = "safesecur";
// $db = "diabate1_safesecur";

try{
    $cnx = new PDO('mysql:host='.$host.';dbname='.$db.'' , $user , $pass);
}catch(exception $e){
    die("Echec de connexion a la base de données".$e->getMessage());
}
