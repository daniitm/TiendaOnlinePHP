<h3>Restablecer Contraseña</h3>
<form action="<?= BASE_URL ?>Auth/resetPassword" method="POST">
    <input type="hidden" name="token" value="<?= $token ?>">
    <label for="new_password">Nueva Contraseña:</label>
    <input type="password" name="new_password" required>
    <br><br>
    
    <label for="confirm_password">Confirmar Contraseña:</label>
    <input type="password" name="confirm_password" required>
    <br><br>

    <input type="submit" value="Actualizar Contraseña">
</form>
<?php if (isset($_SESSION['error'])): ?>
    <p class="error"><?= $_SESSION['error'] ?></p>
<?php unset($_SESSION['error']); endif; ?>