<?php

include 'config.php';

if(!empty($_POST)){
    $sql = 'SELECT * FROM user WHERE username = :username';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $_POST['username']]);
    $user = $stmt->fetch();

    if($user && password_verify($_POST['password'], $user['password'])){
        $_SESSION['username'] = $user['username'];
        $_SESSION['id'] = $user['id'];
        header('Location: profil.php');
        exit();
    }
    else{
        $message_error = 'Identifiants incorrects.';
    }
}
?>
<?php include 'includes/header.php'; ?>



<body>
    <main>
        <h1>Connexion</h1>
        
        <?php if(isset($message_error)){ ?>
            <div style="color: red; background: #fdd; padding: 10px; border: 1px solid red; margin-bottom: 20px; text-align: center;">
                <?php echo $message_error; ?>
            </div>
        <?php } ?>

        <section>
            <div class="form-section-signin">
            <form action="" method="post">
                <input type="text" name="username" placeholder="Nom d'utilisateur" value="<?php echo isset($_POST['username']) ? ($_POST['username']) : ''; ?>">
                <input type="password" name="password" placeholder="Mot de passe">
                <button type="submit">Se connecter</button>
            </form>
            </div>
        </section>
    </main>
</body>