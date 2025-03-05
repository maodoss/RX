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
    $type        = !empty($_POST['type_fichier']) ? trim($_POST['type_fichier']) : null;
    $relatedTo   = !empty($_POST['related_to']) ? trim($_POST['related_to']) : null;
    $relatedId   = !empty($_POST['related_id']) ? intval($_POST['related_id']) : 0;
    $uploadedBy  = !empty($_POST['uploaded_by']) ? intval($_POST['uploaded_by']) : null;

    try {
        $sql = "UPDATE documents
                SET titre = :titre,
                    description_fichier = :description,
                    chemin_fichier = :chemin,
                    type_fichier = :type,
                    related_to = :related_to,
                    related_id = :related_id,
                    uploaded_by = :uploaded_by
                WHERE document_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titre'       => $titre,
            ':description' => $description,
            ':chemin'      => $chemin,
            ':type'        => $type,
            ':related_to'  => $relatedTo,
            ':related_id'  => $relatedId,
            ':uploaded_by' => $uploadedBy,
            ':id'          => $document_id
        ]);

        $message = "Document mis à jour avec succès !";
        // Redirection possible
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
<div class="container">
    <h1>Modifier un Document</h1>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label>Titre :</label>
        <input type="text" name="titre" required 
               value="<?php echo htmlspecialchars($document['titre']); ?>">

        <label>Description :</label>
        <textarea name="description_fichier" rows="3"><?php 
            echo htmlspecialchars($document['description_fichier']); 
        ?></textarea>

        <label>Chemin du fichier :</label>
        <input type="text" name="chemin_fichier" required
               value="<?php echo htmlspecialchars($document['chemin_fichier']); ?>">

        <label>Type de fichier :</label>
        <input type="text" name="type_fichier"
               value="<?php echo htmlspecialchars($document['type_fichier']); ?>">

        <label>Related To :</label>
        <select name="related_to" required>
            <option value="">-- Sélectionnez --</option>
            <option value="employee" <?php 
                if ($document['related_to'] === 'employee') echo 'selected'; 
            ?>>employee</option>
            <option value="client" <?php 
                if ($document['related_to'] === 'client') echo 'selected'; 
            ?>>client</option>
        </select>

        <label>Related ID :</label>
        <input type="number" name="related_id"
               value="<?php echo htmlspecialchars($document['related_id']); ?>">

        <label>Uploaded By (employe_id) :</label>
        <input type="number" name="uploaded_by"
               value="<?php echo htmlspecialchars($document['uploaded_by']); ?>">

        <button type="submit">Mettre à jour</button>
    </form>
</div>
</body>
</html>
