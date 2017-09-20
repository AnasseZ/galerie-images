<?php
error_reporting(E_ALL);

session_start();

include("DatabaseConnec.php");

/* Utilisation du pattern singleton afin d'instancier uniquement une seule
 connexion à la base
*/
$instance = DatabaseConnec::getInstance();
include 'utils.php';

// Utilisation des sessions pour ne remplir qu'une seule fois la base au démarrage
if(!isset($_SESSION['first_run'])) {
    echo 'test';
    $_SESSION['first_run'] = 1;
    $instance->loadFixtures();
}
?>

<!DOCTYPE html>
<html lang="fr">
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
      <div class="content">
        <?php
          showheader();
          if(isset($_GET['rep'])) {
            $instance->showRepertory($_GET['rep']);
        ?>
        </br>
        <?php
          $instance->showRepMiniatures($_GET['rep']);
          }
          else {
        ?>
        <div class="row">
          <h2> Répertoires à la racine </h2>
        </br>
          <?php  $instance->showAllBaseRepertories(); ?>
        </div>
        </br>
        </br>
        <div class="row">
          <h2>Images contenus dans le répertoire </h2>
          <?php $instance->showMiniatures(); ?>
        </div>
        <?php
          }
        ?>
      </div>

    </div>

  </body>
</html>
