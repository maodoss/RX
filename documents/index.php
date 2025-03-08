<?php
// documents/index.php

// Paramètres FTP
$ftp_server = "192.168.1.11";
$ftp_user = "ftpuser1";
$ftp_password = "passer";
$ftp_upload_dir = "/home/ftpuser1/ftp"; // Répertoire distant où stocker les fichiers

$message = "";

// Connexion au serveur FTP
$ftp_conn = ftp_connect($ftp_server);
if (!$ftp_conn) {
    die("Échec de la connexion au serveur FTP.");
}
if (!ftp_login($ftp_conn, $ftp_user, $ftp_password)) {
    die("Échec de l'authentification FTP.");
}


if ($ftp_conn && ftp_login($ftp_conn, $ftp_user, $ftp_password)) {

    ftp_pasv($ftp_conn, true); // Mode passif si nécessaire
    echo"connexion reussie";
    // Récupération de la liste des fichiers du répertoire
    $files = ftp_nlist($ftp_conn, $ftp_upload_dir);
    
    if ($files === false) {
        $message = "Impossible de récupérer la liste des fichiers.";
        $files = [];
    }
} else {
    die("Erreur : Connexion au serveur FTP échouée.");
}

// Suppression d'un fichier si demandé
if (isset($_GET['delete'])) {
    $fileToDelete = $ftp_upload_dir . basename($_GET['delete']);
    if (ftp_delete($ftp_conn, $fileToDelete)) {
        $message = "Fichier supprimé avec succès.";
    } else {
        $message = "Erreur lors de la suppression du fichier.";
    }
    header("Location: index.php");
    exit;
}

// Fermeture de la connexion FTP
ftp_close($ftp_conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Documents</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4; 
            margin: 0; 
            padding: 20px; 
        }
        h1 { color: #333; }
        .container { 
            background: #fff; 
            padding: 20px; 
            border-radius: 5px; 
            margin: 40px auto; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            max-width: 1000px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left;
        }
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
<!-- Fin de la barre de navigation -->

<div class="container">
    <h1>Liste des Documents</h1>
    <a href="create.php" class="btn-create">+ Uploader un document</a>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <table>
        <tr>
            <th>Nom du fichier</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($files)) : ?>
            <?php foreach ($files as $file) : ?>
                <tr>
                    <td><?= htmlspecialchars(basename($file)) ?></td>
                    <td>
                        <a href="ftp://<?= $ftp_user ?>:<?= $ftp_password ?>@<?= $ftp_server . $file ?>" target="_blank">Visualiser</a> |
                        <a href="ftp://<?= $ftp_user ?>:<?= $ftp_password ?>@<?= $ftp_server . $file ?>" download>Télécharger</a> |
                        <a href="?delete=<?= urlencode(basename($file)) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2">Aucun document trouvé sur le serveur FTP.</td></tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
