<?php
include('db.php');
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <link rel="icon" href="/ressources/favicon.png" type="image/ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Flaticon/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand print">
                <img src="ressources/logob.png" height="120" alt="Logo" style="padding-left:30px;">
                
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse" id="n">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav">
                </div>
                <div class="navbar-nav ms-auto">
                
                <a href="index.php" style="padding-left:5.5vw; display:flex; align-items:center" class="nav-item nav-link"> <span class="fi-rr-home" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Accueil</a>
                <a href="who.php" style="padding-left:5.5vw;display:flex; align-items:center" class="nav-item nav-link"> <span class="fi-rr-world" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Qui sommes nous?</a>
                <a href="metiers.php" style="padding-left:5.5vw;display:flex; align-items:center" class="nav-item nav-link"> <span class="fi-rr-edit" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Nos métiers</a>
                <a href="ref.php" style="padding-left:5.5vw;display:flex; align-items:center" class="nav-item nav-link"> <span class="fi-rr-search-alt" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Références</a>
                <a href="part.php" style="padding-left:5.5vw;display:flex; align-items:center" class="nav-item nav-link"> <span class="fi-rr-comment-user" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Partenaires</a>
                <a href="contact.php" style="padding-left:5.5vw;display:flex; align-items:center" class="nav-item nav-link"> <span class="fi-rr-phone-call" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Contact</a>
                        
                </div>
            </div>
        </div>
    </nav>
</header>

</html>

