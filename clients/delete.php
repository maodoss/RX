<?php
// clients/delete.php
require_once '../config/database.php'; // Ajustez le chemin si nécessaire

// Vérifier si un ID est passé
if (!isset($_GET['id'])) {
    die("Paramètre 'id' manquant !");
}

$client_id = intval($_GET['id']);

// Suppression du client
try {
    $stmt = $pdo->prepare("DELETE FROM clients WHERE client_id = :id");
    $stmt->execute([':id' => $client_id]);

    // Redirection vers la liste des clients
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
