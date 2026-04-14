<?php
ob_start();
?>

<link rel="stylesheet" href="../style/refuge.css">
<link rel="stylesheet" href="../style/lightbox.css">
<script src="../../script/lightbox.js"></script>

<h2>Le refuge du Roedelen</h2>

<div class="refuge">
    <p>Notre association possede un refuge qui se situe au pied du Grand-Ballon : le point culminant des Vosges.
    </p>
    <p>Notre chalet est ouvert un week-end sur deux l'hiver, entre sur réservation, et tous les Week-End d'été.
    </p>
    <p>Sa situation est idéale, à proximité du GR 532 et de la route des crêtes.</p>
    <p>On peut y accéder l'été de Guebwiller, à pieds ou en vélo, en passant par de nombreux itinéraires et
        l'hiver
        depuis la station du Markstein, à environ 6 Km par une piste dammée pour le ski de fond classique,
        skating
        et raquette .</p>
    <p>Notre refuge est à un point idéal pour réaliser le tour de la vallée de Guebwiller en trois jours en
        passant
        par le Petit-Ballon puis le Grand-Ballon.</p>
    <p>Notre chalet est équipé d'une salle de séjour et d'un dortoir comptant une capacité de 30 couchages,
        ainsi
        qu'une cuisine en libre service et des sanitaires (toilettes, douches etc...).</p>
</div>

<div class="refuge">
    <h3>Tarifs :</h3>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Non Menbre Adultes</th>
                <th>Non Menbre Enfants</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nuitée</td>
                <td>15€</td>
                <td>8€</td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th></th>
                <th>Menbre Adultes</th>
                <th>Menbre Enfants</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nuitée</td>
                <td>2.5€</td>
                <td>2€</td>
            </tr>
        </tbody>
        <caption>Tarifs 2020 - 2021</caption>
    </table>
</div>


<div class="refuge">
    <h4>Des photos du refuge du Roedelen</h4>
    <div class="photos">
        <img src="../media/refugeete.jpg" alt="" width="350" height="250" />

        <img src="../media/refugehiver.jpg" alt="" width="350" height="250" />

        <img src="../media/refugecuisine.jpg" alt="" width="350" height="250" />

        <img src="../media/refugesejour.jpg" alt="" width="350" height="250" />

        <img src="../media/refugedortoir.jpg" alt="" width="350" height="250" />

        <img src="../media/refugedortoir2.jpg" alt="" width="350" height="250" />
    </div>
</div>

<hr>

<div class="refuge">
    <h3>Se restaurer à proximité :</h3>
    <p>Bien que nous sommes équipé d'une cuisine accessible à tous, il est possible de manger aux adresses
        suivantes :</p>
    <br />
    <ul class="cuisine">
        <li><a href="https://gustiberg.fr/" target="_blank">Ferme-auberge du Gustiberg</a></li>
        <li><a href="#">Ferme du Haag</a></li>
        <!-- <li><a href="https://www.fermeduhaag.fr/" target="_blank">Ferme du Haag</a></li> -->
        <li><a href="https://www.lavuedesalpes.fr/">La Vue des Alpes</a></li>
        <li><a href="https://www.ferme-auberge-grand-ballon.fr/">La ferme auberge du Grand-Ballon</a></li>
    </ul>
</div>

<hr>

<div class="refuge">
    <h3>On parle de nous :</h3>
    <ul class="reference">
        <li><a href="http://tomalp.over-blog.net/article-27718096.html" target="_blank">Tomalp</a></li>
        <li><a href="http://touringclubmulhouse.com/refuge/" target="_blank">Touring Club Mulhouse</a></li>
        <li><a href="http://www.lemarkstein.net/fr/decouvrir/grand-ballon/" target="_blank">Le Markstein</a>
        </li>
    </ul>
</div>

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>