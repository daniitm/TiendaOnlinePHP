<form id="checkout-form" action="<?= BASE_URL ?>placeOrder" method="POST">
    <label for="provincia">Provincia:</label>
    <input type="text" name="data[provincia]" id="provincia" required>
    <?php if (isset($_SESSION['errores']['provincia'])): ?>
        <p class="error"><?= $_SESSION['errores']['provincia'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="localidad">Localidad:</label>
    <input type="text" name="data[localidad]" id="localidad" required>
    <?php if (isset($_SESSION['errores']['localidad'])): ?>
        <p class="error"><?= $_SESSION['errores']['localidad'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="direccion">Dirección:</label>
    <input type="text" name="data[direccion]" id="direccion" required>
    <?php if (isset($_SESSION['errores']['direccion'])): ?>
        <p class="error"><?= $_SESSION['errores']['direccion'] ?></p>
    <?php endif; ?>
    <br><br>

</form>

<br><br>

<div id="paypal-button-container"></div>

<script src="https://www.paypal.com/sdk/js?client-id=AV6E4QJr36DZTOTt7fjHFGoKjwF-5UYGFuoty4l4OBeIwi7Galu3oQOBrFS0b_bwLGrY5SQp9ozZlitN&currency=EUR"></script>
<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?= $_SESSION['cart'] ? array_sum(array_map(fn($item) => $item["precio"] * $item["cantidad"], $_SESSION["cart"])) : 0 ?>'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                let formData = new FormData(document.getElementById('checkout-form'));
                formData.append('paypal_order_id', data.orderID);
                formData.append('metodo_pago', 'PayPal');

                fetch('<?= BASE_URL ?>placeOrder', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert('Error al procesar el pedido: ' + (data.message || 'Error desconocido'));
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar el pedido: ' + error.message);
                });
            });
        }
    }).render('#paypal-button-container');
</script>