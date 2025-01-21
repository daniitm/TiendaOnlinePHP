<hr>
<h3>Listado de Categorías: </h3>

<?php if (!empty($categories)): ?>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li>
                <strong>ID:</strong> <?= $category->getId(); ?> -
                <strong>Nombre:</strong> <?= htmlspecialchars($category->getNombre(), ENT_QUOTES, 'UTF-8'); ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay categorías disponibles.</p>
<?php endif; ?>
<hr>