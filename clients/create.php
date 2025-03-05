<?php
// clients/create.php
require_once '../config/database.php'; // Ajustez le chemin si nécessaire

// Initialisation d'un message (optionnel)
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom       = !empty($_POST['nom']) ? trim($_POST['nom']) : null;
    $prenom    = !empty($_POST['prenom']) ? trim($_POST['prenom']) : null;
    $email     = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $telephone = !empty($_POST['telephone']) ? trim($_POST['telephone']) : null;
    $adresse   = !empty($_POST['adresse']) ? trim($_POST['adresse']) : null;

    try {
        // Préparation de la requête d'insertion
        $sql = "INSERT INTO clients (nom, prenom, email, telephone, adresse)
                VALUES (:nom, :prenom, :email, :telephone, :adresse)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom'       => $nom,
            ':prenom'    => $prenom,
            ':email'     => $email,
            ':telephone' => $telephone,
            ':adresse'   => $adresse
        ]);

        // Succès : on peut soit afficher un message, soit rediriger
        $message = "Client ajouté avec succès !";
        // header("Location: index.php"); 
        // exit;

    } catch (PDOException $e) {
        $message = "Erreur lors de l'ajout : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Client</title>
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
        input[type="email"] {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 20px;
            padding: 10px 15px;
            background: #1e88e5;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover {
            background: #1565c0;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Ajouter un Client</h1>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <form action="" method="post">
        <label>Nom :</label>
        <input type="text" name="nom">

        <label>Prénom :</label>
        <input type="text" name="prenom" required>

        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Téléphone :</label>
        <input type="text" name="telephone">

        <label>Adresse :</label>
        <input type="text" name="adresse">

        <button type="submit">Ajouter</button>
    </form>
</div>
</body>
</html>
