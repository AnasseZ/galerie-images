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
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage();
        die();
    }
  }

  public static function getInstance()
  {
    echo self::$instance;

    if (!self::$instance) {
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
    $this->connection->query($request);

    $insertQueries = "INSERT INTO Pictures (pic_path, title, description)
    VALUES ('/assets/bmw.jpg', 'Airbus 300', 'Un avion allant au Mexique.'),
    ('/assets/bmw.jpg', 'BMW 2018', 'La voiture innovante de l\'année.'),
    ('/assets/bmw.jpg', 'Yamaha X', 'Moto la plus rapide au monde.'),
    ('/assets/bmw.jpg', 'Decatbic', 'Vélo sans pédale.')
    ";

    $this->connection->query($insertQueries);
  }

  public function affiche()
  {
    $selectQuery = "SELECT * FROM Pictures";
    $reponse = $this->connection->query($selectQuery);

    while ($donnees = $reponse->fetch()) {
      echo '<div class="col-md-4"><div class="thumbnail">';
      echo '<img src="' . $donnees['pic_path'] . '" alt="Lights" style="width:100%">';
      echo '<div class="caption"><p>' . $donnees['description'] . '</p></div></div></div>';
    }
  }
}
