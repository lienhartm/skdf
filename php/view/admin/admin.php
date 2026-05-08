<?php
ob_start();
?>

<link rel="stylesheet" href="../style/admin.css">

<section class="admin-page">
    <div>
        <p>Gérez le contenu de votre site en toute simplicité.</p>
        <h2>Admin</h2>

        <p>Vous êtes connecté en tant qu'administrateur.</p>

        <br />

        <p>Vous pouvez basculez entre les différentes sections du site à l'aide des boutons du navigateur :</p>

        <ul>
            <li>`Accueil` du panel `Admin` permet de basculez vers la section `Client`.</li>
            <li>`Admin` du panel `Client` permet de basculez vers la section `Admin`.</li>
        </ul>

        <br />
    </div>
</section>

<?php
$contenu = ob_get_clean();
require_once './model/admin.php';
?>