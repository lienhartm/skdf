<?php
ob_start();
?>

<link rel="stylesheet" href="../style/club.css">
<link rel="stylesheet" href="../style/lightbox.css">
<script src="../../script/lightbox.js"></script>

<div>

    <h2>Ski de fond :</h2>

    <div class="skdf">
        <img src="../media/competition.jpg" alt="Ski de fond compétition" width="400" height="200" class="left" />
        <h3>Ski de fond compétition :</h3>
        <p>Pour la saison d'hiver, nous vous proposons une activité SKI DE FOND ADULTE (loisir) encadrée par des
            moniteurs fédéraux diplômés.</p>
        <p>La licence carte neige sera obligatoire pour tous les pratiquants. Les séances commenceront par
            l’apprentissage de la technique du skating.</p>
        <p>Le skating n’aura plus de secret pour vous, vous maîtriserez parfaitement la glisse, vous n’aurez
            plus peur des virages et vous pourrez apprécier même les descentes.</p>
    </div>

    <hr>

    <div class="skdf">
        <img src="../media/loisir.jpg" alt="Ski de fond loisir" width="400" height="200" class="right" />
        <h3>Ski de fond Loisir :</h3>
        <p>Notre club vous propose des sorties ski de fond classique pour les enfants et adultes à
            raison d'une
            fois par semaine au Markstein.</p>
        <p>Nous vous proposons également une initiation à la pratique du skating.</p>
        <p>Pout tous renseignements, s'adresser à :</p>
        <address class="renseignements">Raymond PEQUIGNOT<br>
            06 06 40 23 92<br>
            raymond.pequignot@gmail.com</address>
    </div>

    <hr>

    <div class="skdf">
        <img src="../media/organisation.jpg" alt="Ski de fond organisation" width="400" height="200" class="left" />
        <h3>Organisation de compétition de ski de fond :</h3>
        <p>Les membres de notre club sont très engagés dans l'organisation de compétion de ski de fond.</p>
        <p>Que ce soit pour le chronométrage, ou l'intendance notre club est aguéri.</p>
        <p>Nous organisons entre autre "la nordique des crêtes", compétion de premier plan.</p>
    </div>

</div>

<hr>

<br />

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>