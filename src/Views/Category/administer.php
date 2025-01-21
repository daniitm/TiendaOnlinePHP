<hr>
<h3>Crear Categoría:</h3>
<form action="<?= BASE_URL ?>CreateCategories" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" name="data[nombre]" value="<?= $_POST['data']['nombre'] ?? '' ?>" required>
    <?php if (isset($_SESSION['errores']['nombre'])): ?>
        <p class="error"><?= $_SESSION['errores']['nombre'] ?></p>
    <?php endif; ?>
    <br><br>

    <input type="submit" value="Agregar Categoría">
</form>
<hr>
<h3>Editar Categoría:</h3>
<form action="<?= BASE_URL ?>EditCategory" method="POST">
<label for="id">ID de la Categoría:</label>
    <input type="number" name="data[id]" value="<?= $_POST['data']['id'] ?? '' ?>" required>
    <br>
    <label for="nombre">Nuevo Nombre:</label>
    <input type="text" name="data[nombre]" value="<?= $_POST['data']['nombre'] ?? '' ?>" required>
    <?php if (isset($_SESSION['errores']['nombre'])): ?>
        <p class="error"><?= $_SESSION['errores']['nombre'] ?></p>
    <?php endif; ?>
    <br><br>
    
    <input type="submit" value="Editar Categoría">
</form>
<hr>
<h3>Eliminar Categoría:</h3>
<form action="<?= BASE_URL ?>DeleteCategory" method="POST">
<label for="id">ID de la Categoría:</label>
    <input type="number" name="data[id]" value="<?= $_POST['data']['id'] ?? '' ?>" required>
    <?php if (isset($_SESSION['errores']['id'])): ?>
        <p class="error"><?= $_SESSION['errores']['id'] ?></p>
    <?php endif; ?>
    <br><br>

    <input type="submit" value="Eliminar Categoría">
</form>

<?php
// Limpiar errores después de mostrarlos
if (isset($_SESSION['errores'])) {
    unset($_SESSION['errores']);
}
?>
<hr>