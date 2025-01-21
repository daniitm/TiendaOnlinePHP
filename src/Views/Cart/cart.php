<hr>
<h3>Carrito de Compras</h3>

<?php if (isset($_SESSION['error'])): ?>
    <div class="error-message" style="color: red; margin-bottom: 10px;">
        <?php echo $_SESSION['error']; ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($cart)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?= $item['id']; ?></td>
                    <td><?= htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= number_format($item['precio'], 2, ',', '.'); ?> €</td>
                    <td><?= $item['cantidad']; ?></td>
                    <td><?= number_format($item['precio'] * $item['cantidad'], 2, ',', '.'); ?> €</td>
                    <td>
                        <form method="POST" action="removeFromCart">
                            <input type="hidden" name="product_id" value="<?= $item['id']; ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php $total += $item['precio'] * $item['cantidad']; ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="4"><strong>Total:</strong></td>
                <td><strong><?= number_format($total, 2, ',', '.'); ?> €</strong></td>
                <td></td>
                <?php if (isset($_SESSION['user'])): ?>
                    <td><a href="checkout"><input type="button" value="Finalizar compra"></a></td>
                <?php else: ?>
                    <td><a href="Auth/login"><button>Iniciar sesión para finalizar compra</button></a></td>
                <?php endif; ?>
            </tr>
        </tbody>
    </table>
<?php else: ?>
    <p>Tu carrito está vacío.</p>
<?php endif; ?>
<hr>
