<?php

include 'config.php';


// Vérification de connexion

if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

$message = "";


// Récupération des données du formulaire

if (!empty($_POST)) {
    $new_username = trim($_POST['new_username']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];


    // Vérification des champs obligatoires

    if (empty($new_username) || empty($current_password)) {
        $message = 'Veuillez remplir tous les champs obligatoires.';
    } else {


        // 1. Récupération des infos de l'utilisateur actuel

        $stmt = $pdo->prepare('SELECT * FROM user WHERE username = :username');
        $stmt->execute([':username' => $_SESSION['username']]);
        $user = $stmt->fetch();


        // 2. Vérifier si le mot de passe actuel est bon

        if ($user && password_verify($current_password, $user['password'])) {
            

            // 3. Vérifier si le nouveau pseudo est déjà pris (si on le change)

            $pseudo_ok = true;
            if ($new_username !== $_SESSION['username']) {
                $checkUser = $pdo->prepare('SELECT id FROM user WHERE username = :new_user');
                $checkUser->execute([':new_user' => $new_username]);
                if ($checkUser->fetch()) {
                    $message = "Ce nom d'utilisateur est déjà utilisé.";
                    $pseudo_ok = false;
                }
            }

            if ($pseudo_ok) {


                // CAS A : Changement de mot de pasee

                if (!empty($new_password)) {
                    if ($new_password !== $confirm_password) {
                        $message = 'Les nouveaux mots de passe ne correspondent pas.';
                    } 
                    elseif (strlen($new_password) < 8 || !preg_match('/[0-9]/', $new_password) || !preg_match('/[A-Z]/', $new_password)) {
                        $message = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.';
                    } 
                    else {
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        
                        $updateSql = 'UPDATE user SET username = :new_username, password = :new_password WHERE username = :old_username';
                        $stmtUpdate = $pdo->prepare($updateSql);
                        
                        if ($stmtUpdate->execute([
                            ':new_username' => $new_username, 
                            ':new_password' => $hashed_password, 
                            ':old_username' => $_SESSION['username']
                        ])) {
                            $_SESSION['username'] = $new_username;
                            $message = 'Profil et mot de passe mis à jour avec succès.';
                            header('Location: profil.php');
                            exit();
                        } else {
                            $message = 'Erreur lors de la mise à jour.';
                        }
                    }
                } 


                // CAS B : Changement uniquement du Nom d'utilisateur

                else {
                    $updateSql = 'UPDATE user SET username = :new_username WHERE username = :old_username';
                    $stmtUpdate = $pdo->prepare($updateSql);
                    
                    if ($stmtUpdate->execute([
                        ':new_username' => $new_username, 
                        ':old_username' => $_SESSION['username']
                    ])) {
                        $_SESSION['username'] = $new_username; 
                        $message = 'Profil mis à jour avec succès.';
                        header('Location: profil.php');
                        exit();
                        
                    } else {
                        $message = 'Erreur lors de la mise à jour.';
                    }
                }
            }
        } else {
            $message = 'Mot de passe actuel incorrect.';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<body>
    <main>
        <h1>Modifier mon profil</h1>
        <div class="edit-container">
            <form method="post">
                <label for="new_username">Nouveau Nom d'utilisateurs</label>
                <input type="text" name="new_username" id="new_username" required>

                <label for="current_password">Mot de passe actuel</label>
                <input type="password" name="current_password" id="current_password" required>

                <label for="new_password">Nouveau mot de passe (optionel):</label>
                <input type="password" name="new_password" id="new_password">

                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                <input type="password" name="confirm_password" id="confirm_password">

                <?php if(isset($message)): ?>
                    <p class="message-info"><?= $message ?></p>
                <?php endif; ?>

                <button type="submit">Mettre à jour</button>
            </form>
        </div>
    </main>
</body>