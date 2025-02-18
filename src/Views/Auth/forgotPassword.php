<h3>Recuperar Contraseña</h3>
<form action="<?= BASE_URL ?>Auth/forgotPassword" method="POST">
    <label for="email">Correo Electrónico:</label>
    <input type="email" name="email" required>
    <br><br>
    <input type="submit" value="Enviar enlace de restablecimiento">
</form>
<?php if (isset($_SESSION['error'])): ?>
    <p class="error"><?= $_SESSION['error'] ?></p>
<?php unset($_SESSION['error']); endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <p class="success"><?= $_SESSION['success'] ?></p>
<?php unset($_SESSION['success']); endif; ?>