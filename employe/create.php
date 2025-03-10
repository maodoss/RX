<?php
// create.php

require_once '../config/database.php';
require 'PHPMailer/PHPMailerAutoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des valeurs du formulaire
    $nom       = $_POST['nom']       ?? '';
    $prenom    = $_POST['prenom']    ?? '';
    $email     = $_POST['email']     ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $poste     = $_POST['poste']     ?? '';
    $salaire   = $_POST['salaire']   ?? 0;
    $domain    = explode('@', $email)[1]; // Extraire le domaine de l'e-mail
    $username  = explode('@', $email)[0]; // Extraire le nom d'utilisateur

    try {
        // 1. Préparation de la requête d’insertion dans votre base de données (employés)
        $sql = "INSERT INTO employes (nom, prenom, email, telephone, poste, salaire)
                VALUES (:nom, :prenom, :email, :telephone, :poste, :salaire)";
        $stmt = $pdo->prepare($sql);

        // Exécution avec un tableau de paramètres
        $stmt->execute([
            ':nom'       => $nom,
            ':prenom'    => $prenom,
            ':email'     => $email,
            ':telephone' => $telephone,
            ':poste'     => $poste,
            ':salaire'   => $salaire
        ]);

        echo "Employé ajouté avec succès !<br>";

        // 2. Ajouter l'utilisateur dans la base de données iRedMail (vmail)

        // Insertion du domaine dans la table `domain` de iRedMail (si nécessaire)
        $sqlDomain = "INSERT INTO domain (domain, description, active) 
                      VALUES ('$domain', 'Mon domaine principal', 1)";
        $pdo->query($sqlDomain);

        // Insertion de l'utilisateur dans la table `mailbox` de iRedMail
        // Utiliser un mot de passe généré ou par défaut
        $sqlUser = "INSERT INTO mailbox (username, password, domain, maildir, quota, active)
                    VALUES ('$username','passer', '$domain', 'vmail/$username', 104857600, 1)";
        $pdo->query($sqlUser);

        // 3. Envoyer un e-mail de confirmation
        sendConfirmationEmail($email);

    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout : " . $e->getMessage();
    }
}

// Fonction pour envoyer un e-mail de confirmation
function sendConfirmationEmail($email) {
    $mail = new PHPMailer;

    // Configuration du serveur SMTP (en utilisant le serveur iRedMail)
    $mail->isSMTP();
    $mail->Host = '192.168.1.11';  // iRedMail utilise généralement le serveur local
    $mail->SMTPAuth = true;
    $mail->Username = 'postmaster@vooz.sn';  // Utilisateur admin sur votre serveur iRedMail
    $mail->Password = 'passer123';  // Mot de passe du compte admin
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Destinataire et expéditeur
    $mail->setFrom('postmaster@vooz.sn', 'Admin');
    $mail->addAddress($email, 'Nouveau utilisateur');  // L'utilisateur nouvellement créé

    // Contenu de l'e-mail
    $mail->isHTML(true);
    $mail->Subject = 'Confirmation de création de compte';
    $mail->Body    = 'Bonjour,<br>Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter à votre compte avec l\'adresse e-mail ' . $email . '.';

    if ($mail->send()) {
        echo "E-mail de confirmation envoyé à $email.\n";
    } else {
        echo "Erreur lors de l'envoi de l'e-mail: " . $mail->ErrorInfo . "\n";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Employé</title>
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
        input[type="email"],
        input[type="number"] {
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
        /* Pour un peu de réactivité sur mobiles */
        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 15px;
            }
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
    <h1>Ajouter un Employé</h1>
    <form action="create.php" method="post">
        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Prénom :</label>
        <input type="text" name="prenom" required>

        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Téléphone :</label>
        <input type="text" name="telephone">

        <label>Poste :</label>
        <input type="text" name="poste">

        <label>Salaire :</label>
        <input type="number" step="0.01" name="salaire">

        <button type="submit">Ajouter</button>
    </form>
</div>
</body>
</html>



