<?php
ob_start();
?>

<link href="../style/inscrit.css" rel="stylesheet">

<section id="inscrit">
    <div style="display:flex;flex-direction:row;">
        <div style="display:flex;flex-direction:column;width:50%;">
            <h2>Inscription d'individu</h2>

            <form method="post" action="?action=write_inscrit">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
                <label for="gardien">Gardien</label>
                <input type="checkbox" id="gardien" name="gardien">
                <label for="newsletter">Newsletter</label>
                <input type="checkbox" id="newsletter" name="newsletter" checked>
                <input type="submit" value="S'inscrire">
            </form>
        </div>

        <div style="display:flex;flex-direction:column;width:50%;">
            <h2>Import de fichier d'inscrit</h2>
            <form method="post" action="?action=write_file_inscrit" enctype="multipart/form-data">
                <label for="file">Fichier CSV</label>
                <input type="file" id="file" name="file">
                <input type="submit" value="Importer">
            </form>
        </div>
    </div>

    <h2>Les inscrits aux newsletters : <span><?= count($inscrits) ?></span></h2>


    <h2>Liste des conscrits inscrits</h2>
    <div id="newsletters">
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Gardien</th>
                    <th>Newsletter</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($inscrits as $inscrit): ?>
                    <tr>
                        <td><?= htmlspecialchars($inscrit['nom']) ?></td>
                        <td><?= htmlspecialchars($inscrit['prenom']) ?></td>
                        <td><?= htmlspecialchars($inscrit['email']) ?></td>
                        <td><?= htmlspecialchars($inscrit['date_inscription']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php
$contenu = ob_get_clean();
require_once './model/admin.php';
?>