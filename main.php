<?php

$user = 'root';
$pass = 'root';

try {
    $dbh = new PDO('mysql:host=localhost;dbname=gallery_photos', $user, $pass);

    echo 'Ca marche';

    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage();
    die();
}

?>
