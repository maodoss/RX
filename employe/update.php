<?php
// update_employe.php
require_once '../config/database.php';

// 1) Récupérer l'employé à modifier
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM employes WHERE employe_id = :id");
    $stmt->execute([':id' => $id]);
    $employe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employe) {
        die("Employé non trouvé !");
    }
} else {
    die("Paramètre ID manquant !");
}

// 2) Mise à jour des données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = intval($_POST['employe_id']);
    $nom       = $_POST['nom']       ?? '';
    $prenom    = $_POST['prenom']    ?? '';
    $email     = $_POST['email']     ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $poste     = $_POST['poste']     ?? '';
    $salaire   = $_POST['salaire']   ?? 0;

    try {
        $sql = "UPDATE employes
                SET nom = :nom,
                    prenom = :prenom,
                    email = :email,
                    telephone = :telephone,
                    poste = :poste,
                    salaire = :salaire
                WHERE employe_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom'       => $nom,
            ':prenom'    => $prenom,
            ':email'     => $email,
            ':telephone' => $telephone,
            ':poste'     => $poste,
            ':salaire'   => $salaire,
            ':id'        => $id
        ]);

        echo "Mise à jour effectuée avec succès !";
        // Redirection ou lien de retour
        // header('Location: list_employes.php');
        // exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Modifier un employé</title>
    <style>
        /* Styles pour la barre de navigation */
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

        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Styles du formulaire */
        form {
            display: flex;
            flex-direction: column;
        }
        form label {
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="number"] {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }
        form button {
            margin-top: 20px;
            padding: 12px;
            background-color: #1e88e5;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
        }
        form button:hover {
            background-color: #1565c0;
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
    <h1>Modifier un employé</h1>
    <form action="update.php?id=<?= $employe['employe_id'] ?>" method="post">
        <input type="hidden" name="employe_id" value="<?= $employe['employe_id'] ?>">

        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($employe['nom']) ?>" required>

        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($employe['prenom']) ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($employe['email']) ?>" required>

        <label>Téléphone :</label>
        <input type="text" name="telephone" value="<?= htmlspecialchars($employe['telephone']) ?>">

        <label>Poste :</label>
        <input type="text" name="poste" value="<?= htmlspecialchars($employe['poste']) ?>">

        <label>Salaire :</label>
        <input type="number" step="0.01" name="salaire" value="<?= $employe['salaire'] ?>">

        <button type="submit">Enregistrer</button>
    </form>
</div>

</body>
</html>
