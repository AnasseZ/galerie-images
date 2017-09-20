<?php

$user = 'root';
$pass = 'root';

try {
    $dbh = new PDO('mysql:host=localhost;dbname=gallery_photos', $user, $pass);

    $request = "CREATE TABLE IF NOT EXISTS Pictures (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pic_path VARCHAR(200) NOT NULL,
    title VARCHAR(200) NOT NULL,
    description VARCHAR(200) NOT NULL
    )";

    $br = '<br>';
    if($dbh->query($request)){
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

    $selectQuery = "SELECT * FROM Pictures";

    $dbh->query($insertQueries);
    $reponse = $dbh->query($selectQuery);
    while ($donnees = $reponse->fetch()) {
      echo 'Id : ' . $donnees['id'] . $br;
      echo 'Path : ' . $donnees['pic_path'] . $br;
      echo 'Title : ' . $donnees['title'] . $br;
      echo 'Description : ' . $donnees['description'] . $br . $br;
    }

    $dbh->query("DROP TABLE Pictures");
    // Ferme la connexion à la base.
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage();
    die();
}

?>
