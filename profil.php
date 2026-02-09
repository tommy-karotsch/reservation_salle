<?php

include 'config.php';

if(!isset($_SESSION['username'])){
    header('Location: signin.php');
    exit();
}
?>
<?php include 'includes/header.php'; ?>
<body>
    <main>
        <h1>Bienvenue,<div class="username"><?php echo ($_SESSION['username']); ?></div></h1>

        <ul class="box-profil">
            <li><a href="edit.php">Modifier mon profil</a></li>
            <li><a href="schedule.php">Planning</a></li>
            <li><a href="logout.php">Se déconnecter</a></li>
            <li><a href="confirm_delete.php">Supprimer mon compte</a></li>
        </ul>
    </main>
</body>