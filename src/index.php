<?php
error_reporting(E_ALL);

session_start();

include("DatabaseConnec.php");

/* Utilisation du pattern singleton afin d'instancier uniquement une seule
 connexion à la base
*/
$instance = DatabaseConnec::getInstance();

// Utilisation des sessions pour ne remplir qu'une seule fois la base au démarrage
if(!isset($_SESSION['first_run'])) {
    echo 'test';
    $_SESSION['first_run'] = 1;
    $instance->loadFixtures();
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Projet du mercredi</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="index.css" rel="stylesheet">

    <script src="modal.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  </head>

  <body>

  <div id="myModal" class="modal">
    <span class="close cursor" onclick="closeModal()">&times;</span>
    <div class="modal-content">
      <?php  $instance->showModalPictures(); ?>
      <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
      <a class="next" onclick="plusSlides(1)">&#10095;</a>

      <div class="caption-container">
        <p id="caption"></p>
      </div>


    </div>


  </div>

    <div class="container">
      <h1> Mini projet </h1>
      </br>
      <div class="row">
        <h2> Répertoires à la racine </h2>
      </br>
        <?php  $instance->showAllBaseRepertories(); ?>
      </div>
      </br>
      </br>
      <div class="row">
        <h2>Images contenus dans le répertoire </h2>
        <?php
           $instance->showMiniatures();
         ?>
      </div>
    </div>
  </body>
</html>
