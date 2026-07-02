<?php
ob_start();
?>

<link rel="stylesheet" href="../style/reservation.css">

<section class="reservation-page">
    <h2>Réservation</h2>
    <p>Pour réserver une nuitée au refuge, merci d'indiquer vos date de séjour comprenant la date d'arrivée et de départ. <span class="italic">Pour l'exemple la nuitée du 2 au 5 bloque les nuits du 2, 3 et 4 uniquement.</span></p>
    <br />
    <!-- calendrier de réservation -->
    <form id="reservationForm" action="?action=paypal" method="POST">
        <div class="calendar">
            <div class="month">
                <ul>
                    <li class="prev">&#10094;</li>
                    <li class="month-title">Août<br><span style="font-size:18px">2024</span></li>
                    <li class="next">&#10095;</li>
                </ul>
            </div>
            <div id="calendar-error"></div>
            <div class="days"></div>
        </div>

        <p class="field-note">Le départ se fait le matin du jour sélectionné, les nuits sont comptées jusqu’à la veille.</p>

        <br />

        <div class="reservation-details">
            <div class="reservation-inputs">
                <label for="numPeople">Nombre d'adultes:</label>
                <input type="number" id="numPeople" name="numPeople" min="1" max="10">

                <br />

                <label for ="numChildren">Nombre d'enfants:</label>
                <input type="number" id="numChildren" name="numChildren" min="0" max="10">

                <br />

                <label for="arrivalDate">Date d'arrivée:</label>
                <input type="date" id="arrivalDate" name="arrivalDate">

                <br />

                <label for="departureDate">Date de départ (matin):</label>
                <input type="date" id="departureDate" name="departureDate">

                <br />

                <label for="motivation">Motivations:</label>
                <input type="text" id="motivation" name="motivation">

            </div>
            <div>

                    <p class="bold">Nom : <span id="nom">...</span></p>
                    <p class="bold">Prénom : <span id="prenom">...</span></p>
                    <p class="bold">Voie: <span id="voie">0</span></p>
                    <p class="bold">Rue: <span id="rue">...</span></p>
                    <p class="bold">Code Postal: <span id="codePostal">00000</span></p>
                    <p class="bold">Ville: <span id="ville">...</span></p>
                    <p class="bold">Tél: <span id="telephone">+33-000-000-000</span></p>
                    <p class="bold">Courriel: <span id="courriel">...@....com</span></p>
                    <p class="bold">Date de début: <span id="dateDebut">00-00-0000</span></p>
                    <p class="bold">Date de fin: <span id="dateFin">00-00-0000</span></p>
                    <p class="bold">Nombre de nuitées: <span id="numDays">0</span></p>
                    <p class="bold">Nombre d'adultes: <span id="numAdult">0</span></p>
                    <p class="bold">Nombre d'enfants: <span id="numChild">0</span></p>

                    <p class="bold">Motivation: <span id="textMotivation">...</span></p>

                <button id="reserveButton">Réserver</button>
            </div>
        </div>
    </form>

    <br />

</section>

<script src="../script/reservation.js"></script>

<script>
    const reservations = <?php echo json_encode($reservations ?? []); ?>;
</script>
    

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>