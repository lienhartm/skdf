<?php
ob_start();
?>

<link rel="stylesheet" href="../style/login.css">

<section class="login">

<p>Vous n'avez pas accès à cette page.</p>

<br />

</section>

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>