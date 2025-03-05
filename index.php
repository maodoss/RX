<?php
// index.php
require_once 'config/database.php';  // Inclure ici la connexion PDO (variable $pdo)

// Initialisation des compteurs
$employe_count = 0;
$client_count = 0;
$document_count = 0;

// Récupération du nombre d'employés
try {
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM employes");
    $employe_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    // En cas d'erreur, on peut laisser la valeur à 0
}

// Récupération du nombre de clients
try {
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM clients");
    $client_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    // idem
}

// Récupération du nombre de documents
try {
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM documents");
    $document_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    // idem
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Système de Gestion</title>
    <style>
        /* --- Styles généraux --- */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f0f0f0;
        }
        header {
            background: #2c3e50;
            color: #fff;
            padding: 15px;
        }
        header h1 {
            margin: 0;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* --- Section d'accueil --- */
        .welcome {
            text-align: center;
            margin-bottom: 40px;
        }
        .welcome h2 {
            margin-bottom: 10px;
        }
        .welcome p {
            color: #666;
        }

        /* --- Tableau de bord (les cartes) --- */
        .dashboard {
            display: flex;
            gap: 20px;
            flex-wrap: wrap; /* pour s'adapter sur petits écrans */
        }
        .card {
            flex: 1 1 250px; /* largeur minimale de 250px */
            background: #eee;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
        }
        .card h3 {
            margin: 0 0 10px;
        }
        .count {
            font-size: 2em;
            font-weight: bold;
            margin: 10px 0;
            color: #333;
        }
        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background: #2c3e50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .card a:hover {
            background: #34495e;
        }

        /* --- Actions rapides --- */
        .actions {
            margin-top: 40px;
        }
        .actions h3 {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .actions .action-list {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .action-box {
            flex: 1 1 250px;
            background: #fafafa;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        .action-box a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
        }
        .action-box a:hover {
            color: #1c5980;
        }

        /* --- Footer --- */
        footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<header>
    <h1>Système de Gestion</h1>
</header>

<div class="container">
    <!-- Section d'accueil -->
    <div class="welcome">
        <h2>Bienvenue dans votre Système de Gestion</h2>
        <p>Gérez facilement vos employés, clients et documents.</p>
    </div>

    <!-- Tableau de bord (cards) -->
    <div class="dashboard">
        <div class="card">
            <h3>Employés</h3>
            <div class="count"><?php echo $employe_count; ?></div>
            <a href="employe/index.php">Gérer</a>
        </div>
        <div class="card">
            <h3>Clients</h3>
            <div class="count"><?php echo $client_count; ?></div>
            <a href="clients/index.php">Gérer</a>
        </div>
        <div class="card">
            <h3>Documents</h3>
            <div class="count"><?php echo $document_count; ?></div>
            <a href="documents/index.php">Gérer</a>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="actions">
        <h3>Actions rapides</h3>
        <div class="action-list">
            <div class="action-box">
                <h4>Nouvel employé</h4>
                <p>Ajouter un nouvel employé dans la base de données.</p>
                <a href="employe/create.php">Créer</a>
            </div>
            <div class="action-box">
                <h4>Nouveau client</h4>
                <p>Ajouter un nouveau client dans la base de données.</p>
                <a href="clients/create.php">Créer</a>
            </div>
            <div class="action-box">
                <h4>Nouveau document</h4>
                <p>Ajouter un document lié à un employé ou un client.</p>
                <a href="documents/create.php">Créer</a>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer>
        &copy; <?php echo date('Y'); ?> - Mon Système de Gestion
    </footer>
</div>

</body>
</html>
