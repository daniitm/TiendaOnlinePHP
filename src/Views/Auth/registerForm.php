<hr>
<h3>Registrarse: </h3>
<form action="<?= BASE_URL ?>register" method='POST'>
    <label for="nombre">Nombre:</label>
    <input type="text" name="data[nombre]" value="<?= $_POST['data']['nombre'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['nombre'])): ?>
        <p class="error"><?= $_SESSION['errores']['nombre'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="apellidos">Apellidos:</label>
    <input type="text" name="data[apellidos]" value="<?= $_POST['data']['apellidos'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['apellidos'])): ?>
        <p class="error"><?= $_SESSION['errores']['apellidos'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="email">Email:</label>
    <input type="text" name="data[email]" value="<?= $_POST['data']['email'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['email'])): ?>
        <p class="error"><?= $_SESSION['errores']['email'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="password">Contraseña:</label>
    <input type="password" name="data[password]" id="password"required>
    <?php if (isset($_SESSION['errores']['password'])): ?>
        <p class="error"><?= $_SESSION['errores']['password'] ?></p>
    <?php endif; ?>
    <br><br>

    <input type="submit" value="register">
</form>

<?php
if (isset($_SESSION['errores'])) {
    unset($_SESSION['errores']);
}
?>
<hr>