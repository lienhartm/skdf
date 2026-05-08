<?php
ob_start();
?>

<link rel="stylesheet" href="../style/paypal.css">

<?php

function sanitizeText($value)
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function parseDate($value)
{
    if (!$value) {
        return null;
    }
    $date = DateTime::createFromFormat('Y-m-d', $value);
    return $date ?: null;
}

function calculateNights($arrival, $departure)
{
    if (!$arrival || !$departure) {
        return 0;
    }
    $interval = $arrival->diff($departure);
    return max(0, (int) $interval->days);
}

function getNightlyRate($statut)
{
    $prices = [
        TRUE => ["adulte" => 2.5, "enfant" => 2],
        FALSE => ["adulte" => 15, "enfant" => 8]
    ];

    return $prices[$statut] ?? $prices[FALSE];
}

$statut = sanitizeText($_SESSION['membre'] ?? FALSE);
$email = sanitizeText($_SESSION['email'] ?? '');
$numAdults = intval($_POST['numPeople'] ?? 0);
$numChildren = intval($_POST['numChildren'] ?? 0);
$arrivalDate = parseDate($_POST['arrivalDate'] ?? '');
$departureDate = parseDate($_POST['departureDate'] ?? '');
$nights = calculateNights($arrivalDate, $departureDate);
$rates = getNightlyRate($statut);

$nightlyRate = 
    ($rates["adulte"] * $numAdults) +
    ($rates["enfant"] * $numChildren);

$amount = $nights > 0 ? number_format($nights * $nightlyRate, 2, '.', '') : '0.00';
?>

<section class="paypal">
    <div class="paypal">
        
        <div class="paypal-summary">
            <h2>Résumé de réservation</h2>
            <p>Statut : <strong><?= $statut ? 'Membre' : 'Non membre' ?></strong></p>
            <p>Nom : <strong><?= htmlspecialchars($_SESSION['nom']) ?></strong></p>
            <p>Prénom : <strong><?= htmlspecialchars($_SESSION['prenom']) ?></strong></p>
            <p>Email : <strong><?= $email ?: 'Non renseigné' ?></strong></p>
            <p>Adultes : <strong><?= $numAdults ?></strong></p>
            <p>Enfants : <strong><?= $numChildren ?></strong></p>
            <p>Nombre de nuits : <strong><?= $nights ?></strong></p>
            <p>Tarif par nuit : <strong><?= number_format($nightlyRate, 2, ',', ' ') ?> €</strong></p>
            <p>Total à payer : <strong><?= number_format($amount, 2, ',', ' ') ?> €</strong></p>
        </div>

        <script src="https://www.paypal.com/sdk/js?client-id=AWkuxpg4LorLOpRHQVNcw7EnJVScsd-HTEhhaRfASUI5-JDsDHmdXhMf0yCzNFOmbQv8LPe8HtnSQqLv&currency=EUR"></script>
        <br />
        <div id="paypal-button-container"></div>
        <br />
    </div>

</section>

<script>
const reservationData = {
    amount: '<?= $amount ?>',
    statut: '<?= $statut ?>',
    email: '<?= $email ?>',
    arrivalDate: '<?= $arrivalDate ? $arrivalDate->format('Y-m-d') : '' ?>',
    departureDate: '<?= $departureDate ? $departureDate->format('Y-m-d') : '' ?>',
    numPeople: <?= $numAdults ?>,
    numChildren: <?= $numChildren ?>,
    nights: <?= $nights ?>
};

paypal.Buttons({
    createOrder: function(data, actions) {
        return fetch('/php/view/paypal/create-order.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(reservationData)
        }).then(res => res.json())
          .then(data => data.id);
    },
    onApprove: function(data, actions) {
        return fetch('/php/view/paypal/capture-order.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                orderID: data.orderID
            })
        }).then(res => res.json())
          .then(details => {
              alert('Paiement réussi');
          });
    }
}).render('#paypal-button-container');
</script>

<?php
$contenu = ob_get_clean();
require_once './model/web.php';
?>