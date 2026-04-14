<?php
ob_start();
?>

<h2>Admin</h2>

<p>Vous êtes connecté.</p>

<a href="?action=logout">Se déconnecter</a>

<?php
$contenu = ob_get_clean();
require_once './model/admin.php';
?>