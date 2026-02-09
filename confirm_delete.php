<?php

include 'config.php';
include 'includes/header.php';

?>
<div class="confirm-delete-container">
    <h2>Êtes vous sûr de vouloir supprimer votre profil ?</h2>
    <form action="delete.php" method="post">
        <button type="submit" name="confirm" value="yes">Oui, supprimer définitivement</button>
        <a href="profil.php" style="margin-left: 10px;">Non, annuler</a>
    </form>
</div>