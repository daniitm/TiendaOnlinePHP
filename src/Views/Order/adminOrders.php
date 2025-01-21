<hr>
<h3>Administración de Pedidos</h3>

<?php if (isset($_SESSION['message'])): ?>
    <p class="success"><?= $_SESSION['message']; ?></p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <p class="error"><?= $_SESSION['error']; ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (!empty($orders)): ?>
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Usuario</th>
                <th>Provincia</th>
                <th>Localidad</th>
                <th>Dirección</th>
                <th>Coste Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['provincia'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['localidad'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['direccion'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= number_format($order['coste'], 2, ',', '.'); ?> €</td>
                    <td><?= htmlspecialchars($order['estado'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['fecha'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($order['hora'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php if ($order['estado'] !== 'finalizado'): ?>
                            <form method="POST" action="changeOrderStatus">
                                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                <input type="hidden" name="new_status" value="finalizado">
                                <button type="submit">Marcar como Finalizado</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay pedidos registrados.</p>
<?php endif; ?>
<hr>