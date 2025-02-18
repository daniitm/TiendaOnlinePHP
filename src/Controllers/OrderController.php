<?php
namespace Controllers;

use Models\Order;
use Services\OrderService;
use Lib\Pages;
use Services\ProductService;
use Lib\EmailSender;
use Services\UserService;

class OrderController {
    private Pages $pages;
    private OrderService $orderService;
    private ProductService $productService;
    private UserService $userService;

    public function __construct() {
        $this->pages = new Pages();
        $this->orderService = new OrderService();
        $this->productService = new ProductService();
        $this->userService = new UserService();
    }

    public function placeOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user'])) {
                header('Location: Auth/login');
                exit;
            }
    
            $userId = $_SESSION['user_id'] ?? null;
            if ($userId === null) {
                header('Location: Auth/login');
                exit;
            }
    
            $cart = $_SESSION['cart'] ?? [];
    
            // Verificar si el pago se hizo con PayPal
            $isPayPal = isset($_POST['paypal_order_id']);
    
            // Si no es PayPal, verificar stock antes de continuar
            if (!$isPayPal) {
                $stockError = $this->checkStock($cart);
                if ($stockError) {
                    $_SESSION['error'] = $stockError;
                    header('Location: cart');
                    exit;
                }
            }
    
            $totalCost = array_reduce($cart, fn($carry, $item) => $carry + ($item['precio'] * $item['cantidad']), 0);
    
            $orderData = [
                'usuario_id' => $userId,
                'provincia' => $_POST['data']['provincia'] ?? '',
                'localidad' => $_POST['data']['localidad'] ?? '',
                'direccion' => $_POST['data']['direccion'] ?? '',
                'coste' => $totalCost,
                'estado' => 'pendiente',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s'),
                'metodo_pago' => $isPayPal ? 'PayPal' : 'Transferencia'
            ];
    
            $order = Order::fromArray($orderData);
    
            if ($order->validation()) {
                $orderId = $this->orderService->createOrderWithLines($order, $cart);
    
                if ($orderId) {
                    foreach ($cart as $item) {
                        $this->productService->updateStock($item['id'], $item['cantidad']);
                    }
    
                    // Enviar correo de confirmación
                    $emailSender = new EmailSender();
                    $emailSender->sendOrderConfirmation(
                        $_SESSION['user_email'], 
                        $orderId, 
                        $cart, 
                        $totalCost, 
                        $_SESSION['user']
                    );
    
                    // Vaciar carrito
                    unset($_SESSION['cart']);
                    setcookie('cart', '', time() - 3600, '/');
    
                    // Si el pago fue con PayPal, responder con JSON
                    if ($isPayPal) {
                        echo json_encode(['success' => true, 'redirect' => BASE_URL . 'orderSuccess']);
                        exit;
                    }
    
                    header('Location: orderSuccess');
                    exit;
                } else {
                    $_SESSION['errores'] = ['general' => 'Error al crear el pedido'];
                    header('Location: checkoutForm');
                    exit;
                }
            } else {
                $_SESSION['errores'] = Order::getErrors();
                $_SESSION['old_input'] = $_POST['data'];
                header('Location: checkoutForm');
                exit;
            }
        }
    }

    private function checkStock($cart) {
        foreach ($cart as $item) {
            $productId = $item['id'];
            $requestedQuantity = $item['cantidad'];
            $availableStock = $this->productService->getProductStock($productId);
    
            if ($requestedQuantity > $availableStock) {
                return "No hay suficiente stock para el producto '{$item['nombre']}'. Stock disponible: $availableStock";
            }
        }
        return null; 
    }
    
    public function checkoutForm() {
        if (!isset($_SESSION['user'])) {
            header('Location: Auth/login');
            exit;
        }
    
        $data = [
            'errores' => $_SESSION['errores'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? []
        ];
    
        unset($_SESSION['errores'], $_SESSION['old_input']);
    
        $this->pages->render('Order/checkoutForm', $data);
    }

    public function orderSuccess() {
        $this->pages->render('Order/success');
    }

    public function listUserOrders() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: Auth/login');
            exit;
        }
    
        $userId = $_SESSION['user_id'];
        $orders = $this->orderService->getOrdersByUserId($userId);
    
        $this->pages->render('Order/userOrders', ['orders' => $orders]);
    }

    public function listAllOrders() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: Auth/login');
            exit;
        }
    
        $orders = $this->orderService->getAllOrders();
        $this->pages->render('Order/adminOrders', ['orders' => $orders]);
    }
    
    public function changeOrderStatus() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'Auth/login');
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $newStatus = $_POST['new_status'] ?? null;
    
            if ($orderId && $newStatus) {
                $success = $this->orderService->updateOrderStatus($orderId, $newStatus);
                if ($success) {
                    $_SESSION['message'] = "Estado del pedido actualizado correctamente.";
                } else {
                    $_SESSION['error'] = "Error al actualizar el estado del pedido.";
                }
            }
        }
    
        header('Location: ' . BASE_URL . 'adminOrders');
        exit;
    }



    private function validatePayPalPayment($orderId, $expectedAmount) {
        $clientId = 'AV6E4QJr36DZTOTt7fjHFGoKjwF-5UYGFuoty4l4OBeIwi7Galu3oQOBrFS0b_bwLGrY5SQp9ozZlitN';
        $secret = 'TU_SECRET_PAYPAL';
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderId");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic " . base64_encode("$clientId:$secret"),
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
    
        return isset($response['status']) && $response['status'] === 'COMPLETED' && 
               isset($response['purchase_units'][0]['amount']['value']) && 
               (float)$response['purchase_units'][0]['amount']['value'] == (float)$expectedAmount;
    }
}
?>
