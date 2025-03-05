<?php
// Paramètres de connexion
$host = "localhost";
$db   = "gestion_app";
$user = "root";
$pass = "";

// Création de la connexion avec PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Configuration pour que PDO lève des exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
