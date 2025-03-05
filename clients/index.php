<?php
// clients/index.php
require_once '../config/database.php';  // Ajustez le chemin si nécessaire

try {
    // Récupérer tous les clients
    $stmt = $pdo->query("SELECT client_id, nom, prenom, email, telephone, adresse FROM clients");
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des clients : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Clients</title>
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
    <h1>Liste des Clients</h1>
    <a href="create.php" class="btn-create">+ Ajouter un client</a>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Adresse</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($clients)) : ?>
            <?php foreach ($clients as $cli) : ?>
                <tr>
                    <td><?= $cli['client_id'] ?></td>
                    <td><?= htmlspecialchars($cli['nom']) ?></td>
                    <td><?= htmlspecialchars($cli['prenom']) ?></td>
                    <td><?= htmlspecialchars($cli['email']) ?></td>
                    <td><?= htmlspecialchars($cli['telephone']) ?></td>
                    <td><?= htmlspecialchars($cli['adresse']) ?></td>
                    <td>
                        <!-- Liens pour modifier et supprimer -->
                        <a href="update.php?id=<?= $cli['client_id'] ?>">Modifier</a> | 
                        <a href="delete.php?id=<?= $cli['client_id'] ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
                           Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7">Aucun client trouvé.</td></tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
