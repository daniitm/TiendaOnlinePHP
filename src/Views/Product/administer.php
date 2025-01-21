<hr>
<h3>Agregar Producto a la tienda: </h3>
<form action="<?= BASE_URL ?>CreateProducts" method='POST'>
    <label for="categoria_id">Id de la categoría:</label>
    <input type="number" name="data[categoria_id]" value="<?= $_POST['data']['categoria_id'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['categoria_id'])): ?>
        <p class="error"><?= $_SESSION['errores']['categoria_id'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="nombre">Nombre:</label>
    <input type="text" name="data[nombre]" value="<?= $_POST['data']['nombre'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['nombre'])): ?>
        <p class="error"><?= $_SESSION['errores']['nombre'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="descripcion">Descripción:</label>
    <input type="text" name="data[descripcion]" value="<?= $_POST['data']['descripcion'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['descripcion'])): ?>
        <p class="error"><?= $_SESSION['errores']['descripcion'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="precio">Precio:</label>
    <input type="number" step="0.01" name="data[precio]" value="<?= $_POST['data']['precio'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['precio'])): ?>
        <p class="error"><?= $_SESSION['errores']['precio'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="stock">Stock:</label>
    <input type="number" name="data[stock]" value="<?= $_POST['data']['stock'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['stock'])): ?>
        <p class="error"><?= $_SESSION['errores']['stock'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="oferta">Oferta (%):</label>
    <input type="number" step="0.01" name="data[oferta]" value="<?= $_POST['data']['oferta'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['oferta'])): ?>
        <p class="error"><?= $_SESSION['errores']['oferta'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="imagen">Imagen (url):</label>
    <input type="text" name="data[imagen]" value="<?= $_POST['data']['imagen'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['imagen'])): ?>
        <p class="error"><?= $_SESSION['errores']['imagen'] ?></p>
    <?php endif; ?>
    <br><br>

    <input type="submit" value="Agregar Producto">
</form>
<hr>
<h3>Editar Producto:</h3>
<form action="<?= BASE_URL ?>EditProducts" method="POST">
<label for="id">ID de la Producto:</label>
    <input type="number" name="data[id]" value="<?= $_POST['data']['id'] ?? '' ?>" required>
    <br><br>

    <label for="categoria_id">Editar id de la categoría:</label>
    <input type="number" name="data[categoria_id]" value="<?= $_POST['data']['categoria_id'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['categoria_id'])): ?>
        <p class="error"><?= $_SESSION['errores']['categoria_id'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="nombre">Editar Nombre:</label>
    <input type="text" name="data[nombre]" value="<?= $_POST['data']['nombre'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['nombre'])): ?>
        <p class="error"><?= $_SESSION['errores']['nombre'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="descripcion">Editar Descripción:</label>
    <input type="text" name="data[descripcion]" value="<?= $_POST['data']['descripcion'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['descripcion'])): ?>
        <p class="error"><?= $_SESSION['errores']['descripcion'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="precio">Editar Precio:</label>
    <input type="number" step="0.01" name="data[precio]" value="<?= $_POST['data']['precio'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['precio'])): ?>
        <p class="error"><?= $_SESSION['errores']['precio'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="stock">Editar Stock:</label>
    <input type="number" name="data[stock]" value="<?= $_POST['data']['stock'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['stock'])): ?>
        <p class="error"><?= $_SESSION['errores']['stock'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="oferta">Editar Oferta (%):</label>
    <input type="number" step="0.01" name="data[oferta]" value="<?= $_POST['data']['oferta'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['oferta'])): ?>
        <p class="error"><?= $_SESSION['errores']['oferta'] ?></p>
    <?php endif; ?>
    <br><br>

    <label for="imagen">Editar Imagen (url):</label>
    <input type="text" name="data[imagen]" value="<?= $_POST['data']['imagen'] ?? '' ?>"required>
    <?php if (isset($_SESSION['errores']['imagen'])): ?>
        <p class="error"><?= $_SESSION['errores']['imagen'] ?></p>
    <?php endif; ?>
    <br><br>
    
    <input type="submit" value="Editar Producto">
</form>
<hr>
<h3>Eliminar Producto:</h3>
<form action="<?= BASE_URL ?>DeleteProducts" method="POST">
<label for="id">ID de la Producto:</label>
    <input type="number" name="data[id]" value="<?= $_POST['data']['id'] ?? '' ?>" required>
    <?php if (isset($_SESSION['errores']['id'])): ?>
        <p class="error"><?= $_SESSION['errores']['id'] ?></p>
    <?php endif; ?>
    <br><br>

    <input type="submit" value="Eliminar Producto">
</form>

<?php
// Limpiar errores después de mostrarlos
if (isset($_SESSION['errores'])) {
    unset($_SESSION['errores']);
}
?>
<hr>