<?php

include 'config.php';

if(isset($_POST['confirm']) && $_POST['confirm'] === 'yes'){

    $sql = 'DELETE FROM user WHERE username = :username';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $_SESSION['username'],
    ]);

    session_destroy();
    header('Location: index.php');
    exit();
}
    else{
        header('Location: profil.php');
        exit();
    }