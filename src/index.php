<?php

session_start();

include("DatabaseConnec.php");

$instance = DatabaseConnec::getInstance();

if(!isset($_SESSION['first_run'])) {
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>

  <body>
    <div class="container">
      <h2> Mini projet </h2>
      <div class="row">
        <?php
          $instance->affiche();
         ?>
      </div>
    </div>
  </body>
</html>
