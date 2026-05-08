<?php
ob_start();
?>

<link rel="stylesheet" href="../style/acces.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>

<div class="acces">

    <h2>Accès :</h2>

    <ul>
        <li>L'accès au chalet se fait en voiture par la route depuis le <b>parking du Grand-Ballon</b> accéssible
            selon les
            saisons :</li>
    </ul>

    <blockquote>
        <p>L'été par la <b>route des crêtes (D431)</b> en venant de <b>Guebwiller</b> ou <b>Thann</b> respéctivement
            par <b>le Col-Amic</b> ou <b>le Markstein</b>.</p>

        <p>L'hiver uniquement par <b>Thann</b> et <b>le Col-Amic (D431 - D13b6)</b>.</p>

        <p>Puis à pied par le chemin balisé GR direction Roedelen (<b>rectangle rouge-blanc-rouge</b>).</p>
    </blockquote>

    <ul>
        <li>Ou à pied par la crête sur le <b>GR 532</b>, ou de <b>la Vallée de Guebwiller</b> depuis le
            <b>Judenhut</b>, <b>Lieserwasen</b>, <b>Lac de Ballon</b> ou encore la <b>Ferme du Ballon</b>.
        </li>
    </ul>
</div>

<hr>

<div id="map" style="height:400px;width: 800px;margin: auto;"></div>

<br />

<hr>

<br />

<script>
    var map = L.map('map').setView([47.907614, 7.104433], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    new L.GPX("../../documents/geomapx/scg.gpx", {
        async: true,
        marker_options: {
            startIconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/pin-icon-start.png',
            endIconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/pin-icon-end.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/pin-shadow.png'
        }
    }).on('loaded', function (e) {
        map.fitBounds(e.target.getBounds());
    }).addTo(map);
</script>

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>