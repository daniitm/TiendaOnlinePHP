<hr>
<h3>Listado de Productos: </h3>

<?php if (!empty($products)): ?>
    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <strong>ID:</strong> <?= $product->getId(); ?> -
                <strong>Nombre:</strong> <?= htmlspecialchars($product->getNombre(), ENT_QUOTES, 'UTF-8'); ?> -
                <strong>Descripción:</strong> <?= htmlspecialchars($product->getDescripcion(), ENT_QUOTES, 'UTF-8'); ?> -
                <strong>Precio:</strong> <?= number_format($product->getPrecio(), 2, ',', '.'); ?> € -
                <strong>Stock:</strong> <?= $product->getStock(); ?> -
                <strong>Oferta:</strong> <?= $product->getOferta() * 100; ?>% -
                <strong>Fecha:</strong> <?= $product->getFecha(); ?> -
                <strong>Imagen:</strong> <img src="<?= htmlspecialchars($product->getImagen(), ENT_QUOTES, 'UTF-8'); ?>" alt="Imagen de <?= htmlspecialchars($product->getNombre(), ENT_QUOTES, 'UTF-8'); ?>" width="100" />
                <form method="POST" action="addToCart" style="display: inline;">
                    <input type="hidden" name="product_id" value="<?= $product->getId(); ?>">
                    <button type="submit">Añadir al carrito</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay productos disponibles.</p>
<?php endif; ?>
<hr>