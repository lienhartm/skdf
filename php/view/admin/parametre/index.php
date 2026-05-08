<?php
ob_start();
?>

<link rel="stylesheet" href="../style/admin.css">

<section class="admin-page">
    <div>
        <h2>Paramètres</h2>

        <p>Désigner un nouvel administrateur:</p>

        <form method="POST" action="?action=new_admin">
            <label for="newAdminEmail">Email:</label>
            <input type="email" id="newAdminEmail" name="email" required>

            <br /><br />

            <label for="newAdminPassword">Mot de passe:</label>
            <input type="password" id="newAdminPassword" name="password" required>

            <br /><br />

            <button type="submit">Ajouter</button>
        </form>

        <p>Attention toute validation entraîne directement les changements sans confirmation supplémentaire. Veillez à ne pas faire d'erreur.</p>
        <p>Le mot de passe sera modifier par le nouvel administrateur lors de la réception de l'email de confirmation.</p>

        <br />
    </div>
</section>

<?php
$contenu = ob_get_clean();
require_once './model/admin.php';
?>