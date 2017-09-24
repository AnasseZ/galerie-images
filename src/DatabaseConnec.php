<?php

class DatabaseConnec {

  private static $instance = NULL;
  private $connection;

  private $user = 'root';
  private $pass = 'root';

  private function __construct()
  {
    try {
        $this->connection = new PDO('mysql:host=localhost;dbname=gallery_photos', $this->user, $this->pass);

        echo 'Je suis dans le constructeur <br>';
        $this->loadFixtures();

        // $this->connection->query("DROP TABLE Pictures");
        // Ferme la connexion à la base.
        // $this->connection = null;
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
  }

  public static function getInstance()
  {
    echo self::$instance;

    if (!self::$instance) {
      echo 'Classe n existe pas, je la cree.';
      self::$instance = new DatabaseConnec();
    }

    return self::$instance;
  }

  public function getConnection()
  {
    return $this->connection;
  }

  public function loadFixtures()
  {
    $request = "CREATE TABLE IF NOT EXISTS Pictures (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pic_path VARCHAR(200) NOT NULL,
    title VARCHAR(200) NOT NULL,
    description VARCHAR(200) NOT NULL
    )";

    $br = '<br>';
    if($this->connection->query($request)){
      echo "Table created or already exists." . $br . $br;
    } else {
      echo "Error creating table." . $br . $br;
    }

    $insertQueries = "INSERT INTO Pictures (pic_path, title, description)
    VALUES ('backend/img/avion', 'Airbus 300', 'Un avion allant au Mexique.'),
    ('backend/img/voiture', 'BMW 2018', 'La voiture innovante de l\'année.'),
    ('backend/img/moto', 'Yamaha X', 'Moto la plus rapide au monde.'),
    ('backend/img/velo', 'Decatbic', 'Vélo sans pédale.')
    ";

    $this->connection->query($insertQueries);
  }

  public function affiche() {
    $br = '<br>';

    $selectQuery = "SELECT * FROM Pictures";
    $reponse = $this->connection->query($selectQuery);
    while ($donnees = $reponse->fetch()) {
      echo 'Id : ' . $donnees['id'] . $br;
      echo 'Path : ' . $donnees['pic_path'] . $br;
      echo 'Title : ' . $donnees['title'] . $br;
      echo 'Description : ' . $donnees['description'] . $br . $br;
    }
  }
}
