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
    $this->connection->query("DROP TABLE IF EXISTS Picture, Repertory");

    $createPictureTable = "CREATE TABLE IF NOT EXISTS Picture (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    repertory_id INT(6) NOT NULL,
    file_name VARCHAR(200) NOT NULL,
    title VARCHAR(200) NOT NULL,
    description VARCHAR(200) NOT NULL
    )";

    $createRepertoryTable = "CREATE TABLE IF NOT EXISTS Repertory (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT(6),
    name VARCHAR(200) NOT NULL,
    description VARCHAR(200) NOT NULL
    )";

    $this->connection->query($createPictureTable);
    $this->connection->query($createRepertoryTable);

    $insertRepertories = "INSERT INTO Repertory (id, parent_id, name, description)
    VALUES (1, null, 'Images aléatoires','Répertoire avec des images aléatoires sans réel liens'),
    (2, null, 'Voitures de luxe', 'Des voitures qui coutent une blinde'),
    (3, 2, 'Marque BMW', 'Bayerische Motoren Werke'),
    (4, null, 'Avions', 'Des avions parcequ\'on aime les avions')
    ";

    $this->connection->query($insertRepertories);

    $fileNames = [
      'jardin',
      'avion',
      'aeroport',
      'maya',
      'elephant',
      'maison',
      'ferrari'
    ];

    $insertPictures = "INSERT INTO Picture (repertory_id, file_name, title, description)
    VALUES (1,'$fileNames[0].jpg', 'Jardin', 'Un jardin au petit matin'),
    (4, '$fileNames[1].jpg', 'Escadron', 'Escadron d\'avions du 14 juillet.'),
    (4, '$fileNames[2].jpg', 'Aéroport', 'Photo prise dans le futur'),
    (1, '$fileNames[3].jpg', 'Sculpture', 'Ancienne sculpture maya ou asteque.'),
    (1, '$fileNames[4].jpg', 'Elephant', 'Création des machines de l\'ile.'),
    (1, '$fileNames[5].jpg', 'Maison', 'Une maison.'),
    (2, '$fileNames[6].jpg', 'Ferarri', 'Ma future voiture.')
    ";

    $this->connection->query($insertPictures);

    for ($i=0; $i < count($fileNames) ; $i++) {
      $this->createThumbNail('../assets/' . $fileNames[$i] . '.jpg', '../assets/tmp/' . $fileNames[$i] . '.jpg');
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
      $selectQuery = "SELECT * FROM Picture WHERE repertory_id = " . $id_rep;
    }
    else {
      $selectQuery = "SELECT * FROM Picture";
    }
    $reponse = $this->connection->query($selectQuery);
    if ($reponse->rowCount()==0) {
      echo "<b>Il n'y a pas d'images dans ce répertoire.</b>";
    }
    while ($donnees = $reponse->fetch()) {
      echo '<div class="col-md-4 col-sm-6 galery-item">
        <a class="galery-link" data-toggle="modal" href="#myModal'. $donnees['id'] . '">
          <div class="galery-hover">
            <div class="galery-hover-content">
              <i class="fa fa-plus fa-3x"></i>
            </div>
          </div>
          <img data-id="#' . $donnees['id'] . '" class="img-fluid"  style="width:350px; height:200px;" data-src="/assets/'. $donnees['file_name'] . '"  src="/assets/tmp/' .
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
  public function showModalPictures($id_rep=null)
  {
    if (isset($id_rep)) {
      $selectQuery = "SELECT * FROM Picture WHERE repertory_id = " . $id_rep;
    }
    else {
      $selectQuery = "SELECT * FROM Picture";
    }
    $reponse = $this->connection->query($selectQuery);
    while ($donnees = $reponse->fetch()) {
      echo '
      <div class="galery-modal modal fade" id="myModal'. $donnees['id'] . '" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
              <div class="lr">
                <div class="rl"></div>
              </div>
            </div>
            <div class="container">
              <div class="row">
                  <div class="col-lg-2 button-modal">
                      <button type="button" class="btn btn btn-menu btn-prev">Précédente</button>
                  </div>
                <div class="col-lg-8 mx-auto">
                  <div class="modal-body">
                    <h2>'. $donnees['title'] . '</h2>
                    <p class="item-intro text-muted">'. $donnees['description'] . '</p>
                    <img id="img-'. $donnees['id'] .'" class="img-fluid d-block mx-auto" alt="' . $donnees['title'] . ' " src="/assets/'. $donnees['file_name'] . '">
                  </div>
                </div>
                <div class="col-lg-2 button-modal">
                      <button type="button" class="btn btn btn-menu btn-next">Suivante</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      ';
    }
  }

  public function showAllBaseRepertories()
  {
    $this->showRepContent(null);
  }

  public function createThumbNail($source_path, $destination_path)
  {
    $source = imagecreatefromjpeg($source_path);
    $destination = imagecreatetruecolor(350, 200);

    $largeur_source = imagesx($source);
    $hauteur_source = imagesy($source);
    $largeur_destination = imagesx($destination);
    $hauteur_destination = imagesy($destination);

    imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
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
      echo '
      <div class="col-md-4">
          <a href="?rep='. $donnees['id'] .'" class="rep-base">
              <span class="fa fa-folder-open fa-4x"></span>
              <h4 class="name-rep">' . $donnees['name'] . '</h4>
          </a>
        </div>';
    }
    if ($reponse->rowCount()==0) {
      echo '
      <div class="row"><div class="col-md-12"><b>Il n\'y a pas de sous répertoires...</b></div></div>';
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
