<?php

include("DatabaseConnec.php");

$instance = DatabaseConnec::getInstance();
$instance->affiche();
