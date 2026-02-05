<?php

include 'config.php';

$message = '';

if(!empty($_POST)){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if(empty($username) || empty($password) || empty ($confirm_password)){
        $message = 'Veuillez remplir tous les champs.';
    }
    elseif(strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[A-Z]/', $password)){
        $message = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.';
    }
    elseif($password !== $confirm_password){
        $message = 'Les mots de passe ne correspondent pas.';
    }
    else{
        if(isset($pdo)){
            try{
                $checkSql = 'SELECT count(*) FROM user WHERE username = :username';
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->execute([':username' => $username]);
                $user =$checkStmt->fetchColumn();

                if($user > 0){
                    $message = 'Ce nom d\'utilisateur est déjà pris.';
                }
                else{
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = 'INSERT INTO user (username, password) VALUES (:username, :password)';
                    $stmt = $pdo->prepare($sql);

                    if($stmt->execute([':username' => $username, ':password' => $hash])){
                        header('Location: signin.php');
                        exit();
                    }
                    else{
                        $errorinfo = $stmt->errorInfo();
                        $message = 'Erreur lors de l\'inscription : ' . $errorinfo[2];
                    }
                }
            } catch(PDOException $e){
                $message = 'Erreur SQL : ' . $e->getMessage();
            }
        }
    }
}

?>
<?php include 'includes/header.php'; ?>
<body>
    <main>
        <h1>Inscription</h1>
        <section>
            <div class="form-section-signup">
                <form action="" method="post">
                    <input type="text" name="username" placeholder="Nom d'utilisateur">
                    <input type="password" name="password" placeholder="Mot de passe">
                    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe">
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
        </section>
    </main>
</body>