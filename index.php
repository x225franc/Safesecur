<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="Flaticon/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="style.css">
    
    <title>Accueil</title>
    <style>

body {
  /* background-image: url('ressources/background.jpg');
  background-position: center center;
  background-repeat: no-repeat;
  background-attachment: fixed;  
  background-size: cover; */
  background: -webkit-linear-gradient(left, blue, green);
}
.text-focus-in {
	animation: text-focus-in 0.6s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
}

@keyframes text-focus-in {
  0% {
    filter: blur(12px);
    opacity: 0;
  }
  100% {
    filter: blur(0px);
    opacity: 1;
  }
}
.lol {
  color:ivory;
  text-shadow: 5px 5px 10px #000000 ;
}

.lul {
  color:black;
  text-shadow: 5px 5px 10px #FFFFFF ;
}

/*carousel*/
  /* Make the image fully responsive */
  .carousel-inner img {
    width: 100%;
    height: 100%;
  }
</style>
</head>


<body style="min-height:auto;display:flex;flex-direction:column;">


<?php include "header.php" ?>

<h1  style="border: 0px solid #FFFFFF; background:white;text-align:center;color:ivory;font-family:arial;font-size:5vw;font-style:italic;text-shadow: 3px 3px 8px #000000;"><p class="text-focus-in"> SAFESECUR</p></h1>

<h1 style="border: 0px solid #FFFFFF;text-align:center;color:ivory;font-family:arial;font-size:2vw;font-style:italic;text-shadow: 3px 3px 8px #000000;"><p class="text-focus-in"> Votre sécurité , notre priorité </p></h1>


<div id="demo" class="carousel slide" data-ride="carousel" style="padding-bottom: 50px;">
  <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
    <li data-target="#demo" data-slide-to="3"></li>
    <li data-target="#demo" data-slide-to="4"></li>
  </ul>
  <div class="carousel-inner">
    <div class="carousel-item active"> 
      <a href="https://www.bodet-software.com/fr/" target="_blank">
      <img src="slides/1.png" alt="Los Angeles" width="1100" height="500">
      <div class="carousel-caption">
        <h3 class="lol">BODET SOFTWARE</h3>
        <p class="lol">Gestion de présence</p>
        </a>
      </div>   
    </div>
    <div class="carousel-item">
    <a href="https://new.siemens.com/fr/fr.html" target="_blank">
      <img src="slides/2.png" alt="Chicago" width="1100" height="500">
      <div class="carousel-caption">
        <h3 class="lol">SIEMENS</h3>
        <p class="lol">Automatisation</p>
      </div>   
      </a>
    </div>
    <div class="carousel-item">
    <a href="https://www.pok.fr/fr/accueil/" target="_blank">
      <img src="slides/3.png" alt="New York" width="1100" height="500">
      <div class="carousel-caption">
        <h3 class="lul">POK</h3>
        <p class="lul">Prévention Incendie</p>
      </div>   
      </a>
    </div>
    <div class="carousel-item">
    <a href="https://www.extincteurs-andrieu.fr/" target="_blank">
      <img src="slides/4.png" alt="New York" width="1100" height="500">
      <div class="carousel-caption">
        <h3 class="lul">Andrieu</h3>
        <p class="lul">Exctinceurs</p>
      </div>   
      </a>
    </div>
    <div class="carousel-item">
    <a href="https://www.televic.com/en/conference" target="_blank">
      <img src="slides/5.png" alt="New York" width="1100" height="500">
      <div class="carousel-caption">
        <h3 class="lul">Televic</h3>
        <p class="lul">Solution video-conferences</p>
      </div>   
      </a>
    </div>
  </div>
  <a class="carousel-control-prev" href="#demo" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#demo" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
</div>

<h1 style="border: 0px solid #FFFFFF;text-align:center;color:ivory;font-family:arial;font-size:2vw;font-style:italic;text-shadow: 3px 3px 8px #000000;"><p class="text-focus-in"> SAFESECUR PARTENAIRES DE LEADERS MONDIAUX </p></h1>

<?php include "footer.php" ?>

</body>
</html>