<?php 
include("db.php");
session_start();


if (isset($_POST['login'])) {
    // on stocke les informations du formulaire dans des variable.
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];


    if (!empty($username)) {

        //On sélectionne toutes les informations de la table user ou email_user égale a la variable $Email.

        $req = $cnx->query('SELECT * FROM user');

        //On récupère les informations de notre requête.
        $ver = $req->fetch();

        //On vérifie la  correspondance de l'email du mot de passe saisie à ceux de la base de donnée.
        if (@$username == @$ver['username'] && @$password == @$ver['password']) {
            //Si oui on stock les informations de la requête dans des variables de session.
            $_SESSION['username']  = $ver['username'];

            //Si tout le code a bien été executé on redirige l'utilisateur sur la page d'accueil.
            header('Location:info.php');
        } else {

            //Si les informations ne correspondent pas on créer une variable qui va stocker le message d'erreur.
            @$msg = '<span class="alert alert-danger">informations erronées</span>';
        }
    } else {
        //Si tout les champs n'ont pas été remplis on créer une variable qui va stocker le message d'erreur.
        @$msg = '<span class="alert alert-danger">Veuillez remplir tout les champs</span>';
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <title>Connexion</title>

    <style>
    /* login */

.wrapper {
  max-width: 350px;
  min-height: 500px;
  margin: 80px auto;
  padding: 40px 30px 30px 30px;
  background-color: #ecf0f3;
  border-radius: 15px;
  box-shadow: 13px 13px 20px #cbced1, -13px -13px 20px #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-direction: column;
}

.logo {
  width: 80px;
  margin: auto
}

.logo img {
  width: 120%;
  height: 100px;
  border-radius: 50%;
}

.wrapper .name {
  font-size: 1.4rem;
  letter-spacing: 1.3px;
  padding-left: 10px;
  color: black
}

.wrapper .form-field input {
  width: 100%;
  display: block;
  border: none;
  outline: none;
  background: none;
  font-size: 1.2rem;
  color: #666;
  padding: 10px 15px 10px 10px
}

.wrapper .form-field {
  padding-left: 10px;
  margin-bottom: 20px;
  border-radius: 20px;
  box-shadow: inset 8px 8px 8px #cbced1, inset -8px -8px 8px #fff
}

.wrapper .form-field .fas {
  color: #555
}

.wrapper .btn {
  box-shadow: none;
  width: 100%;
  height: 40px;
  background-color: red;
  color: #fff;
  border-radius: 25px;
  box-shadow: 3px 3px 3px #b1b1b1, -3px -3px 3px #fff;
  letter-spacing: 1.3px
}

#text1 {
  text-align: center;
}

.wrapper .btn:hover {
  background-color: black
}

.wrapper a {
  text-decoration: none;
  font-size: 0.8rem;
  color: black
}

.wrapper a:hover {
  color: black
}

@media(max-width: 380px) {
  .wrapper {
      margin: 30px 20px;
      padding: 40px 15px 15px 15px
  }
}

#nom {
  text-align: center;
}

.logo1{
  width: 8%;
}
</style>
</head>

<body>
    <div class="wrapper">
        <?php if (@$msg) {
            echo @$msg;
        }  ?>
        <div class="logo"> <img src="ressources/logob.png" alt="safesecur"> </div>
        <br>
        <form class="p-3 mt-3" method="post">
            <br>
            <div class="form-field d-flex align-items-center"> <span class="far fa-user"></span> <input type="text" name="username" id="username" placeholder="Nom d'utilisateur" required> </div>
            <div class="form-field d-flex align-items-center"> <span class="fas fa-key"></span> <input type="password" name="password" id="pwd" placeholder="Mot de Passe" required> </div>
            <button type="submit" name="login" class="btn mt-3">Connexion</button>
            <br>
            <a href="index.php" class="btn mt-3" style="font-size:inherit;">Retourner à l'accueil</a>
        </form>
        <div class="text-center fs-6" id="text1"> <a href="mailto:diawaraalphamalick225@gmail.com"> Mot de passe oublie? <br> Contactez l'administrateur. </a>
        </div>
    </div>
</body>

</html>