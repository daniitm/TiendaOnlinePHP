<h3>Ingrese el token de verificación</h3>
<form action="<?= BASE_URL ?>Auth/validateVerificationToken" method="POST">
    <label for="verification_token">Token de Verificación:</label>
    <input type="text" id="verification_token" name="verification_token" required>
    <br><br>
    <input type="submit" value="Validar Token">
</form>
<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?= $_SESSION['error'] ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <p style="color: green;"><?= $_SESSION['success'] ?></p>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
