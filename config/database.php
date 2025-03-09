<?php
// Paramètres de connexion
$host = "localhost";
$db   = "gestion_app";
$user = "admin";
$pass = "passer123";

// Création de la connexion avec PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
  
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
