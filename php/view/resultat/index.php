<?php
ob_start();
?>

<link rel="stylesheet" href="../style/resultat.css">


<!-- RESULTATS -->
<div class="resultats">
    <h2>Résultats :</h2>
    <ul id="resultats">
        <?php foreach ($resultats as $resultat): ?>
            <li class="resultat">
                <a href="../documents/resultat/<?= $resultat['document'] ?>" target="_blank"><?= $resultat['titre']; ?></a>
            </li>
        <?php endforeach; ?>

    </ul>
</div>


<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>