<?php
namespace Controllers;

use Services\ProductService;
use Lib\Pages;

class CartController {
    private Pages $pages;
    private ProductService $productService;

    public function __construct() {
        $this->pages = new Pages();
        $this->productService = new ProductService();
    }

    public function showCart() {
        // Recuperar carrito desde la cookie si existe
        if (!isset($_SESSION['cart']) && isset($_COOKIE['cart'])) {
            $_SESSION['cart'] = json_decode($_COOKIE['cart'], true);
        }
    
        $cart = $_SESSION['cart'] ?? [];
    
        $this->pages->render('Cart/cart', ['cart' => $cart]);
    }

    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
    
            if ($productId) {
                $product = $this->productService->getProductById((int)$productId);
    
                if ($product) {
                    // Agregar al carrito en la sesión
                    $_SESSION['cart'][$productId] = [
                        'id' => $product->getId(),
                        'nombre' => $product->getNombre(),
                        'precio' => $product->getPrecio(),
                        'cantidad' => ($_SESSION['cart'][$productId]['cantidad'] ?? 0) + 1
                    ];
                    
                    // Guardar el carrito en una cookie
                    setcookie('cart', json_encode($_SESSION['cart']), time() + (86400 * 30), "/");
                }
            }
            header('Location: listProducts');
            exit;
        }
    }

    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;

            if ($productId && isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
            }
            header('Location: cart');
            exit;
        }
    }

    public function checkout() {
        if (!isset($_SESSION['user'])) {
            header('Location: Auth/login');
            exit;
        }
        header('Location: checkoutForm');
        exit;
    }
}