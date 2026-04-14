<?php
ob_start();
?>

<link rel="stylesheet" href="../style/presse.css">

<!-- PRESSE -->
<div class="presse">
    <h2>Revue de presse :</h2>
    <ul id="presse">
        <?php foreach ($presses as $presse): ?>
            <li class="presse">
                <a href="../documents/presse/<?= $presse['document'] ?>" target="_blank"><?= $presse['titre']; ?></a>
            </li>
        <?php endforeach; ?>

    </ul>
</div>


<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>