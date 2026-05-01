<?php
ob_start();
?>

<link rel="stylesheet" href="../style/upload.css">

<section id="upload">
    <div class="upload-form">
        <h2>Upload un Résultats</h2>
        <form method="post" enctype="multipart/form-data" action="?action=write_document">
            <label for="type">Type de document</label>
            <select id="type" name="type">
                <option value="resultat">Résultats</option>
                <option value="presse">Revue de presse</option>
            </select>
            <label for="title">Titre</label>
            <input type="text" id="title" name="title">
            <label for="file-doc">Fichier</label>
            <input type="file" id="file-doc" name="file">
            <input type="submit" value="Envoyer">
        </form>
    </div>

    <br />

    <div class="liste">
        <div>
            <h2>Articles de presse</h2>
            <ul id="list-presse">

                <?php ?>

            </ul>
        </div>
        <div>
            <h2>Résultat sportifs</h2>
            <ul id="list-resultats">

                <?php ?>

            </ul>
        </div>

</section>

<script>
    fetch('../documents/presse/presse.json')
        .then(response => response.json())
        .then(data => {
            const actualitesContainer = document.getElementById('list-presse');
            const path = '../documents/presse/';
            if (data.length === 0) {
                actualitesContainer.innerHTML = '<p>Aucune presse</p>';
            } else {
                data.forEach(press => {
                    const pressDiv = document.createElement('li');
                    pressDiv.classList.add('presse');

                    pressDiv.innerHTML = `<a href="${path}${press.document}" target="_blank">${press.titre}</a>
                <a href="">Modifier</a>
                <a href="">Supprimer</a>`;
                    actualitesContainer.appendChild(pressDiv);
                });
            }
        })
        .catch(error => console.error("Erreur lors du chargement des articles de presse:", error));

    /*---*/

    fetch('../documents/resultat/resultat.json')
        .then(response => response.json())
        .then(data => {
            const actualitesContainer = document.getElementById('list-resultats');
            const path = '../documents/resultat/';
            if (data.length === 0) {
                actualitesContainer.innerHTML = '<p>Aucun resultat</p>';
            } else {
                data.forEach(resultat => {
                    const resultDiv = document.createElement('li');
                    resultDiv.classList.add('resultat');

                    resultDiv.innerHTML = `<a href="${path}${resultat.document}" target="_blank">${resultat.titre}</a>
                <a href="">Modifier</a>
                <a href="">Supprimer</a>`;
                    actualitesContainer.appendChild(resultDiv);
                });
            }
        })
        .catch(error => console.error("Erreur lors du chargement des articles de presse:", error));

</script>

<?php
$contenu = ob_get_clean();
require_once './model/admin.php';
?>