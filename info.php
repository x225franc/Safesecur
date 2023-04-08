<?php
echo '<link rel="icon" type="image/x-icon" href="ressources\favicon.ico">';
include("db.php");



// recuperons les informations

$r = $cnx->query('SELECT * FROM info ORDER BY id_info DESC ');

// on recupere le mail de l'admin
$rx = $cnx->query('SELECT mail FROM user WHERE id_user = 1');
$rxx = $rx->fetch();


// modification du mail de l'admin
if (isset($_POST['mail'])) {
    $mail = $_POST['mail'];
    $req = $cnx->prepare('UPDATE user SET mail = :mail WHERE id_user = 1');
    $req->execute(array(
        'mail' => $mail
    ));
    echo '<script>alert("Modification effectuée")</script>';
    echo '<meta http-equiv="refresh" content="0">';
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.1.3/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="Flaticon/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="style.css">

    <title>Espace Administrateur</title>

</head>


<body style="min-height:180vh;display:flex;flex-direction:column;">


    <?php include "header.php" ?>

    <div class="container-fluid mt-100" style="margin-top:25px; margin-bottom:35px;">

        <h1 style="text-align: center;">Tableaux de leads </h1><br>

        <?php
        if (!empty($_SESSION)) {



            // petit formulaire pour modifier l'adresse email de l'admin
            echo '<form method="post" style="text-align:center;">';
            echo '<label for="mail">Email permettant de recevoir les nouveau contact</label>';
            echo '<br>';
            echo '<input type="email" name="mail" placeholder="Adresse mail" style="width: 30%; margin-right: 10px;" value="' . (!empty($rxx['mail']) ? $rxx['mail'] : '') . '">';
            echo '<input type="submit" name="submit" value="Modifier" style="width: 10%;">';
            echo '</form>';
            echo '<br>';
            echo '<br>';

        ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header pr-0 pl-0">
                            <div class="row no-gutters align-items-center w-100">
                                <div class="col font-weight-bold pl-3">Message / Date</div>
                                <div class="d-none d-md-block col-6 text-muted">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-3">Nom</div>
                                        <div class="col-3">Email</div>
                                        <div class="col-6">Telephone</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        while ($q = $r->fetch()) {
                        ?>
                            <div class="card-body py-3">
                                <div class="row no-gutters align-items-center">
                                    <div class="col" style="padding-right: 10px">
                                        <a> <?= $msg = $q['msg']; ?> / <strong><?= $date = $q['date'] ?> </strong> </a>
                                    </div>




                                    <div class="d-none d-md-block col-6">
                                        <div class="row no-gutters align-items-center" style="padding-right: 10px">
                                            <div class="col-3" style=" margin-left: -10px;">
                                                <a> <?= $name = $q['name']; ?> </a>
                                            </div>
                                            <div class="col-3" style="padding-right: 10px">
                                                <a> <?= $mail = $q['mail']; ?> </a>
                                            </div>
                                            <div class="col-3" style="padding-left: 10px;">
                                                <a> <?= $phone = $q['phone']; ?> </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-0">
                        <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
    </div>

    <a href="break.php" class="nav-item nav-link" style="padding-left:6.5vw; display:flex; align-items:center;  justify-content:center;">
        <h4 style="padding-right: 10px;"> Quitter </h4> <i class="fi-rr-sign-out" style="font-size:25px; margin-right:5px"></i>
    </a>

<?php
        } else {
?>
    <div class="alert alert-danger text-center"><a href="login.php"> Connectez vous </a></div>

<?php
        }
?>
</div>

<?php include "footer.php" ?>

</body>

</html>