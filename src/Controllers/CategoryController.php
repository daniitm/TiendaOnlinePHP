<?php
namespace Controllers;

use Models\Category;
use Services\CategoryService;
use Lib\Pages;
use Exception;

class CategoryController {
    private Pages $pages;
    private CategoryService $categoryService;

    public function __construct() {
        $this->pages = new Pages();
        $this->categoryService = new CategoryService();
    }

    public function list() {
        $categories = $this->categoryService->getAllCategories();
        $this->pages->render('Category/list', ['categories' => $categories]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['data']) {
                $category = Category::fromArray($_POST['data']);
                if ($category->validation()) {
                    try {
                        $this->categoryService->registerCategory($category);
                        $_SESSION['create'] = 'Success';
                        header('Location: listCategories'); //Redirige a la lista de categorias
                        exit;
                    } catch (Exception $e) {
                        $_SESSION['create'] = 'Error';
                        $_SESSION['errores'] = Category::getErrors();
                    }
                } else {
                    $_SESSION['create'] = 'Error';
                    $_SESSION['errores'] = Category::getErrors();
                }
            } else {
                $_SESSION['create'] = 'Error'; //Si falla la conexion
            }
        } else {
            $this->pages->render('Category/administer');
        }
    }

    public function edit(int $id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['data'])) {
                $data = $_POST['data'];
                $id = $data['id'] ?? null;
    
                if ($id) {
                    $category = Category::fromArray($data);
                    $category->setId($id);
    
                    if ($category->validation()) {
                        try {
                            $this->categoryService->updateCategory($category);
                            $_SESSION['update'] = 'Success';
                            header('Location: listCategories'); //Redirige a la lista de categorias
                            exit;
                        } catch (Exception $e) {
                            $_SESSION['update'] = 'Error';
                            $_SESSION['errores'] = Category::getErrors();
                        }
                    } else {
                        $_SESSION['update'] = 'Error';
                        $_SESSION['errores'] = Category::getErrors();
                    }
                } else {
                    $_SESSION['update'] = 'Error';
                    $_SESSION['errores']['id'] = 'El ID de la categoría es obligatorio.';
                }
            } else {
                $_SESSION['update'] = 'Error';
            }
        } else {
            $this->pages->render('Category/administer');
        }
    }

    public function delete(int $id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['data']['id'] ?? null;
    
            if ($id) {
                try {
                    $this->categoryService->deleteCategory($id);
                    $_SESSION['delete'] = 'Success';
                    header('Location: listCategories'); //Redirige a la lista de categorias
                    exit;
                } catch (Exception $e) {
                    $_SESSION['delete'] = 'Error';
                    $_SESSION['errores']['id'] = 'Error al eliminar la categoría.';
                }
            } else {
                $_SESSION['delete'] = 'Error';
                $_SESSION['errores']['id'] = 'El ID de la categoría es obligatorio.';
            }
        } else {
            header('Location: listCategories'); //Redirige a la lista de categorias
            exit;
        }
    }
}
?>