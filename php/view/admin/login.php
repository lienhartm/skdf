<?php
ob_start();
?>

<style>
    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    label {
        margin-bottom: 10px;
    }

    form>input {
        width: 20%;
        margin-bottom: 10px;
    }

    form>input[type="submit"] {
        margin: 50px;
        width: 20%;
        color: #000;
        background-color: #31d2f2;
        border-color: #25cff2;
        border-radius: 5px;
        padding: 10px;
    }
</style>

<form method="post" action="?action=checkLogin">
    <label for="username">Nom d'utilisateur</label>
    <input type="text" id="username" name="username">
    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password">
    <input type="submit" value="Se connecter">
</form>

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>