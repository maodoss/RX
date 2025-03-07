<?php
// documents/create.php
require_once '../config/database.php';  // Ajustez le chemin si nécessaire

$message = "";
$uploadDir = '../uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération de la description saisie par l'utilisateur
    $description = !empty($_POST['description_fichier']) ? trim($_POST['description_fichier']) : null;

    // Vérification de l'upload du fichier
    if (isset($_FILES['chemin_fichier']) && $_FILES['chemin_fichier']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['chemin_fichier']['tmp_name'];
        $originalFileName = $_FILES['chemin_fichier']['name'];  // Titre du document
        $fileType = $_FILES['chemin_fichier']['type'];

        // Pour éviter les collisions, on peut préfixer le nom avec un identifiant unique
        $uniquePrefix = date('YmdHis') . '_' . uniqid();
        $fileName = $uniquePrefix . '_' . $originalFileName;
        $finalPath = $uploadDir . $fileName;

        // Déplacer le fichier dans le dossier uploads/
        if (move_uploaded_file($tmpName, $finalPath)) {
            try {
                // Insertion en base de données
                // Le titre est le nom original du fichier, et le chemin est le chemin final.
                $sql = "INSERT INTO documents (titre, description_fichier, chemin_fichier)
                        VALUES (:titre, :description, :chemin)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':titre'       => $originalFileName,
                    ':description' => $description,
                    ':chemin'      => $finalPath
                ]);

                $message = "Document ajouté avec succès !";
                // Vous pouvez rediriger si nécessaire, par exemple :
                // header("Location: index.php");
                // exit;
            } catch (PDOException $e) {
                $message = "Erreur lors de l'ajout en base : " . $e->getMessage();
            }
        } else {
            $message = "Erreur : Impossible de déplacer le fichier dans $uploadDir.";
        }
    } else {
        $message = "Aucun fichier sélectionné ou erreur lors de l'upload.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0; 
            padding: 20px;
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
        /* Conteneur principal */
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
        /* Styles du formulaire */
        form {
            display: flex;
            flex-direction: column;
        }
        form label {
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }
        form input[type="file"],
        form textarea {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form textarea {
            resize: vertical;
        }
        form button {
            margin-top: 20px;
            padding: 10px 15px;
            background: #1e88e5;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        form button:hover {
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
    <h1>Ajouter un Document</h1>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Formulaire d'upload avec description -->
    <form action="" method="post" enctype="multipart/form-data">
        <label>Fichier à uploader :</label>
        <input type="file" name="chemin_fichier" required>

        <label>Description :</label>
        <textarea name="description_fichier" rows="3"></textarea>

        <button type="submit">Ajouter</button>
    </form>
</div>
</body>
</html>
