<?php
// documents/index.php
require_once '../config/database.php';  // Ajustez le chemin si nécessaire

try {
    // Récupérer tous les documents
    $stmt = $pdo->query("SELECT document_id, titre, description_fichier, chemin_fichier, type_fichier, related_to, related_id, date_upload 
                         FROM documents
                         ORDER BY document_id DESC");
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des documents : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Documents</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        h1 { color: #333; }
        .container { background: #fff; padding: 20px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f0f0f0; }
        a { text-decoration: none; color: #1e88e5; }
        a:hover { text-decoration: underline; }
        .btn-create {
            display: inline-block; 
            margin-top: 10px; 
            padding: 8px 12px; 
            background: #1e88e5; 
            color: #fff; 
            border-radius: 4px;
        }
        .btn-create:hover { background: #1565c0; }
    </style>
</head>
<body>
<div class="container">
    <h1>Liste des Documents</h1>
    <a href="create.php" class="btn-create">+ Ajouter un document</a>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Chemin</th>
            <th>Type</th>
            <th>Lié à (related_to)</th>
            <th>Identifiant (related_id)</th>
            <th>Date d'upload</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($documents)) : ?>
            <?php foreach ($documents as $doc) : ?>
                <tr>
                    <td><?= $doc['document_id'] ?></td>
                    <td><?= htmlspecialchars($doc['titre']) ?></td>
                    <td><?= htmlspecialchars($doc['description_fichier']) ?></td>
                    <td><?= htmlspecialchars($doc['chemin_fichier']) ?></td>
                    <td><?= htmlspecialchars($doc['type_fichier']) ?></td>
                    <td><?= htmlspecialchars($doc['related_to']) ?></td>
                    <td><?= $doc['related_id'] ?></td>
                    <td><?= $doc['date_upload'] ?></td>
                    <td>
                        <a href="update.php?id=<?= $doc['document_id'] ?>">Modifier</a> | 
                        <a href="delete.php?id=<?= $doc['document_id'] ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                           Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="9">Aucun document trouvé.</td></tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
