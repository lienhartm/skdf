<?php
ob_start();
?>

<link rel="stylesheet" href="../style/contact.css">

<div class="contact">

    <h2>Nous contacter :</h2>
    <form action="">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email">
        <br />
        <label for="sujet">Sujet :</label>
        <input type="text" id="sujet" name="sujet">
        <br />
        <label for="message">Message :</label>
        <textarea id="message" name="message"></textarea>
        <br />
        <input type="submit" value="Envoyer">
    </form>
</div>


<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>