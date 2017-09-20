<?php

$user = 'root';
$pass = 'root';

try {
    $dbh = new PDO('mysql:host=localhost;dbname=gallery_photos', $user, $pass);

    echo 'Ca marche' . '<br>';

    $request = "CREATE TABLE IF NOT EXISTS Photos (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    chemin VARCHAR(200) NOT NULL,
    description VARCHAR(200) NOT NULL
    )";

    if($dbh->query($request)){
      echo "Table créée ou bien déjà existante.";
    } else {
      echo "Error creating table: ";
    }

    $insertQueries = "INSERT INTO Photos (chemin, description)
    VALUES ('backend/img/avion', 'Un avion allant au Mexique.')
    ";

    $selectQuery = "SELECT * FROM Photos";

    $dbh->query($insertQueries);
    $reponse = $dbh->query($selectQuery);
    while ($donnees = $reponse->fetch()) {
      echo $donnees['id'] . '<br>';
      echo $donnees['chemin'] . '<br>';
      echo $donnees['description'] . '<br>';
    }


    // Ferme la connexion à la base.
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage();
    die();
}

?>
