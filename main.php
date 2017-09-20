<?php

$user = 'root';
$pass = 'root';

try {
    $dbh = new PDO('mysql:host=localhost;dbname=gallery_photos', $user, $pass);

    $request = "CREATE TABLE IF NOT EXISTS Pictures (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pic_path VARCHAR(200) NOT NULL,
    description VARCHAR(200) NOT NULL
    )";

    $br = '<br>';
    if($dbh->query($request)){
      echo "Table created or already exists." . $br;
    } else {
      echo "Error creating table." . $br;
    }

    $insertQueries = "INSERT INTO Pictures (pic_path, description)
    VALUES ('backend/img/avion', 'Un avion allant au Mexique.')
    ";

    $selectQuery = "SELECT * FROM Pictures";

    $dbh->query($insertQueries);
    $reponse = $dbh->query($selectQuery);
    while ($donnees = $reponse->fetch()) {
      echo $donnees['id'] . $br;
      echo $donnees['pic_path'] . $br;
      echo $donnees['description'] . $br;
    }

    // $dbh->query("DROP TABLE Pictures");
    // Ferme la connexion à la base.
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage();
    die();
}

?>
