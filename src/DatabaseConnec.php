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
    $createPictureTable = "CREATE TABLE IF NOT EXISTS Pictures (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    repertory_id INT(6) NOT NULL,
    file_name VARCHAR(200) NOT NULL,
    title VARCHAR(200) NOT NULL,
    description VARCHAR(200) NOT NULL
    )";

    $createRepertoryTable = "CREATE TABLE IF NOT EXISTS Repertory (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT(6),
    rep_path VARCHAR(200) NOT NULL,
    name VARCHAR(200) NOT NULL
    )";

    $this->connection->query($createPictureTable);
    $this->connection->query($createRepertoryTable);

    $insertRepertories = "INSERT INTO Repertory (id, parent_id, rep_path, name)
    VALUES (1, null, 'random', 'Images aléatoires'),(2, null,'cars', 'Voitures de luxe'),
    (3,2,'bmw', 'Marque BMW')
    ";


    $insertPictures = "INSERT INTO Pictures (repertory_id, file_name, title, description)
    VALUES (1,'jardin.jpg', 'Airbus 300', 'Un avion allant au Mexique.'),
    (1, 'avion.jpg', 'BMW 2018', 'La voiture innovante de l\'année.'),
    (1, 'aeroport.jpg', 'Yamaha X', 'Moto la plus rapide au monde.'),
    (1, 'maya.jpg', 'Decatbic', 'Vélo sans pédale.')
    ";

    $this->connection->query($insertRepertories);
    $this->connection->query($insertPictures);
    echo 'LOADFI';
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
      echo '<img src="/assets/random/' . $donnees['file_name'] . '" alt="' . $donnees['title'] .'" style="width:350px; height:200px" onclick="openModal();currentSlide('. $donnees['id'] .')" class="hover-shadow cursor">';
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
      echo '<div class="mySlides"><img src="/assets/random/' . $donnees['file_name'] . '" style="width:100%"></div>';
    }
  }

  public  function showAllBaseRepertories()
  {
    $selectQuery = "SELECT * FROM Repertory WHERE parent_id is null";
    $reponse = $this->connection->query($selectQuery);
    while ($donnees = $reponse->fetch()) {
      echo '<div class="col-md-3 glyphicon glyphicon-folder-open" aria-hidden="true"> &nbsp;' . $donnees['name'] . '</div>';
    }
  }

}
