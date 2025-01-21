<hr>
<h3>Finalizar Compra</h3>

<form method="POST" action="placeOrder">
    <label for="provincia">Provincia:</label>
    <input type="text" name="data[provincia]" value="<?= $_POST['data']['provincia'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['provincia'])): ?>
        <p class="error"><?= $_SESSION['errores']['provincia'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="localidad">Localidad:</label>
    <input type="text" name="data[localidad]" value="<?= $_POST['data']['localidad'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['localidad'])): ?>
        <p class="error"><?= $_SESSION['errores']['localidad'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="direccion">Dirección:</label>
    <input type="text" name="data[direccion]" value="<?= $_POST['data']['direccion'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['direccion'])): ?>
        <p class="error"><?= $_SESSION['errores']['direccion'] ?></p>
    <?php endif; ?>
    <br><br>

    <button type="submit">Comprar</button>
</form>
<hr>