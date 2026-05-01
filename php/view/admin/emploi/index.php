<?php
ob_start();
?>

<link href="../style/emploi.css" rel="stylesheet">

<section id="emploi">
    <h2>Mode d'emploi</h2>

    <br />

    <div class="upload">
        <h3>Upload de documents</h3>

        <p>Vous devez choisir le type de documents que vous souhaitez upload (Résultats ou Revue de presse)</p>
        <p>Renseignez le titre du document que vous voulez faire apparaître sur le site</p>
        <p>Vous devez ensuite choisir le document que vous souhaitez upload</p>
        <p>Pour pouvoir upload un document, vous devez cliquer sur le bouton "Upload". </p>

        <br />

        <h3>Suppression/Modification de documents</h3>
        <p>En bas de page se trouve les listes des documents uploadés</p>
        <p>Vous pouvez supprimer ou modifier un document en cliquant sur le bouton "Supprimer" ou "Modifier"</p>

        <br />

        <h3>Suppression</h3>
        <p>En cliquant sur le bouton supprimer une fenêtre s'ouvre et vous demande de confirmer la suppression (en
            cas
            de confirmation de la suppression le document est supprimé instantanément)</p>

        <br />

        <h3>Modification</h3>
        <p>En cliquant sur le bouton modifier vous ferez apparaitre le titre et le document dans le formulaire
            ci-dessus.</p>
        <p>En validant la modification celui ci est alors modifier.</p>
    </div>

    <div class="info">
        <h3>Informations</h3>
        <p>Pour toute question ou problème, n'hésitez pas à contacter le responsable du site.</p>
    </div>

    <br />

    <div class="write">
        <h3>Ecrire un article</h3>
        <p>Pour écrire un article rien de plus simple, remplisser les champs du formulaire.</p>
        <p>Vous apercevrez un aperçu de votre article sur le côté droit de la page.</p>
        <p>Une fois votre article écrit, vous pouvez le publier en cliquant sur le bouton "Publier".</p>

        <br />

        <h3>Suppression/modification d'article</h3>
        <p>En bas de page se trouve les listes des articles écrits</p>
        <p>Vous pouvez supprimer ou modifier un article en cliquant sur le bouton "Supprimer" ou "Modifier"</p>

        <br />

        <h3>Suppression</h3>
        <p>Si vous décidez de supprimer un article, une confirmation vous sera demandez (en cas de confirmation de
            la
            suppression l'article est supprimé instantanément)</p>

        <br />

        <h3>Modification</h3>
        <p>En cliquant sur le bouton modifier vous ferez apparaitre le titre et le contenu de l'article dans le
            formulaire ci-dessus.</p>
        <p>Vous pourrez alors le modifier à votre guise.</p>
        <p>Une fois votre article modifié, vous pouvez le republier en cliquant sur le bouton "Publier".</p>
    </div>
</section>

<?php
$contenu = ob_get_clean();
require_once './model/admin.php';
?>