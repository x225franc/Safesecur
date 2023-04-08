<?php
include('db.php');
@session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="ressources\favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Flaticon/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.1.3/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">


</head>

<style>
    @media (max-width: 1199px) {
        .navbar-nav {
            margin-left: auto !important;
        }
    }

    @media (max-width: 992px) {
        .navbar-nav {
            flex-direction: column;
        }

        .nav-item {
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-bottom: 10px;
            text-align: center;
        }
    }
</style>

<header>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a href="index.php" class="navbar-brand print" style="font-style:italic;font-weight:bold;color:darkgray;font-size:20px">
            <img src="ressources/logob.png" height="120" alt="Logo" style="padding-left:30px;">
            &nbsp;SAFESECUR
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active" style="margin-right: 10px;">
                    <a href="index.php" style="align-items:center;display:flex" class="nav-item nav-link">
                        <span class="fi-rr-home" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Accueil
                    </a>
                </li>
                <li class="nav-item" style="margin-right: 10px;">
                    <a href="who.php" style="align-items:center;display:flex" class="nav-item nav-link">
                        <span class="fi-rr-world" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎A propos
                    </a>
                </li>
                <li class="nav-item" style="margin-right: 10px;">
                    <a href="metiers.php" style="align-items:center;display:flex" class="nav-item nav-link">
                        <span class="fi-rr-edit" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Métiers
                    </a>
                </li>
                <li class="nav-item" style="margin-right: 10px;">
                    <a href="ref.php" style="align-items:center;display:flex" class="nav-item nav-link">
                        <span class="fi-rr-search-alt" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Références
                    </a>
                </li>
                <li class="nav-item" style="margin-right: 10px;">
                    <a href="part.php" style="align-items:center;display:flex" class="nav-item nav-link">
                        <span class="fi-rr-comment-user" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Partenaires
                    </a>
                </li>
                <li class="nav-item" style="margin-right: 10px;">
                    <a href="contact.php" style="align-items:center;display:flex" class="nav-item nav-link">
                        <span class="fi-rr-phone-call" style="font-size:25px;margin-top:7px;padding-right:5px"></span>‎Contact
                    </a>
                </li>
            </ul>
        </div>

    </nav>

</header>



</html>