<?php
    namespace Controllers;

    use Models\User;
    use Lib\Pages;
    use Services\UserService;
    use Services\ProductService;
    use Exception;

    class AuthController{
        private Pages $pages;
        private UserService $userServices;
        private ProductService $ProductService;

        public function __construct() {
            $this->pages = new Pages();
            $this->userServices = new UserService();
            $this->ProductService = new ProductService();
        }

        public function register() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($_POST['data']) {
                    $user = User::fromArray($_POST['data']);
                    if ($user->validation()) {
                        $password = password_hash($user->getPassword(), PASSWORD_BCRYPT, ['cost' => 5]);
                        $user->setPassword($password);
                        try {
                            $this->userServices->registerUser($user);
                        } catch (Exception $err) {
                            $_SESSION['register'] = 'Error';
                            $_SESSION['errores'] = User::getErrors();
                        }
                        if ($user->getRol() === 'admin') {
                            $_SESSION['role'] = 'admin';
                        } else {
                            $_SESSION['role'] = 'user';
                        }
                        $this->pages->render('Auth/login');
                    } else {
                        $_SESSION['register'] = 'Error';
                        $_SESSION['errores'] = User::getErrors();
                        $this->pages->render('Auth/registerForm');
                    }
                } else {
                    $_SESSION['register'] = 'Error'; // Si falla la conexión
                }
            } else {
                $this->pages->render('Auth/registerForm');
            }
        }

        public function login() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($_POST['data']) { 
                    try {
                        $usuario = $this->userServices->getUserByEmail($_POST['data']['email']);
                        if ($usuario && password_verify($_POST['data']['password'], $usuario->getPassword())) {
                            $_SESSION['user'] = $usuario->getNombre();
                            $_SESSION['user_id'] = $usuario->getId();
                            $_SESSION['user_email'] = $usuario->getEmail();
                            if ($usuario->getRol() === 'admin') {
                                $_SESSION['role'] = 'admin';
                            }else{
                                $_SESSION['role'] = 'user';
                            }
                            $product = $this->ProductService->getAllProducts();
                            $this->pages->render('Product/list',['products' => $product]);
                        } else {
                            $_SESSION['register'] = 'Error';
                            $_SESSION['errores'] = User::getErrors();
                            $this->pages->render('Auth/login', ['mensaje' => 'Usuario no logueado']);
                        }
                    } catch (Exception $err) {
                        $_SESSION['register'] = 'Error';
                        $_SESSION['errores'] = User::getErrors();
                        echo $err->getMessage();
                    }
                }
            } else {
                $this->pages->render('Auth/login');
            }
            
        }

        public function logout(): void{
            session_unset();
            session_destroy();
            $this->pages->render('Auth/login', ['mensaje' => 'Usuario deslogueado']);
            exit();
        }
    }