<?php
namespace Controllers;

use Models\Product;
use Services\ProductService;
use Lib\Pages;
use Exception;

class ProductController {
    private Pages $pages;
    private ProductService $productService;

    public function __construct() {
        $this->pages = new Pages();
        $this->productService = new ProductService();
    }

    public function list() {
        $products = $this->productService->getAllProducts();
        $this->pages->render('Product/list', ['products' => $products]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            var_dump($_POST);

            if ($_POST['data']) {                
                $product = Product::fromArray($_POST['data']);

                var_dump($product);

                var_dump($product->validation());

                if ($product->validation()) {
                    try {
                        $this->productService->registerProduct($product);
                        $_SESSION['create'] = 'Success';
                        header('Location: listProducts'); //Redirige a la lista de productos
                        exit;
                    } catch (Exception $e) {
                        $_SESSION['create'] = 'Error';
                        $_SESSION['errores'] = Product::getErrors();
                    }
                } else {
                    
                    var_dump(Product::getErrors());

                    $_SESSION['create'] = 'Error';
                    $_SESSION['errores'] = Product::getErrors();
                }
            } else {
                $_SESSION['create'] = 'Error'; //Si falla la conexión
            }
        } else {
            $this->pages->render('Product/administer');
        }
    }

    public function edit(int $id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['data'])) {
                $data = $_POST['data'];
                $id = $data['id'] ?? null;
    
                if ($id) {
                    $product = Product::fromArray($data);
                    $product->setId($id);
    
                    if ($product->validation()) {
                        try {
                            $this->productService->updateProduct($product);
                            $_SESSION['update'] = 'Success';
                            header('Location: listProducts'); //Redirige a la lista de productos
                            exit;
                        } catch (Exception $e) {
                            $_SESSION['update'] = 'Error';
                            $_SESSION['errores'] = Product::getErrors();
                        }
                    } else {
                        $_SESSION['update'] = 'Error';
                        $_SESSION['errores'] = Product::getErrors();
                    }
                } else {
                    $_SESSION['update'] = 'Error';
                    $_SESSION['errores']['id'] = 'El ID del producto es obligatorio.';
                }
            } else {
                $_SESSION['update'] = 'Error';
            }
        } else {
            $this->pages->render('Product/administer');
        }
    }

    public function delete(int $id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['data']['id'] ?? null;
    
            if ($id) {
                try {
                    $this->productService->deleteProduct($id);
                    $_SESSION['delete'] = 'Success';
                    header('Location: listProducts'); //Redirige a la lista de producto
                    exit;
                } catch (Exception $e) {
                    $_SESSION['delete'] = 'Error';
                    $_SESSION['errores']['id'] = 'Error al eliminar el producto.';
                }
            } else {
                $_SESSION['delete'] = 'Error';
                $_SESSION['errores']['id'] = 'El ID del producto es obligatorio.';
            }
        } else {
            header('Location: listProducts'); //Redirige a la lista de producto
            exit;
        }
    }
}