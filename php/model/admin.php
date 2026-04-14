<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Ski Club Guebwiller : Refuge Grand-Ballon Roedelen Club Ski de Fond de Guebwiller">

    <title>SCG : Refuge Grand-Ballon Roedelen club ski de fond de Guebwiller</title>
    <link rel="shortcut icon" type="image/x-icon" href="../media/favicon.ico">
    <link rel="stylesheet" href="../style/styles.css">
    <script src="../script/index.js"></script>

    <style>

    </style>
</head>

<body>

    <header>
        <div class="title">
            <img src="../media/logo.png" alt="Logo du Ski Club Guebwiller">
            <!-- updateImage is shut in js -->
            <h1 class="title">Ski Club Guebwiller</h1>
        </div>

        <nav class="nav ">
            <a href="?action=write_newsletter">Newsletter</a>
            <a href="?action=write_article">Articles</a>
            <a href="?action=upload_doc">Documents</a>
            <a href="?action=markdown">Markdown</a>
            <a href="?action=mode_emploi">Mode d'emploi</a>
            <?php if (isset($_SESSION['admin'])) { ?>
                <a href="?action=logout">Déconnexion</a>
            <?php } else { ?>
                <a href="?action=login">Connexion</a>
            <?php } ?>
        </nav>

    </header>

    <main>

        <?= $contenu ?>

    </main>

    <h6>2020 - 2022 Ski Club Guebwiller - Tous droits réservés</h6>

</body>

</html>