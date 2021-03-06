<?php
error_reporting(E_ALL);

include("DatabaseConnec.php");

/* Utilisation du pattern singleton afin d'instancier uniquement une seule
 connexion à la base
*/
$instance = DatabaseConnec::getInstance();

// Utilisation des sessions pour ne remplir qu'une seule fois la base au démarrage
if(isset($_GET['first_run']) and $_GET['first_run'] == 15) {
    $instance->loadFixtures();
}
?>

<!DOCTYPE html>
<html lang="fr">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Mini projet - Mercredi</title>

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="index.css" rel="stylesheet">

  </head>

  <body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
      <div class="container">
        <?php
          if(isset($_GET['rep'])){
            echo '<a class="navbar-brand js-scroll-trigger" href="/src/">Mini-Projet du mercredi </a>';
          }
          else{
            echo '<a class="navbar-brand js-scroll-trigger" href="#page-top">Mini-Projet du mercredi </a>';
          }
        ?>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#repertoires">Parcours des Répertoires</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#galery">Galerie photos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="?first_run=15">Réinitialiser la base</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Header -->
    <header class="masthead">
      <div class="container">
        <div class="intro-text">
          <div class="intro-lead-in">Mini projet du mercredi</div>
          <div class="intro-heading">Galerie photos</div>
          <a class="btn btn-menu js-scroll-trigger" href="#repertoires">Parcourir les répertoires</a>
          <a class="btn btn-menu js-scroll-trigger" href="#galery">Afficher toutes les images</a>
        </div>
      </div>
    </header>

    <!-- Repertoires -->
    <section id="repertoires">
      <?php
      if(isset($_GET['rep'])){
        $currentRepo =  $instance->getRepertory($_GET['rep']);
      }
      ?>
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="name-rep">Parcourir les répertoires</h2>
            <?php
              if(isset($_GET['rep'])){
                echo '<h4 class="description-bloc text-muted">'. $currentRepo['name'] .'</h4>';
              }
              else {
                echo '<h3 class="description-bloc text-muted">Répertoires à la base de la racine</h3>';
              }
            ?>
          </div>
        </div>

        <div class="row text-center">
          <?php
            if(isset($_GET['rep'])){
              if($currentRepo['parent_id'] == null){
                $redirection = "/src/";
              }
              else{
                $redirection = '?rep='.$currentRepo['parent_id'];
              }
              echo '
                <div class="col-md-4">
                  <a href="'. $redirection .'" class="rep-base">
                      <span class="fa fa-folder-open fa-4x"></span>
                      <h4 class="name-rep">&#8617; Retour</h4>
                  </a>
                </div>
              ';
              $instance->showRepContent($_GET['rep']);
            }
            else {
              $instance->showRepContent();
            }
          ?>
        </div>

      </div>
    </section>

    <!-- Galerie photos -->
    <section class="bg-light" id="galery">
      <div class="container">

        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="name-rep">Galerie photos</h2>
            <?php
            if(isset($_GET['rep'])){
              echo '<h4 class="description-bloc text-muted">'. $currentRepo['name'] .'</h4>';
            }
            else {
              echo '<h3 class="description-bloc text-muted">Toutes les images présentes dans tous les répertoires et sous répertoires</h3>';
            }
            ?>

          </div>
        </div>

        <div class="row">
          <?php
            if(isset($_GET['rep'])){
              $instance->showRepMiniatures($_GET['rep']);
            }
            else {
              $instance->showRepMiniatures();
            }
          ?>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <span class="copyright">Copyright &copy; ZOUGARH Anasse, MALINGE Quentin, MASSON Emilien et FREMONT Floriant.</span>
          </div>
        </div>
      </div>
    </footer>

    <!-- Modales -->
    <?php
      if(isset($_GET['rep'])){
        $instance->showModalPictures($_GET['rep']);
      }
      else {
        $instance->showModalPictures();
      }
    ?>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/popper/popper.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="agency.js"></script>

  </body>

</html>
