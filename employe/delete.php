<?php
// delete_employe.php
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $sql = "DELETE FROM employes WHERE employe_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        echo "Employé supprimé avec succès !";
        // Redirection possible
        // header('Location: list_employes.php');
        // exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression : " . $e->getMessage();
    }
} else {
    echo "Paramètre ID manquant !";
}
?>
