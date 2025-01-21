<hr>
<h3>Mis Pedidos</h3>

<?php if (!empty($orders)): ?>
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Provincia</th>
                <th>Localidad</th>
                <th>Dirección</th>
                <th>Coste Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['provincia'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['localidad'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['direccion'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= number_format($order['coste'], 2, ',', '.'); ?> €</td>
                    <td><?= htmlspecialchars($order['estado'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['fecha'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['hora'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No tienes pedidos realizados.</p>
<?php endif; ?>
<hr>