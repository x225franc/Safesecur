<?php
include "db.php";

#declaration des variables a envoyer en bdd
@$name = htmlspecialchars($_POST['name']);
@$mail = htmlspecialchars($_POST['mail']);
@$phone = htmlspecialchars($_POST['phone']);
@$msg = htmlspecialchars($_POST['msg']);
@$submit = $_POST['submit'];

//On vérifie que les champs ne sont pas vides
if (isset($_POST['submit']) && !empty($name) && !empty($mail) && !empty($phone) && !empty($msg)) {

  // Si oui on procède à l'insertion
  $insert = $cnx->prepare('INSERT INTO info(name,mail,phone,msg,date) VALUES(?,?,?,?,NOW())');

  $insert->execute(array(
    $name,
    $mail,
    $phone,
    $msg
  ));

  @$lastid = $cnx->lastInsertId();
  $ret = $cnx->query('SELECT * FROM info WHERE id_info =  ' . $lastid . '');

  @$q = $ret->fetch();

  //Et enfin on créer une variable pour stocker notre message de réussite
  @$msg = '<span class="alert alert-success" style="display: flex;justify-content: center;width: -webkit-fill-available;"> Envoie reussi ! Redirection à l\'accueil</span>';


  // ENVOIE EMAIL // 

  // on recupere le mail de l'admin
  $rx = $cnx->query('SELECT mail FROM user WHERE id_user = 1');
  $rxx = $rx->fetch();


  @$mail = $rxx['mail'];
  @$subject = "[SAFESECUR] - Nouveau contact !";
  @$message =

    '<div style="text-align:center">
        <h4> <img src="https://i.ibb.co/xFDXnq8/logob.png" alt="logo" width="100" height="100">
            
        <p style="color:#1C8ADA;font-size:35px">SAFESECUR</p><br>
            <b style="color:red"">!</b> <b style="color:#1C8ADA">NE PAS REPONDRE</b> <b style="color:red"">!</b> <br>

            <b style="color:red"">Nouveau contact</b><br><br>

            <b style="color:red"">Nom complet</b> : ' . $q['name'] . '.<br>

            <b style="color:red"">Email</b> : ' . $q['mail'] . '<br>

            <b style="color:red"">Contact</b> : ' . $q['phone'] . '<br><br>

            <b style="color:red"">Message</b> : ' . $q['msg'] . '<br><br>


            <br><br><b style="color:#1C8ADA;font-size:20px"> SAFESECUR  - ' . @date('Y') . ' Copyright</b>
        </h4>
    </div>';



  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

  @mail($mail, $subject, $message, $headers);

  header("refresh:3;url=index.php");
}


?>

<!DOCTYPE html>

<html>

<head>
  <meta charset="UTF-8">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@2.1.3/dist/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="Flaticon/uicons-regular-rounded/css/uicons-regular-rounded.css">
  <link rel="icon" type="image/x-icon" href="ressources\favicon.ico">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>Accueil</title>

  <style>
    #contatti {
      background: -webkit-linear-gradient(left, red, #00c6ff);
      letter-spacing: 2px;
    }

    #contatti a {
      color: #fff;
      text-decoration: none;
    }


    @media (max-width: 575.98px) {

      #contatti {
        padding-bottom: 800px;
      }

      #contatti .maps iframe {
        width: 100%;
        height: 450px;
      }
    }


    @media (min-width: 576px) {

      #contatti {
        padding-bottom: 800px;
      }

      #contatti .maps iframe {
        width: 100%;
        height: 450px;
      }
    }

    @media (min-width: 768px) {

      #contatti {
        padding-bottom: 350px;
      }

      #contatti .maps iframe {
        width: 100%;
        height: 850px;
      }
    }

    @media (min-width: 992px) {
      #contatti {
        padding-bottom: 200px;
      }

      #contatti .maps iframe {
        width: 100%;
        height: 700px;
      }
    }


    #author a {
      color: #fff;
      text-decoration: none;

    }

    .text-focus-in {
      -webkit-animation: text-focus-in 1s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
      animation: text-focus-in 1s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
    }

    @-webkit-keyframes text-focus-in {
      0% {
        -webkit-filter: blur(12px);
        filter: blur(12px);
        opacity: 0;
      }

      100% {
        -webkit-filter: blur(0px);
        filter: blur(0px);
        opacity: 1;
      }
    }

    @keyframes text-focus-in {
      0% {
        -webkit-filter: blur(12px);
        filter: blur(12px);
        opacity: 0;
      }

      100% {
        -webkit-filter: blur(0px);
        filter: blur(0px);
        opacity: 1;
      }
    }
  </style>
</head>


<body style="display:flex;flex-direction:column;">

  <?php include "header.php" ?>

  <div class="row" id="contatti" style="width: 101%;">
    <?php
    if (isset($_POST['submit'])) {
      echo @$msg;
    }
    ?>
    <div class="container mt-5">

      <div class="row" style="height:550px;">
        <div class="col-md-6 maps">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.331795951496!2d-3.9590189!3d5.366258200000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc1ecd100000001%3A0xf42822f3dbfe030b!2sSAFESECUR%20SARL!5e0!3m2!1sfr!2sci!4v1658705451018!5m2!1sfr!2sci" frameborder="0" style="border:0" allowfullscreen class="text-focus-in"></iframe>
        </div>
        <div class="col-md-6">
          <h2 class="text-uppercase mt-3 font-weight-bold text-white">ENVOYEZ NOUS UN MESSAGE</h2>
          <form method="post">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <input type="text" name="name" class="form-control mt-2" placeholder="Nom complet" required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <input type="date" class="form-control mt-2" placeholder="date" value="<?php echo date('Y-m-d'); ?>" disabled>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <input type="email" class="form-control mt-2" name="mail" placeholder="Email" required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <input type="number" class="form-control mt-2" name="phone" placeholder="Telephone" required>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <textarea name="msg" class="form-control" id="exampleFormControlTextarea1" placeholder="Votre message" rows="3" required></textarea>
                </div>
              </div>
              <div class="col-12">
                <input type="submit" name="submit" value="ENVOYER" />
              </div>
            </div>
          </form>
          <div class="text-white" style="padding-top: 20px;">
            <h2 class="text-uppercase mt-4 font-weight-bold">CONTACTS</h2>

            <i class="fa fa-envelope mt-3"></i><a href="mailto:contact@safesecur.com"> contact@safesecur.com</a><br>
            <i class="fas fa-phone mt-3"></i><a href="tel:+2252722495018"> (+225) 27 22 49 50 18</a><br>
            <i class="fas fa-phone mt-3"></i><a href="tel:+2250707526272"> (+225) 07 07 52 62 72</a><br>
            <i class="fas fa-globe mt-3"></i><a href="https://goo.gl/maps/rpZncQoDU9PPaDFeA" target="_blank"> Riviera palmeraie, derrière la sodeci <br> Abidjan (Côte d'Ivoire) </a><br>
            <div class="my-4">
              <a href=""><i class="fab fa-facebook fa-3x pr-4"></i></a>
              <a href="https://www.linkedin.com/in/tanfalotien-ibrahim-diabate-3531b727/?originalSubdomain=ci" target="_blank"><i class="fab fa-linkedin fa-3x"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include "footer.php" ?>

</body>

</html>