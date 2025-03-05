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

<!-- Formulaire prérempli avec les données existantes -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Modifier un employé</title>
</head>
<body>
<h1>Modifier un employé</h1>

<form action="update_employe.php?id=<?= $employe['employe_id'] ?>" method="post">
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

</body>
</html>
