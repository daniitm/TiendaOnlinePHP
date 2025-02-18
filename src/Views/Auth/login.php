<h3>Inicia Sesión: </h3>
<form action="<?= BASE_URL ?>Auth/login" method='POST'>
    <label for="email">Email:</label>
    <input type="text" name="data[email]" value="<?= $_POST['data']['email'] ?? '' ?>" required>
    <?php if (isset($_SESSION['errores']['email'])): ?>
        <p class="error"><?= $_SESSION['errores']['email'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="password">Contraseña:</label>
    <input type="password" name="data[password]" id="password" required>
    <?php if (isset($_SESSION['errores']['password'])): ?>
        <p class="error"><?= $_SESSION['errores']['password'] ?></p>
    <?php endif; ?>
    <br><br>

    <input type="submit" value="login">
</form>

<a href="<?= BASE_URL ?>Auth/forgotPassword">¿Olvidaste tu contraseña?</a>

<?php if (isset($_SESSION['success'])): ?>
    <p class="success"><?= $_SESSION['success'] ?></p>
<?php unset($_SESSION['success']); endif; ?>

<?php
// Limpiar errores después de mostrarlos
if (isset($_SESSION['errores'])) {
    unset($_SESSION['errores']);
}
?>