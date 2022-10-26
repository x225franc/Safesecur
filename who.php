<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="Flaticon/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="style.css">
    <title>Qui Sommes nous ?</title>

    <style> 
    
    body {
  background-image: url('ressources/2.jpg');
  background-position: center center;
  background-repeat: no-repeat;
  background-attachment: fixed;  
  background-size: cover;
  /* background: -webkit-linear-gradient(left, blue, green); */
}

.tracking-in-expand {
	animation: tracking-in-expand 0.7s cubic-bezier(0.215, 0.610, 0.355, 1.000) both;
}

@keyframes tracking-in-expand {
  0% {
    letter-spacing: -0.5em;
    opacity: 0;
  }
  40% {
    opacity: 0.6;
  }
  100% {
    opacity: 1;
  }
}

span li {
  color:white;
  font-size: 19px;
}

.text-shadow-drop-center {
	-webkit-animation: text-shadow-drop-center 0.6s both;
	        animation: text-shadow-drop-center 0.6s both;
}

@-webkit-keyframes text-shadow-drop-center {
  0% {
    text-shadow: 0 0 0 rgba(0, 0, 0, 0);
  }
  100% {
    text-shadow: 0 0 18px rgba(0, 0, 0, 0.35);
  }
}
@keyframes text-shadow-drop-center {
  0% {
    text-shadow: 0 0 0 rgba(0, 0, 0, 0);
  }
  100% {
    text-shadow: 0 0 18px rgba(0, 0, 0, 0.35);
  }
}

</style>

</head>

<body style="min-height:180vh;display:flex;flex-direction:column;">




<?php include "header.php" ?>

<h1 style="border: 0px solid #FFFFFF; background:white;text-align:center;color:ivory;font-family:arial;font-size:5vw;font-style:italic;text-shadow: 3px 3px 8px #000000;"> <p class="tracking-in-expand"> QUI SOMMES NOUS ?</p></h1>

<br>

<h1 style="border: 0px solid #FFFFFF;text-align:center;color:ivory;font-family:arial;font-size:2vw;font-style:italic;text-shadow: 3px 3px 8px #000000;"><p class="text-focus-in"> PRÉSENTATION </p></h1>
<br>
<div style="display: flex; padding-left:150px; padding-right:150px; align-items:center" >
  <img src="ressources/electrician.jpg" alt="Electrician" width="40%" class="text-shadow-drop-center" style="border: 6px ridge #ffffff;">
<span>
  <ul>
<li>De la volonté de réunir dans une équipe de taille humaine, l’expérience et le savoir-faire de spécialistes ayant œuvré auprès de grands noms du marché, SAFESECUR a été créée.</li>
<br>
<li>L’entreprise SAFESECUR ambitionne d’être un partenaire compétent et fiable dans le domaine des installations de sécurité et protection en Côte d’Ivoire et dans la sous région, par la représentation de fabricants européens de renom et la réalisation des travaux de qualités dans les délais impartis.</li>
<br>
<li>SAFESECUR répond aux attentes de ses clients en leur apportant une solution toujours plus pertinente.</li>
</ul>
</span>
</div>

<br>
<br>
<br>
<br>
<br>

<h1 style="border: 0px solid #FFFFFF;text-align:center;color:ivory;font-family:arial;font-size:2vw;font-style:italic;text-shadow: 3px 3px 8px #000000;"><p class="text-focus-in"> NOTRE MISSION </p></h1>
<br>
<div style="display: flex; padding-left:50px; padding-right:50px; align-items:center" >
  <span style="padding-left:50px; padding-right:50px; align-items:center">
    <ul>
      <li> SAFESECUR déploie l’ensemble de ses compétences dans des domaines très diversifiés: du tertiaire au risque industriel stratégique. Afin d’être un partenaire toujours plus pertinent auprès des entreprises. SAFESECUR s’est engagée dans une démarche d’évolution permanente et de veille technologique.</li>
      <br>
      <div>
      <button data-toggle="collapse" data-target="#demo" class="btn btn-primary border bordered">Nos Missions ‎ ‎</button>
      <div id="demo" class="collapse">
      <li> l’étude, la conception, la mise en œuvre et la maintenance des systèmes de sécurité des biens et des personnes. </li>
      <li> La formation du personnel à l’utilisation du matériel installé.
      </li>
      </div>
      
      <br>
      <button data-toggle="collapse" data-target="#demo" class="btn btn-primary border bordered">Nos Garanties</button>
      <div id="demo" class="collapse">
      <li> Equipe sérieuse et expérimentée.</li>
      <li> Utilisation de matériaux de premier choix.</li>
      <li> Respect des normes et des règles de construction.</li>
      <li> Respect de nos engagements.</li>
      <li> Responsabilité sociétale.</li>
      </div>
      </div>
      
    </ul>
  </span>
  <img src="ressources/plan.jpg" alt="Electrician" width="40%" class="text-shadow-drop-center" style="border: 6px ridge #ffffff;">
</div>

<br>
<br>
<br>
<br>
<br>

<h1 style="border: 0px solid #FFFFFF;text-align:center;color:ivory;font-family:arial;font-size:2vw;font-style:italic;text-shadow: 3px 3px 8px #000000;"><p class="text-focus-in"> NOTRE ÉQUIPE </p></h1>
<br>
<img src="ressources/ekip.jpg" alt="l'Equipe">

<?php include "footer.php" ?>

</body>
</html>