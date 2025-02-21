<h2>Verificación de Cuenta</h2>

<?php if (isset($_SESSION['error'])): ?>
    <p class="error"><?= $_SESSION['error'] ?></p>
    <?php unset($_SESSION['error']); ?>
<?php elseif (isset($_SESSION['success'])): ?>
    <p class="success"><?= $_SESSION['success'] ?></p>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<p>Si aún no has ingresado tu token de verificación, por favor hazlo a continuación:</p>

<form action="<?= BASE_URL ?>Auth/verifyEmail" method="POST">
    <label for="verification_token">Token de Verificación:</label>
    <input type="text" name="verification_token" required>
    <br><br>
    <input type="submit" value="Verificar Cuenta">
</form>
<?php if (isset($_SESSION['error'])): ?>
    <p class="error"><?= $_SESSION['error'] ?></p>
<?php unset($_SESSION['error']); endif; ?>