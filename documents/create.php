<?php
// documents/create.php
require_once '../config/database.php';  // Ajustez si nécessaire

$message = "";
$uploadDir = '../uploads/'; // Ce dossier ne sera plus utilisé pour stocker les fichiers

// Paramètres FTP
$ftp_server = "192.168.1.11";
$ftp_user = "ftpuser1";
$ftp_password = "passer";
$ftp_upload_dir = "/home/ftpuser1/ftp/"; // Répertoire distant où stocker les fichiers

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification de l'upload du fichier
    if (isset($_FILES['chemin_fichier']) && $_FILES['chemin_fichier']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['chemin_fichier']['tmp_name'];
        $originalFileName = $_FILES['chemin_fichier']['name'];
        
        // Connexion au serveur FTP
        $ftp_conn = ftp_connect($ftp_server);
        
        if ($ftp_conn && ftp_login($ftp_conn, $ftp_user, $ftp_password)) {
            ftp_pasv($ftp_conn, true); // Activation du mode passif si nécessaire
            $remoteFile = $ftp_upload_dir . $originalFileName;
            
            // Envoi du fichier via FTP
            if (ftp_put($ftp_conn, $remoteFile, $tmpName, FTP_BINARY)) {
                $message = "Document transféré avec succès sur le serveur FTP.";
            } else {
                $message = "Erreur : Impossible de transférer le fichier vers le serveur FTP.";
            }
            
            // Fermeture de la connexion FTP
            ftp_close($ftp_conn);
        } else {
            $message = "Erreur : Connexion au serveur FTP échouée.";
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
    <form action="" method="post" enctype="multipart/form-data">
        <label>Fichier à uploader :</label>
        <input type="file" name="chemin_fichier" required>
        <button type="submit">Ajouter</button>
    </form>
</div>
</body>
</html>
