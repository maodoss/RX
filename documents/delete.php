<?php
// documents/delete.php
require_once '../config/database.php';  // Ajustez le chemin si nécessaire

if (!isset($_GET['id'])) {
    die("Paramètre 'id' manquant !");
}

$document_id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("DELETE FROM documents WHERE document_id = :id");
    $stmt->execute([':id' => $document_id]);

    // Redirection vers la liste des documents
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
