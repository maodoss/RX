<?php
// employes/index.php
require_once '../config/database.php';  // Ajustez le chemin si nécessaire

try {
    // Récupérer tous les employés
    $stmt = $pdo->query("SELECT employe_id, nom, prenom, email, telephone, poste, salaire FROM employes");
    $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des employés : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Employés</title>
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

<!-- Début de la barre de navigation -->
<nav class="navbar">
    <ul>
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="../employe/index.php">Employés</a></li>
        <li><a href="../clients/index.php">Clients</a></li>
        <li><a href="../documents/index.php">Documents</a></li>
    </ul>
</nav>

<style>
    .navbar {
        background-color: #2c3e50;
        padding: 10px;
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
<!-- Fin de la barre de navigation -->

<div class="container">
    <h1>Liste des Employés</h1>
    <a href="create.php" class="btn-create">+ Ajouter un employé</a>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Poste</th>
            <th>Salaire</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($employes)) : ?>
            <?php foreach ($employes as $emp) : ?>
                <tr>
                    <td><?= $emp['employe_id'] ?></td>
                    <td><?= htmlspecialchars($emp['nom']) ?></td>
                    <td><?= htmlspecialchars($emp['prenom']) ?></td>
                    <td><?= htmlspecialchars($emp['email']) ?></td>
                    <td><?= htmlspecialchars($emp['telephone']) ?></td>
                    <td><?= htmlspecialchars($emp['poste']) ?></td>
                    <td><?= $emp['salaire'] ?></td>
                    <td>
                        <!-- Liens pour modifier et supprimer (si vous avez les pages correspondantes) -->
                        <a href="update.php?id=<?= $emp['employe_id'] ?>">Modifier</a> | 
                        <a href="delete.php?id=<?= $emp['employe_id'] ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                           Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8">Aucun employé trouvé.</td></tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
