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
        <?php $instance->showModalPictures(); ?>
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
      <a href="/src/" >Accueil</a> &nbsp; <a href="/src/repertories.php">Répertoires</a>
      <div class="content">
        <?php showheader(); ?>
        </br>
        <div class="row">
          <h2>Toutes les images</h2>
          <?php $instance->showAllMiniatures(); ?>
        </div>
      </div>

    </div>

  </body>
</html>
