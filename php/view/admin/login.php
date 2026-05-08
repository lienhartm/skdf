<?php
ob_start();
?>

<link rel="stylesheet" href="../style/login.css">

<section class="login">
    <form method="post" action="?action=checkLogin">
        <label for="email">Email</label>
        <input type="text" id="email" name="email">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password">
        <input type="submit" value="Se connecter">
    </form>

    <p><a href="?action=psw_lost">Mot de passe oublié ?</a>/<a href="?action=register">S'inscrire</a></p>

    <br />
</section>

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>