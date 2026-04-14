<?php
ob_start();
?>

<link rel="stylesheet" href="../style/accueil.css">

<p>Notre club est un club de ski, possédant un chalet-refuge ouvert à tous, tous les week-end en été et un week
    sur deux en hiver.</p>
<p>Affilié à la Fédération Française de Ski, il regroupe des amoureux de la nature de la montagne et de ski.</p>
<p>Nous vous proposons une activité ski de fond <b>loisir</b> et <b>compétition</b> encadrée par des
    <b>Moniteurs fédéraux diplomés</b>.
</p>

<br />

<hr>

<br />

<article>

    <div class="articles">

        <a href="?action=club">
            <div class="article">
                <figure>
                    <img src="media/skdf.jpg" alt="Ski de fond" width="400" height="200">
                    <figcaption>Ski de fond</figcaption>
                </figure>
            </div>
        </a>


        <a href="?action=refuge">
            <div class="article">
                <figure>
                    <img src="media/refuge2.jpg" alt="chalet de roedelen" width="400" height="200">
                    <figcaption>Chalet de Roedelen</figcaption>
                </figure>
            </div>
        </a>

    </div>

</article>

<br />

<hr>

<br />

<aside>
    <h2>Les dernières actualitées :</h2>

    <div class="archives">

        <?php foreach ($actus as $actu): ?>
            <a href="?action=actu&id=<?= $actu['id'] ?>">
                <div class="archive">
                    <img src="media/<?= $actu['type'] ?>.png" alt="<?= $actu['type'] ?>">
                    <h3><?= $actu['titre'] ?></h3>
                    <p>Publié le <?= $actu['date'] ?> par <?= $actu['auteur'] ?></p>
                    <p><?= substr($actu['article'], 0, 100) . "..." ?></p>
                </div>
            </a>
        <?php endforeach; ?>

    </div>
</aside>

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>