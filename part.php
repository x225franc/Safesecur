<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="Flaticon/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="style.css">
    <title>Partenaires</title>

    <style> body {
  /* background-image: url('ressources/background.png');
  background-position: center center;
  background-repeat: no-repeat;
  background-attachment: fixed;  
  background-size: cover; */
  background: -webkit-linear-gradient(left, ivory, white);
  
}

.focus-in-expand {
	-webkit-animation: focus-in-expand 0.8s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
	        animation: focus-in-expand 0.8s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
}

@-webkit-keyframes focus-in-expand {
  0% {
    letter-spacing: -0.5em;
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
@keyframes focus-in-expand {
  0% {
    letter-spacing: -0.5em;
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


.boss1 img {
  width: 32%;
}

/* slideshow */
.col-md-3{
  display: inline-block;
  margin-left:-4px;
}
.col-md-3 img{
  width:100%;
  height:auto;
}
body .carousel-indicators li{
  background-color:red;
}
body .carousel-control-prev-icon,
body .carousel-control-next-icon{
  background-color:red;
}
body .no-padding{
  padding-left: 0;
  padding-right: 0;
}

</style>

</head>

<body style="min-height:100vh;display:flex;flex-direction:column;">




<?php include "header.php" ?>

<h1 style="border: 0px solid #FFFFFF; background:white;text-align:center;color:ivory;font-family:arial;font-size:5vw;font-style:italic;text-shadow: 3px 3px 8px #000000;"><p class="focus-in-expand"> NOS PARTENAIRES </p></h1>
<br>
<br>
<div id="demo" class="carousel slide" data-ride="carousel">

  <!-- Indicators -->
  <!--  <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
  </ul> -->
  
  <!-- The slideshow -->
  <div class="container carousel-inner no-padding">
    <div class="carousel-item active">
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/1.png">
      </div>    
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/2.png">
      </div>   
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/3.png">
      </div>   
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/4.png">
      </div>   
    </div>
    <div class="carousel-item">
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/5.png">
      </div>    
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/6.png">
      </div>   
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/7.png">
      </div>   
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/8.png">
      </div>  
    </div>
    <div class="carousel-item">
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/9.png">
      </div>    
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/10.png">
      </div>
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/11.png">
      </div>  
      <div class="col-xs-3 col-sm-3 col-md-3">
        <img src="logo/12.png">
      </div>  
    </div>
  </div>
  
  <!-- Left and right controls -->
  <a class="carousel-control-prev" href="#demo" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#demo" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
</div>


<?php include "footer.php" ?>



</body>
</html>