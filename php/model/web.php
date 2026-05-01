<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Ski Club Guebwiller : Refuge Grand-Ballon Roedelen Club Ski de Fond de Guebwiller">

    <title>SCG : Refuge Grand-Ballon Roedelen club ski de fond de Guebwiller</title>

    <!-- LINK -->
    <link rel="shortcut icon" type="image/x-icon" href="../media/favicon.ico">
    <link rel="stylesheet" href="../style/styles.css">
    <script src="../script/index.js"></script>

    <?php if (!empty($telescript)): ?>
        <nav id="telescript" class="telescript"></nav>

        <script>
            let texts = <?php echo json_encode($telescript); ?>;
            let i = 0;

            function showText() {
                if (i < texts.length) {
                    let el = document.createElement("span");
                    el.textContent = "***" + texts[i] + "*** ";
                    document.getElementById("telescript").appendChild(el);
                    i++;
                    setTimeout(showText, 10000);
                }
            }

            showText();
        </script>
    <?php endif; ?>

</head>

<body>

    <!--
    <header>
        <div class="header">
            <h1>Bienvenue au Ski Club Guebwiller !</h1>
            <img src="../media/logo.png" alt="Logo du Ski Club Guebwiller">
        </div>
    </header>

    <nav class="nav ">
        <a href="?action=index">Accueil</a>
        <a href="?action=refuge">Refuge</a>
        <a href="?action=club">Ski de fond</a>
            <a href="?action=histoire">Notre histoire</a>
        <a href="?action=actualites">Actualités</a>
        <a href="?action=presse">Revue de presse</a>
        <a href="?action=resultats">Résultats</a>
        <a href="?action=acces">Accès Refuge</a>
        <a href="?action=contact">Contact</a>
        <a href="?action=login">Connection</a>
    </nav>
-->
    <header>
        <div class="title">
            <img src="../media/logo.png" alt="Logo du Ski Club Guebwiller">
            <!-- updateImage is shut in js -->
            <h1 class="title">Ski Club Guebwiller</h1>
        </div>

        <nav class="nav ">
            <a href="?action=index">Accueil</a>
            <a href="?action=refuge">Refuge</a>
            <a href="?action=club">Ski de fond</a>
            <a href="?action=histoire">Notre histoire</a>
            <a href="?action=actualite">Actualités</a>
            <a href="?action=presse">Revue de presse</a>
            <a href="?action=resultat">Résultats</a>
            <a href="?action=acces">Accès Refuge</a>
            <a href="?action=contact">Contact</a>
            <?php if (isset($_SESSION['conscrit'])): ?>
                <div class="dropdown">
                    <a>Espace membre</a><br />
                    <div class="dropdown-content">
                        <a href="?action=profile">Profil</a><br />
                        <a href="?action=reservations">Réservations</a><br />
                        <a href="?action=logout">Déconnexion</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="?action=login">Connection</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>

        <?= $contenu ?>

    </main>

    <footer>

        <figure>
            <img src="media/logo.png" alt="Logo du Ski Club Guebwiller" width="200">
            <figcaption>Ski Club Guebwiller</figcaption>
            <br />
            <address>13 Rue de Trottberg, 68530 Buhl<br>
                Téléphone : 07 60 88 29 06</address>
        </figure>

        <div class="footer">
            <a href="?action=index">Accueil</a>
            <a href="?action=refuge">Refuge</a>
            <a href="?action=club">Ski de fond</a>
            <a href="?action=histoire">Notre histoire</a>
            <a href="?action=contact">Contact</a>
        </div>

        <div class="footer">
            <a href="?action=acces">Accès refuge</a>
            <a href="?action=actualites">Actualités</a>
            <a href="?action=presse">Revue de presse</a>
            <a href="?action=resultats">Résultats</a>
            <a href="?action=login">Connection</a>
        </div>

    </footer>

    <h6>2020 - 2022 Ski Club Guebwiller - Tous droits réservés</h6>

</body>

</html>