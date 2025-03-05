<?php
// documents/create.php
require_once '../config/database.php';  // Ajustez le chemin si nécessaire

$message = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données
    $titre       = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
    $description = !empty($_POST['description_fichier']) ? trim($_POST['description_fichier']) : null;
    $chemin      = !empty($_POST['chemin_fichier']) ? trim($_POST['chemin_fichier']) : null;
    $type        = !empty($_POST['type_fichier']) ? trim($_POST['type_fichier']) : null;
    $relatedTo   = !empty($_POST['related_to']) ? trim($_POST['related_to']) : null;   // 'employee' ou 'client'
    $relatedId   = !empty($_POST['related_id']) ? intval($_POST['related_id']) : 0;    // ID correspondant
    $uploadedBy  = !empty($_POST['uploaded_by']) ? intval($_POST['uploaded_by']) : null;  

    try {
        $sql = "INSERT INTO documents (titre, description_fichier, chemin_fichier, type_fichier, related_to, related_id, uploaded_by)
                VALUES (:titre, :description, :chemin, :type, :related_to, :related_id, :uploaded_by)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titre'       => $titre,
            ':description' => $description,
            ':chemin'      => $chemin,
            ':type'        => $type,
            ':related_to'  => $relatedTo,
            ':related_id'  => $relatedId,
            ':uploaded_by' => $uploadedBy
        ]);

        $message = "Document ajouté avec succès !";
        // Redirection possible
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
    <title>Ajouter un Document</title>
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
        textarea,
        select {
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
    <h1>Ajouter un Document</h1>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label>Titre :</label>
        <input type="text" name="titre" required>

        <label>Description :</label>
        <textarea name="description_fichier" rows="3"></textarea>

        <label>Chemin du fichier :</label>
        <input type="text" name="chemin_fichier" required>

        <label>Type de fichier :</label>
        <input type="text" name="type_fichier" placeholder="Ex: pdf, docx, image...">

        <label>Related To :</label>
        <select name="related_to" required>
            <option value="">-- Sélectionnez --</option>
            <option value="employee">employee</option>
            <option value="client">client</option>
        </select>

        <label>Related ID :</label>
        <input type="number" name="related_id" min="1">

        <label>Uploaded By (employe_id) :</label>
        <input type="number" name="uploaded_by" min="1">

        <button type="submit">Ajouter</button>
    </form>
</div>
</body>
</html>
