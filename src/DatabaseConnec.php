<?php

class DatabaseConnec {

  private static $instance = null;
  private $connection;

  private $user = 'root';
  private $pass = 'root';

  // La connexion à la base se fait dans le constructeur
  private function __construct()
  {
    try {
        $this->connection = new PDO('mysql:host=localhost;dbname=gallery_photos', $this->user, $this->pass);
        // $this->connection->query("DROP TABLE Pictures");
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
  }

/*
  Methode retournant l'instance actuelle de l'objet selon si elle est déjà
  existante ou non
*/
  public static function getInstance()
  {
    if (!self::$instance) {
      self::$instance = new DatabaseConnec();
    }

    return self::$instance;
  }

  public function getConnection()
  {
    return $this->connection;
  }

  /*
    Préchargement des images dans la base
  */
  public function loadFixtures()
  {
    $creationRequest = "CREATE TABLE IF NOT EXISTS Pictures (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pic_path VARCHAR(200) NOT NULL,
    title VARCHAR(200) NOT NULL,
    description VARCHAR(200) NOT NULL
    )";

    $this->connection->query($creationRequest);

    $insertQueries = "INSERT INTO Pictures (pic_path, title, description)
    VALUES ('/assets/jardin.jpg', 'Airbus 300', 'Un avion allant au Mexique.'),
    ('/assets/avion.jpg', 'BMW 2018', 'La voiture innovante de l\'année.'),
    ('/assets/aeroport.jpg', 'Yamaha X', 'Moto la plus rapide au monde.'),
    ('/assets/maya.jpg', 'Decatbic', 'Vélo sans pédale.')
    ";

    $this->connection->query($insertQueries);
  }

  /*
    Méthode affichant toutes les images de la base via leurs miniatures
    responsives ainsi que les informations leurs correspondant
  */
  public function showMiniatures()
  {
    $selectQuery = "SELECT * FROM Pictures";
    $reponse = $this->connection->query($selectQuery);

    while ($donnees = $reponse->fetch()) {
      echo '<div class="col-lg-4 col-md-4 col-xs-6"><div class="thumbnail">';
      echo '<img src="' . $donnees['pic_path'] . '" alt="' . $donnees['title'] .'" style="width:350px; height:200px" onclick="openModal();currentSlide('. $donnees['id'] .')" class="hover-shadow cursor">';
      echo '<div class="caption"><p><strong>' . $donnees['title'] . '</strong><br>' . $donnees['description'] . '</p></div></div></div>';
    }
  }

  /*
    Méthode affichant les slides des images dans la Carousel
  */
  public function showModalPictures()
  {
    $selectQuery = "SELECT * FROM Pictures";
    $reponse = $this->connection->query($selectQuery);

    while ($donnees = $reponse->fetch()) {
      echo '<div class="mySlides"><img src="' . $donnees['pic_path'] . '" style="width:100%"></div>';
    }
  }

}
