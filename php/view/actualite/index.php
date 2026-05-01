<?php
ob_start();
?>

<link rel="stylesheet" href="../style/actualite.css">
<link rel="stylesheet" href="../style/lightbox.css">
<script src="../../script/lightbox.js"></script>

<h2>Actualités</h2>

<div id="actualites">
    <?php
    foreach ($actus as $actu): ?>
        <div class="actualite">
            <div class="article-header">
                <h3>
                    <?= $actu['titre'] ?>
                </h3>
                <p>
                    <span><em>Publié le <?= $actu['date'] ?></em></span>
                    <span><em>Auteur : <?= $actu['auteur'] ?></em></span>
                </p>
            </div>
            <div class="article-content">
                <?php if (count($actu['images']) > 0): ?>
                    <img src="../documents/actualite/<?= $actu['images'][0] ?>" alt="<?= $actu['titre'] ?>" width="200"
                        height="auto">
                <?php endif; ?>
                <?php
                $md = new Markdown();
                echo $md->parse($actu['article']);
                ?>
            </div>
            <div class="photos">
                <div class="row">
                    <div class="column">
                        <?php
                        if (count($actu['images']) > 1):
                            foreach ($actu['images'] as $img): ?>
                                <img src="../documents/actualite/<?= $img ?>" alt="<?= $actu['titre'] ?>" width="200" height="auto">
                            <?php endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>
            <br>
            <hr>
            <br>
        </div>
    <?php endforeach; ?>
</div>

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>