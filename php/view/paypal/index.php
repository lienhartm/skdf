<script src="https://www.paypal.com/sdk/js?client-id=AWkuxpg4LorLOpRHQVNcw7EnJVScsd-HTEhhaRfASUI5-JDsDHmdXhMf0yCzNFOmbQv8LPe8HtnSQqLv&currency=EUR"></script>

<div id="paypal-button-container"></div>

<script>
paypal.Buttons({
    createOrder: function(data, actions) {
        return fetch('./create-order.php', {
            method: 'post'
        }).then(res => res.json())
          .then(data => data.id);
    },
    onApprove: function(data, actions) {
        return fetch('./capture-order.php', {
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