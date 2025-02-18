<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
</head>
<body>
    <h1>Bienvenido a mi Tienda</h1>
    <nav>
        <?php if (!isset($_SESSION['user'])): ?>
        <ul>          
            <li><a href="<?= BASE_URL ?>listProducts">Inicio</a></li>
            <li><a href="<?= BASE_URL ?>listProducts">Productos</a></li>
            <li><a href="<?= BASE_URL ?>listCategories">Categorías</a></li>
            <li><a href="<?= BASE_URL ?>cart">Carrito</a></li>
            <li><a href="<?= BASE_URL ?>Auth/login">Inicia Sesión</a></li>
            <li><a href="<?= BASE_URL ?>register">Regístrate</a></li>
        </ul>

        <?php else: ?>

        <h2>Hola <?= $_SESSION['user']?></h2>
        <ul>
            <li><a href="<?= BASE_URL ?>listProducts">Inicio</a></li>
            <li><a href="<?= BASE_URL ?>listProducts">Productos</a></li>
            <li><a href="<?= BASE_URL ?>listCategories">Categorías</a></li>
            <li><a href="<?= BASE_URL ?>myOrders">Mis pedidos</a></li>
            <li><a href="<?= BASE_URL ?>cart">Carrito</a></li>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <li><a href="<?= BASE_URL ?>CreateProducts">Administrar Productos</a></li>
            <li><a href="<?= BASE_URL ?>CreateCategories">Administrar Categorías</a></li>
            <li><a href="<?= BASE_URL ?>adminOrders">Administrar Pedidos</a></li>
        </ul>
        <?php endif; ?>
            <li><a href="<?= BASE_URL ?>logout">Cerrar Sesión</a></li>
    </nav>
<?php endif; ?>
