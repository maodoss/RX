<?php
// documents/update.php
require_once '../config/database.php';  // Ajustez le chemin si nécessaire

if (!isset($_GET['id'])) {
    die("Paramètre 'id' manquant.");
}

$document_id = intval($_GET['id']);

// Récupération du document
try {
    $stmt = $pdo->prepare("SELECT * FROM documents WHERE document_id = :id");
    $stmt->execute([':id' => $document_id]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$document) {
        die("Document introuvable.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération du document : " . $e->getMessage());
}

// Message de feedback
$message = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre       = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
    $description = !empty($_POST['description_fichier']) ? trim($_POST['description_fichier']) : null;
    $chemin      = !empty($_POST['chemin_fichier']) ? trim($_POST['chemin_fichier']) : null;

    try {
        $sql = "UPDATE documents
                SET titre = :titre,
                    description_fichier = :description,
                    chemin_fichier = :chemin
                WHERE document_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titre'       => $titre,
            ':description' => $description,
            ':chemin'      => $chemin,
            ':id'          => $document_id
        ]);

        $message = "Document mis à jour avec succès !";
        // Redirection possible, par exemple :
        // header("Location: index.php");
        // exit;
    } catch (PDOException $e) {
        $message = "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0; 
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            margin-top: 0;
            color: #333;
        }
        .message {
            margin: 15px 0;
            color: green;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        textarea {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 20px;
            padding: 10px 15px;
            background: #e67e22;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover {
            background: #d35400;
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
        /* Lien pour visualiser le document */
        .view-link {
            margin-top: 15px;
            display: inline-block;
            padding: 8px 12px;
            background: #1e88e5;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
        }
        .view-link:hover {
            background: #1565c0;
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

<div class="container">
    <h1>Modifier un Document</h1>
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label>Titre :</label>
        <input type="text" name="titre" required value="<?= htmlspecialchars($document['titre']) ?>">

        <label>Description :</label>
        <textarea name="description_fichier" rows="3"><?= htmlspecialchars($document['description_fichier']) ?></textarea>

        <label>Chemin du fichier :</label>
        <input type="text" name="chemin_fichier" required value="<?= htmlspecialchars($document['chemin_fichier']) ?>">

        <button type="submit">Mettre à jour</button>
    </form>
    <!-- Lien pour visualiser le document -->
    <a class="view-link" href="<?= htmlspecialchars($document['chemin_fichier']) ?>" target="_blank">Visualiser le document</a>
</div>
</body>
</html>
