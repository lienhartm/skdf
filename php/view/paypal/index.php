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
        'visiteur' => 25.00,
        'inscrit' => 22.00,
        'membre' => 18.00,
    ];
    return $prices[$statut] ?? $prices['visiteur'];
}

$statut = sanitizeText($_POST['statut'] ?? 'visiteur');
$email = sanitizeText($_POST['email'] ?? '');
$numAdults = intval($_POST['numPeople'] ?? 0);
$numChildren = intval($_POST['numChildren'] ?? 0);
$arrivalDate = parseDate($_POST['arrivalDate'] ?? '');
$departureDate = parseDate($_POST['departureDate'] ?? '');
$nights = calculateNights($arrivalDate, $departureDate);
$nightlyRate = getNightlyRate($statut);
$amount = $nights > 0 ? number_format($nights * $nightlyRate, 2, '.', '') : '10.00';
?>

<label for="customerName">Nom:</label>
<input type="text" id="customerName" name="name" placeholder="Votre nom">
<label for="customerSurname">Prénom:</label>
<input type="text" id="customerSurname" name="surname" placeholder="Votre prénom">
<label for="customer
<label for="customerEmail">Email:</label>
<input type="email" id="customerEmail" name="email" placeholder="Votre email">

<br />

<br />

<div class="paypal-summary">
    <h2>Résumé de réservation</h2>
    <p>Statut : <strong><?= $statut ?></strong></p>
    <p>Email : <strong><?= $email ?: 'Non renseigné' ?></strong></p>
    <p>Adultes : <strong><?= $numAdults ?></strong></p>
    <p>Enfants : <strong><?= $numChildren ?></strong></p>
    <p>Nombre de nuits : <strong><?= $nights ?></strong></p>
    <p>Tarif par nuit : <strong><?= number_format($nightlyRate, 2, ',', ' ') ?> €</strong></p>
    <p>Total à payer : <strong><?= number_format($amount, 2, ',', ' ') ?> €</strong></p>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=AWkuxpg4LorLOpRHQVNcw7EnJVScsd-HTEhhaRfASUI5-JDsDHmdXhMf0yCzNFOmbQv8LPe8HtnSQqLv&currency=EUR"></script>

<div id="paypal-button-container"></div>

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