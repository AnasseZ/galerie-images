<?php
error_reporting(E_ALL);

session_start();

include("DatabaseConnec.php");

/* Utilisation du pattern singleton afin d'instancier uniquement une seule
 connexion à la base
*/
$instance = DatabaseConnec::getInstance();
include 'utils.php';

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
      <a href="/src/" >Accueil</a><a href="/src/repertories.php"></a>
      <div class="content">
        <?php
          showheader();
          if(isset($_GET['rep'])) {
            $name = $instance->getRepertoryName($_GET['rep']);
            echo '<div class="row">';
            echo "<h2>Répertoire ". $name ."</h2></br>";
            echo "<h3>Répertoires contenus :</h3>";
            $instance->showRepContent($_GET['rep']);
            echo '</div>';
        ?>
        </br>
        <?php
          echo '<div class="row">';
          echo '<h3>Images contenues :</h3>';
          $instance->showRepMiniatures($_GET['rep']);
          echo '</div>';
          }
          else {
        ?>
        <div class="row">
          <h2> Répertoires à la racine </h2>
        </br>
          <?php  $instance->showAllBaseRepertories(); ?>
        </div>
        <?php
          }
        ?>
      </div>

    </div>

  </body>
</html>
