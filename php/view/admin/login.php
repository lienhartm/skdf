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
    <label for="email">Email</label>
    <input type="text" id="email" name="email">
    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password">
    <input type="submit" value="Se connecter">
</form>

<p><a href="#">Mot de passe oublié ?</a>/<a href="#">S'inscrire</a></p>

<br />

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>