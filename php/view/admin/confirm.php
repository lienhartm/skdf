<?php
ob_start();
?>

<link rel="stylesheet" href="../style/login.css">

<section class="login">
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <p>Un code de confirmation a été envoyé à votre adresse email. Saisissez-le ci-dessous pour finaliser la connexion.</p>

    <form method="post" action="?action=checkCode">
        <label for="code">Code de confirmation</label>
        <input type="text" id="code" name="code" maxlength="6" autocomplete="one-time-code" required>
        <input type="submit" value="Valider le code">
    </form>

    <p><a href="?action=code_mail">Renvoyer un code ?</a></p>

    <br />
</section>

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>