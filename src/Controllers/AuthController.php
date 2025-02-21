<?php
namespace Controllers;

use Models\User;
use Lib\Pages;
use Services\UserService;
use Services\ProductService;
use Lib\EmailSender;
use Exception;

class AuthController {
    private Pages $pages;
    private UserService $userServices;
    private ProductService $ProductService;
    private EmailSender $emailSender;

    public function __construct() {
        $this->pages = new Pages();
        $this->userServices = new UserService();
        $this->ProductService = new ProductService();
        $this->emailSender = new EmailSender();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['data']) {
                $user = User::fromArray($_POST['data']);
                if ($user->validation()) {
                    $password = password_hash($user->getPassword(), PASSWORD_BCRYPT, ['cost' => 5]);
                    $user->setPassword($password);
                    
                    // Generar token de verificación
                    $verificationToken = bin2hex(random_bytes(32));
                    $verificationTokenExpiry = time() + 600; // Token válido por 10 minutos
                    $user->setVerificationToken($verificationToken);
                    $user->setVerificationTokenExpiry($verificationTokenExpiry);
                    
                    try {
                        if ($this->userServices->registerUser($user)) {
                            // Enviar correo de verificación con token en cabecera
                            $this->emailSender->sendVerificationEmail($user->getEmail(), $verificationToken);
                            $this->pages->render('Auth/enterVerificationToken');
                            return;
                        }
                    } catch (Exception $err) {
                        $_SESSION['register'] = 'Error';
                        $_SESSION['errores'] = User::getErrors();
                    }
                } else {
                    $_SESSION['register'] = 'Error';
                    $_SESSION['errores'] = User::getErrors();
                }
            } else {
                $_SESSION['register'] = 'Error'; // Si falla la conexión
            }
            $this->pages->render('Auth/registerForm');
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

    public function logout(): void {
        session_unset();
        session_destroy();
        $this->pages->render('Auth/login', ['mensaje' => 'Usuario deslogueado']);
        exit();
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
    
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "El correo electrónico no es válido.";
                $this->pages->render('Auth/forgotPassword');
                return;
            }
    
            $usuario = $this->userServices->getUserByEmail($email);
            if (!$usuario) {
                $_SESSION['error'] = "No se encontró un usuario con ese correo.";
                $this->pages->render('Auth/forgotPassword');
                return;
            }
    
            $token = bin2hex(random_bytes(32));
            $expiry = time() + 600; // Token válido por 10 minutos
    
            if ($this->userServices->setResetToken($email, $token, $expiry)) {
                $this->emailSender->sendPasswordResetEmail($email, $token);
                $_SESSION['success'] = "Se han enviado instrucciones de restablecimiento a tu correo.";
                $this->pages->render('Auth/enterResetToken');
            } else {
                $_SESSION['error'] = "Hubo un problema al procesar tu solicitud.";
                $this->pages->render('Auth/forgotPassword');
            }
        } else {
            $this->pages->render('Auth/forgotPassword');
        }
    }

    public function validateResetToken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['reset_token'] ?? '';
            
            if (empty($token)) {
                $_SESSION['error'] = "Token no proporcionado.";
                $this->pages->render('Auth/enterResetToken');
                return;
            }
    
            $user = $this->userServices->getUserByResetToken($token);
            if (!$user || $this->userServices->isTokenExpired($user)) {
                $_SESSION['error'] = "El token no es válido o ha expirado.";
                $this->pages->render('Auth/enterResetToken');
                return;
            }
    
            $this->pages->render('Auth/resetPassword', ['reset_token' => $token]);
        } else {
            $this->pages->render('Auth/enterResetToken');
        }
    }
    
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['reset_token'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
    
            if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                $this->pages->render('Auth/resetPassword', ['reset_token' => $token]);
                return;
            }
    
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "Las contraseñas no coinciden.";
                $this->pages->render('Auth/resetPassword', ['reset_token' => $token]);
                return;
            }
    
            $user = $this->userServices->getUserByResetToken($token);
            if (!$user || $this->userServices->isTokenExpired($user)) {
                $_SESSION['error'] = "El token no es válido o ha expirado.";
                $this->pages->render('Auth/forgotPassword');
                return;
            }
    
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 5]);
            if ($this->userServices->updatePasswordAndClearToken($user->getEmail(), $hashedPassword)) {
                $_SESSION['success'] = "Contraseña actualizada con éxito.";
                $this->pages->render('Auth/login');
            } else {
                $_SESSION['error'] = "Hubo un problema al actualizar la contraseña.";
                $this->pages->render('Auth/resetPassword', ['reset_token' => $token]);
            }
        } else {
            $this->pages->render('Auth/forgotPassword');
        }
    }

    public function validateVerificationToken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['verification_token'] ?? '';
            
            if (empty($token)) {
                $_SESSION['error'] = "Token no proporcionado.";
                $this->pages->render('Auth/enterVerificationToken');
                return;
            }
    
            $user = $this->userServices->getUserByVerificationToken($token);
            if (!$user || $this->userServices->isVerificationTokenExpired($user)) {
                $_SESSION['error'] = "El token no es válido o ha expirado.";
                $this->pages->render('Auth/enterVerificationToken');
                return;
            }
    
            if ($this->userServices->verifyUser($user->getEmail())) {
                $_SESSION['success'] = "Cuenta verificada con éxito. Ahora puedes iniciar sesión.";
                $this->pages->render('Auth/login');
            } else {
                $_SESSION['error'] = "Hubo un problema al verificar tu cuenta.";
                $this->pages->render('Auth/enterVerificationToken');
            }
        } else {
            $this->pages->render('Auth/enterVerificationToken');
        }
    }

    public function verifyEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['verification_token'] ?? '';
            
            if (empty($token)) {
                $_SESSION['error'] = "Token no proporcionado.";
                $this->pages->render('Auth/enterVerificationToken');
                return;
            }
    
            $user = $this->userServices->getUserByVerificationToken($token);
            if (!$user || $this->userServices->isVerificationTokenExpired($user)) {
                $_SESSION['error'] = "El token no es válido o ha expirado.";
                $this->pages->render('Auth/enterVerificationToken');
                return;
            }
    
            if ($this->userServices->verifyUser($user->getEmail())) {
                $_SESSION['success'] = "Cuenta verificada con éxito. Puedes iniciar sesión ahora.";
                $this->pages->render('Auth/login');
            } else {
                $_SESSION['error'] = "Hubo un problema al verificar tu cuenta.";
                $this->pages->render('Auth/enterVerificationToken');
            }
        } else {
            $this->pages->render('Auth/enterVerificationToken');
        }
    }
}
