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
    $this->connection->query("DROP TABLE IF EXISTS Pictures, Repository");

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
    VALUES (1, null, 'random', 'Images aléatoires','Répertoire avec des images aléatoires sans réel liens'),
    (2, null,'cars', 'Voitures de luxe', 'Des voitures qui coutent une blinde'),
    (3,2,'bmw', 'Marque BMW', 'Bayerische Motoren Werke'),
    (4, null, 'avions', 'Avions', 'Des avions parcequ\'on aime les avions)
    ";


    $insertPictures = "INSERT INTO Pictures (repertory_id, file_name, title, description)
    VALUES (1,'jardin.jpg', 'Jardin', 'Un jardin au petit matin'),
    (4, 'avion.jpg', 'Escadron', 'Escadron d\'avions du 14 juillet.'),
    (4, 'aeroport.jpg', 'Aéroport', 'Photo prise dans le futur'),
    (1, 'maya.jpg', 'Sculpture', 'Ancienne sculpture maya ou asteque.'),
    (1, 'elephant.jpg', 'Elephant', 'Création des machines de l\'ile.'),
    (1, 'maison.jpg', 'Maison', 'Une maison.'),
    (2, 'ferrari.jpg', 'Ferarri', 'Ma future voiture.')
    ";

    $this->connection->query($insertRepertories);
    $this->connection->query($insertPictures);

    /*$this->createThumbNail('../assets/jardin.jpg', '../assets/tmp/jardin.jpg');
    $this->createThumbNail('../assets/avion.jpg', '../assets/tmp/avion.jpg');
    $this->createThumbNail('../assets/aeroport.jpg', '../assets/tmp/aeroport.jpg');
    $this->createThumbNail('../assets/maya.jpg', '../assets/tmp/maya.jpg');
    $this->createThumbNail('../assets/elephant.jpg', '../assets/tmp/elephant.jpg');
    $this->createThumbNail('../assets/maison.jpg', '../assets/tmp/maison.jpg');
    */
    $getPicturesQuery = "SELECT file_name FROM Pictures";
    $reponse = $this->connection->query($getPicturesQuery);

    while ($donnees = $reponse->fetch()) {
      $this->createThumbNail('../assets/' . $donnees["file_name"], '../assets/tmp/' . $donnees["file_name"]);
    }

  }

  /*
    Méthode affichant toutes les images de la base via leurs miniatures
    responsives ainsi que les informations leurs correspondant
  */
  public function showAllMiniatures()
  {
    $this->showRepMiniatures();
  }

  public function showRepMiniatures($id_rep = null)
  {
    if (isset($id_rep)) {
      $selectQuery = "SELECT * FROM Pictures WHERE repertory_id = " . $id_rep;
    }
    else {
      $selectQuery = "SELECT * FROM Pictures";
    }
    $reponse = $this->connection->query($selectQuery);
    while ($donnees = $reponse->fetch()) {
      echo '<div class="col-md-4 col-sm-6 galery-item">
        <a class="galery-link" data-toggle="modal" href="#myModal1">
          <div class="galery-hover">
            <div class="galery-hover-content">
              <i class="fa fa-plus fa-3x"></i>
            </div>
          </div>
          <img class="img-fluid" style="width:350px; height:200px;" src="/assets/tmp/' .
           $donnees['file_name'] . '" alt="' . $donnees['title'] .'">
        </a>
        <div class="galery-caption">
          <h4>' . $donnees['title'] . '</h4>
          <p class="text-muted">' . $donnees['description'] . '</p>
        </div>
      </div>';
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
      echo '<div class="mySlides"><img src="/assets/' . $donnees['file_name'] . '" style="width:100%"></div>';
    }
  }

  public function showAllBaseRepertories()
  {
    $this->showRepContent(null);
  }

  public function createThumbNail($source_path, $destination_path){
    //"../assets/random/avion.jpg"
    $source = imagecreatefromjpeg($source_path);
    $destination = imagecreatetruecolor(350, 200);

    $largeur_source = imagesx($source);
    $hauteur_source = imagesy($source);
    $largeur_destination = imagesx($destination);
    $hauteur_destination = imagesy($destination);

    imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);

    //"../assets/random/mini_avion.jpg"
    imagejpeg($destination, $destination_path);
  }

  public function showRepContent($id_rep = null)
  {
    if (isset($id_rep)) {
      $selectQuery = "SELECT * FROM Repertory WHERE parent_id = " . $id_rep;
    }
    else {
      $selectQuery = "SELECT * FROM Repertory WHERE parent_id is null";
    }

    $reponse = $this->connection->query($selectQuery);
    while ($donnees = $reponse->fetch()) {
      echo '  <div class="col-md-4">
          <a href="#" class="rep-base">
              <span class="fa fa-folder-open fa-4x"></span>
              <h4 class="name-rep">' . $donnees['name'] . '</h4>
              <p class="text-muted"> Répertoire avec des images aléatoires sans réel liens</p>
          </a>
        </div>';
    }
  }

  public function getRepertoryName($id_rep)
  {
    if (isset($id_rep)) {
      $selectQuery = "SELECT * FROM Repertory WHERE id = " . $id_rep;
      $reponse = $this->connection->query($selectQuery);
      while ($donnees = $reponse->fetch()) {
        return $donnees['name'];
      }
    }
    return null;
  }
}
