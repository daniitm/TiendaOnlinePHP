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
                    $stockDisponible = $product->getStock();
                    $cantidadActual = $_SESSION['cart'][$productId]['cantidad'] ?? 0;
    
                    if ($cantidadActual < $stockDisponible) {
                        $_SESSION['cart'][$productId] = [
                            'id' => $product->getId(),
                            'nombre' => $product->getNombre(),
                            'precio' => $product->getPrecio(),
                            'cantidad' => $cantidadActual + 1
                        ];
                        
                        setcookie('cart', json_encode($_SESSION['cart']), time() + (86400 * 30), "/");
                    } else {
                        $_SESSION['error'] = "No puedes agregar más unidades de {$product->getNombre()}. Stock disponible: $stockDisponible.";
                    }
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
    
        $cart = $_SESSION['cart'] ?? [];
        foreach ($cart as $item) {
            $product = $this->productService->getProductById((int)$item['id']);
            if ($product && $item['cantidad'] > $product->getStock()) {
                $_SESSION['error'] = "No puedes finalizar la compra. El producto {$item['nombre']} solo tiene {$product->getStock()} unidades disponibles.";
                header('Location: cart');
                exit;
            }
        }
    
        header('Location: checkoutForm');
        exit;
    }
}