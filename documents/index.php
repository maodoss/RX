<?php
// documents/index.php
require_once '../config/database.php';  // Ajustez le chemin si nécessaire

try {
    // Récupérer tous les documents en ne sélectionnant que les champs nécessaires
    $stmt = $pdo->query("SELECT document_id, titre, description_fichier, chemin_fichier, date_upload 
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
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4; 
            margin: 0; 
            padding: 20px; 
        }
        h1 { 
            color: #333; 
        }
        .container { 
            background: #fff; 
            padding: 20px; 
            border-radius: 5px; 
            margin: 40px auto; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            max-width: 1000px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left;
        }
        th { 
            background: #f0f0f0; 
        }
        a { 
            text-decoration: none; 
            color: #1e88e5; 
        }
        a:hover { 
            text-decoration: underline; 
        }
        .btn-create {
            display: inline-block; 
            margin-top: 10px; 
            padding: 8px 12px; 
            background: #1e88e5; 
            color: #fff; 
            border-radius: 4px;
        }
        .btn-create:hover { 
            background: #1565c0; 
        }

        /* Barre de navigation */
        .navbar {
            background-color: #2c3e50;
            padding: 10px;
            margin-bottom: 20px;
        }
        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .navbar li {
            margin-right: 20px;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<!-- Barre de navigation -->
<nav class="navbar">
    <ul>
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="../employe/index.php">Employés</a></li>
        <li><a href="../clients/index.php">Clients</a></li>
        <li><a href="../documents/index.php">Documents</a></li>
    </ul>
</nav>
<!-- Fin de la barre de navigation -->

<div class="container">
    <h1>Liste des Documents</h1>
    <a href="create.php" class="btn-create">+ Ajouter un document</a>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Chemin</th>
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
                    <td><?= $doc['date_upload'] ?></td>
                    <td>
                        <a href="update.php?id=<?= $doc['document_id'] ?>">Modifier</a> | 
                        <a href="delete.php?id=<?= $doc['document_id'] ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                           Supprimer
                        </a> | 
                        <a class="view-link" href="<?= htmlspecialchars($doc['chemin_fichier']) ?>" target="_blank">Visualiser</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Aucun document trouvé.</td></tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
