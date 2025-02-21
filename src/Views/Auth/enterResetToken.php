<h3>Ingresar Token de Restablecimiento</h3>
<form action="<?= BASE_URL ?>Auth/validateResetToken" method="POST">
    <label for="reset_token">Token de Restablecimiento:</label>
    <input type="text" name="reset_token" required>
    <br><br>
    <input type="submit" value="Validar Token">
</form>
<?php if (isset($_SESSION['error'])): ?>
    <p class="error"><?= $_SESSION['error'] ?></p>
<?php unset($_SESSION['error']); endif; ?>