<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="Flaticon/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="style.css">
    <title>Nos Metiers</title>

    <style> 
    
    body {
  background-image: url('ressources/3.png');
  background-position: center center;
  background-repeat: no-repeat;
  background-attachment: fixed;  
  background-size: cover;
  /* background: -webkit-linear-gradient(left, blue, green); */
}

.focus-in-contract {
	-webkit-animation: focus-in-contract 0.7s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
	        animation: focus-in-contract 0.7s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
}

@-webkit-keyframes focus-in-contract {
  0% {
    letter-spacing: 1em;
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
@keyframes focus-in-contract {
  0% {
    letter-spacing: 1em;
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

/* accordion */

.accordion_two_section {
    background: transparent;
}

.ptb-100 {
    padding-top: 130px;
    padding-bottom: 100px;
}

.accordionTwo .panel-group {
    margin-bottom: 0;
}

.accordionTwo .panel {
    background-color: transparent;
    box-shadow: none;
    border-bottom: 10px solid transparent;
    border-radius: 0;
    margin: 0;
}

.accordionTwo .panel-default {
    border: 0;
}

.accordionTwo .panel-default>.panel-heading {
    background: red;
    border-radius: 0px;
    border-color: #4385f5;
}

.accordion-wrap .panel-heading {
    padding: 0px;
    border-radius: 0px;
}

.panel-title {
    margin-top: 0;
    margin-bottom: 0;
    font-size: 16px;
    color: inherit;
}

.accordionTwo .panel .panel-heading a.collapsed {
    color: #999999;
    background-color: #fff;
    display: block;
    padding: 12px 20px;
}

.accordionTwo .panel .panel-heading a {
    display: block;
    padding: 12px 20px;
    color: #fff;
}

.accordion-wrap .panel .panel-heading a {
    font-size: 14px;
}

.accordionTwo .panel-group .panel-heading+.panel-collapse>.panel-body {
    border-top: 0;
    padding-top: 0;
    padding: 20px 20px 20px 30px;
    background: black;
    color: #fff;
    font-size: 14px;
    line-height: 24px;
}

.accordionTwo .panel .panel-heading a:after {
    content: "\2212";
    color: #4285f4;
    background: #fff;
}

.accordionTwo .panel .panel-heading a:after, .accordionTwo .panel .panel-heading a.collapsed:after {
    font-family: 'FontAwesome';
    font-size: 14px;
    float: right;
    width: 21px;
    display: block;
    height: 21px;
    line-height: 21px;
    text-align: center;
    border-radius: 50%;
    color: #FFF;
}

.accordionTwo .panel .panel-heading a.collapsed:after {
    content: "\2b";
    color: #fff;
    background-color: #DADADA;
}

.accordionTwo .panel .panel-heading a:after {
    content: "\2212";
    color: #4285f4;
    background: #dadada;
}

a:link {
    text-decoration: none
}

</style>

</head>

<body style="min-height:100vh;display:flex;flex-direction:column;">




<?php include "header.php" ?>


<h1 style="border: 0px solid #FFFFFF; background:white;text-align:center;color:ivory;font-family:arial;font-size:5vw;font-style:italic;text-shadow: 3px 3px 8px #000000;"><p class="focus-in-contract"> NOS MÉTIERS</p></h1>

<section class="accordion_two_section ptb-100">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 accordionTwo">
                            <div class="panel-group" id="accordionTwoLeft">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordionTwoLeft" href="#collapseTwoLeftone" aria-expanded="false" class="collapsed">
                              <strong>SECURITÉ TRADITIONNELLE</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoLeftone" class="panel-collapse collapse" aria-expanded="false" role="tablist" style="height: 0px;">
                                        <div class="panel-body">
                                          <ul>
                                        <li>Extincteurs</li>
                                        <li>Matériels blindes (coffre-fort, armoires ignifuges réfractaires)</li>
                                        <li>Divers matériels d’intervention et de sauvetage (pompier)</li>
                                        <li>Réseau incendie –RIA (Robinets incendie Armes)</li>
                                        <li>Emulseur appareillage à mousse etc.</li>
                                        <li>Une gamme complète des BAES (Bloc Autonome d’éclairage et   de sécurité) répondant à vos besoins en éclairage de sécurité</li>
                                        <li>Fabrication des SAS de sécurité</li>
                                        <li>Serrurerie</li>
                                        <li>Porte et matériel blindés</li>
                                        <li>Grilles renforcées ;(vitrage triplex guichet et caisse blindes)</li>
                                        </ul>
                                      </div>
                                    </div>
                                </div>
                                <!-- /.panel-default -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoLeft" href="#collapseTwoLeftTwo" aria-expanded="false">
                               <strong>PRESTATION RESEAU ET INFORMATIQUE</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoLeftTwo" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                          <ul>
                                        <li> Déploiement et maintenance de réseau haut débit…</li>
                                        <li>Conseil et dimensionnement, déploiement et maintenance de système d’information…</li>
                                        </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.panel-default -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoLeft" href="#collapseTwoLeftThree" aria-expanded="false">
                              <strong>CÔNTROLE D’ACCES ET GESTION DE TEMPS DE PRESENCE- GESTION RH- PLANIFICATION TEMPS DE TRAVAIL</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoLeftThree" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                        <p>Collaborateurs, visiteurs, intervenants temporaires, entreprises extérieures…</p>

                                        <p>Chaque jour, nombreuses personnes se déplacent dans les entreprises et bâtiments publics . Comment donc distinguer l’intervention d’une entreprise extérieure ou une visite d’un acte de malveillance en préparation?</p>

                                        <p> <strong> SAFESECUR </strong> vous propose alors des systèmes performants et simples d’utilisation qui vous permettent de filtrer les accès et de définir des zones de protocole.</p>

                                        <img src="ressources/controle.jpg" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoLeft" href="#collapseTwoLeftFour" aria-expanded="false">
                                <strong>L'INTEGRATION DE SYSTEME DE SECURITÉ</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoLeftFour" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                    <p>Intégration de tous les systèmes de sécurité pour:</p>
                                    
                                    
                                    <ul>
                                      <li>L’analyse des risque, la conception du système et la consultance</li>
                                      <li>Gestion de la vidéo numérique et analogique</li>
                                      <li>Analyse vidéo</li>
                                      <li>Vidéosurveillance en tant que service (VSAAS)</li>
                                      <li>Surveillance 24/7</li>
                                    </ul>
                                    <img src="ressources/monitor.jpg" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoLeft" href="#collapseTwoLeftFive" aria-expanded="false">
                                <strong>EXTINCTEURS PORTATIFS - MOBILE AUTOMATIQUES</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoLeftFive" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                      <p>Disposer d’extincteurs dans un bâtiment est chose indispensable. En cas de départ d’incendie, le simple fait d’en disposer et de s’en servir aide à réduire l’étendue du sinistre et sauve même des vies.</p>
                                      <br>
                                      <p><strong>SAFESECUR</strong> dans son optique de recherche continue de la qualité met à votre disposition des équipements de qualité certifiés <strong>ISO 9000, CE, VERITAS, Marine Division, NF- AFNOR, EN3</strong> selon les équipements, produits par un fabriquant de renommée internationale.</p>
                                      <img src="ressources/extincteurs.jpg" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoLeft" href="#collapseTwoLeftSix" aria-expanded="false">
                                <strong>SIGNALISATIONS ET BLOCS AUTONOMES D'ECLAIRAGE ET DE SECURITE</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoLeftSix" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                        <img src="ressources/securite.png" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoLeft" href="#collapseTwoLeftSeven" aria-expanded="false">
                                <strong>CONSEILS ET AUDITS</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoLeftSeven" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                          <p>La sécurité incendie des entreprises, des établissements recevant du public ou la sécurité des personnes lors de manifestations ponctuelles ou résidentielles ne peuvent être confiées qu’à des spécialistes rompus à ce genre d’exercice.</p>
                                          <br>
                                          <p>Pour répondre au mieux à l’attente de nos clients et concilier leurs besoins avec la ou les réglementations en vigueur, nous commençons notre intervention par l’analyse des risques en vue d’élaborer un audit et conseils en sécurité incendie et systèmes de protection afin de définir une stratégie d’intervention dont tous les aspects sont pris en compte (organisationnels, personnels, architecturaux, techniques ou stratégiques). Ces missions et vérifications sont réalisées par des techniciens expérimentés.</p>
                                          <img src="ressources/b.jpg" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.panel-default -->
                            </div>
                            <!--end of /.panel-group-->
                        </div>
                        <!--end of /.col-sm-6-->
                        <div class="col-sm-6 accordionTwo">
                            <div class="panel-group" id="accordionTwoRight">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoRight" href="#collapseTwoRightone" aria-expanded="false">
                                <strong>SECURITE EN AUTOMATISMES ET HAUTES TECHNOLOGIES</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoRightone" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                        <ul>
                                          <li>Système de Détection incendie et alarme Evacuation</li>
                                          <li>Extinction automatique à gaz</li>
                                          <li>Extinction a eau et poudre</li>
                                          <li>Contrôled’accès</li>
                                          <li>Appel Infirmier</li>
                                          <li>Interphonie et vidéophonie sur IP</li>
                                          <li>Gestion du temps et du personnel</li>
                                          <li>Gestion des paiements</li>
                                          <li>Vidéosurveillance analogique ou IP, Télésurveillance</li>
                                          <li>Détection intrusion et vol (risques courants)</li>
                                        </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.panel-default -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoRight" href="#collapseTwoRightTwo" aria-expanded="false">
                                <strong>SECURITE DES SERVICES MEDICAUX ET DES PATIENTS</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoRightTwo" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                        <p> Une illustration d’un centre de santé avec tout le système de sécurité que nous fournissons.</p>
                                        <img src="ressources/c.jpg" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.panel-default -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoRight" href="#collapseTwoRightThree" aria-expanded="false">
                                 <strong>L’INTEGRATION AVEC LA GESTION DES INFORMATIONS DE SECURITE</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoRightThree" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                        <img src="ressources/d.jpg" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoRight" href="#collapseTwoRightFour" aria-expanded="false">
                                 <strong>VIDEOSURVEILLANCE IP</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoRightFour" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                            <p>Quasiment complémentaire au contrôle d’accès, la vidéosurveillance vous permet d’avoir littéralement un œil sur tout mouvement à l’intérieur et à l’extérieur du bâtiment.
                                            Un système de vidéo surveillance constitue donc un allié sûr et fiable qui permet d’observer tout mouvement suspect à l’intérieur du bâtiment et d’anticiper d’éventuels intrusions et incidents.</p>
                                            <img src="ressources/e.jpg" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoRight" href="#collapseTwoRightFive" aria-expanded="false">
                                 <strong>ROBINETTERIE ET MATERIEL D'INTERVENTION INCENDIE</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoRightFive" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                            <p>Pour agir rapidement et efficacement sur les zones à risques, nous mettons à votre disposition quantité de systèmes, pompes et robinets incendie armés conçus par la marque référence en élaboration de ces types de systèmes.</p>
                                            <img src="ressources/f.jpg" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoRight" href="#collapseTwoRightSix" aria-expanded="false">
                                 <strong>EQUIPEMENTS DE PROTECTION INDIVIDUELLE</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoRightSix" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                            <p>Un Equipement de Protection Individuelle (E.P.I.) est un dispositif
                                            ou un moyen destiné à être porté ou être tenu par une personne en
                                            vue de la protéger contre un ou plusieurs risques susceptibles de
                                            menacer sa santé ainsi que sa sécurité. Les EPI vont du casque aux chaussures de sécurité en passant par les lunettes, les masques de protection respiratoire, les bouchons d’oreille, les gants, …</p>

                                            <br>

                                            <p> <strong> SAFESECUR </strong> vous fournit des équipements fiables conçus par des fabricants de référence.</p>
                                            <img src="ressources/g.jpg" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordionTwoRight" href="#collapseTwoRightSeven" aria-expanded="false">
                                 <strong>FORMATIONS</strong>
                              </a>
                            </h4>
                                    </div>
                                    <div id="collapseTwoRightSeven" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                        <div class="panel-body">
                                            <p>Afin de permettre au client de se familiariser à l’utilisation et la manipulation du matériel ainsi qu’aux mesures d’urgence, SAFESECUR organise des formations auprès des entreprises, ses clients, pour leur apporter le meilleur service possible.</p>
                                            <img src="ressources/h.jpg" alt="" style="width: -webkit-fill-available;">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.panel-default -->
                            </div>
                            <!--end of /.panel-group-->
                        </div>
                        <!--end of /.col-sm-6-->
                    </div>
                </div>
                <!--end of /.container-->
            </section>

<?php include "footer.php" ?>

</body>
</html>