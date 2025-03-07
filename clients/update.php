<?php
// clients/update.php
require_once '../config/database.php'; // Ajustez le chemin si nécessaire

// Vérifier si un ID est passé en paramètre GET
if (!isset($_GET['id'])) {
    die("Paramètre 'id' manquant dans l'URL.");
}

$client_id = intval($_GET['id']);

// Récupérer les données du client pour les afficher dans le formulaire
try {
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE client_id = :id");
    $stmt->execute([':id' => $client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        die("Client non trouvé.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération du client : " . $e->getMessage());
}

// Variable pour message de feedback
$message = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = !empty($_POST['nom']) ? trim($_POST['nom']) : null;
    $prenom    = !empty($_POST['prenom']) ? trim($_POST['prenom']) : null;
    $email     = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $telephone = !empty($_POST['telephone']) ? trim($_POST['telephone']) : null;
    $adresse   = !empty($_POST['adresse']) ? trim($_POST['adresse']) : null;

    try {
        $sql = "UPDATE clients
                SET nom = :nom,
                    prenom = :prenom,
                    email = :email,
                    telephone = :telephone,
                    adresse = :adresse
                WHERE client_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom'       => $nom,
            ':prenom'    => $prenom,
            ':email'     => $email,
            ':telephone' => $telephone,
            ':adresse'   => $adresse,
            ':id'        => $client_id
        ]);

        $message = "Mise à jour effectuée avec succès !";
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
    <title>Modifier un Client</title>
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
    <h1>Modifier un Client</h1>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($client['nom']); ?>">

        <label>Prénom :</label>
        <input type="text" name="prenom" required value="<?php echo htmlspecialchars($client['prenom']); ?>">

        <label>Email :</label>
        <input type="email" name="email" required value="<?php echo htmlspecialchars($client['email']); ?>">

        <label>Téléphone :</label>
        <input type="text" name="telephone" value="<?php echo htmlspecialchars($client['telephone']); ?>">

        <label>Adresse :</label>
        <input type="text" name="adresse" value="<?php echo htmlspecialchars($client['adresse']); ?>">

        <button type="submit">Mettre à jour</button>
    </form>
</div>
</body>
</html>
