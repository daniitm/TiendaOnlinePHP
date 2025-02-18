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
                    $user->setVerificationToken($verificationToken);
                    
                    try {
                        if ($this->userServices->registerUser($user)) {
                            // Enviar correo de verificación
                            $verificationLink = BASE_URL . "Auth/verifyEmail?token=" . $verificationToken;
                            $this->emailSender->sendVerificationEmail($user->getEmail(), $verificationLink);
                            
                            $_SESSION['success'] = "Se ha enviado un correo de verificación a tu dirección de email.";
                            $this->pages->render('Auth/registerSuccess');
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
    
            $token = bin2hex(random_bytes(32)); // Genera un token seguro
            $expiry = time() + 3600; // Token válido por 1 hora
    
            if ($this->userServices->setResetToken($email, $token, $expiry)) {
                $resetLink = BASE_URL . "Auth/resetPassword?token=" . $token;
                $this->emailSender->sendPasswordResetEmail($email, $resetLink);
                $_SESSION['success'] = "Se ha enviado un enlace de restablecimiento a tu correo.";
                $this->pages->render('Auth/login');
            } else {
                $_SESSION['error'] = "Hubo un problema al procesar tu solicitud.";
                $this->pages->render('Auth/forgotPassword');
            }
        } else {
            $this->pages->render('Auth/forgotPassword');
        }
    }

    public function resetPassword($token = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
    
            if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                $this->pages->render('Auth/resetPassword', ['token' => $token]);
                return;
            }
    
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "Las contraseñas no coinciden.";
                $this->pages->render('Auth/resetPassword', ['token' => $token]);
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
                $this->pages->render('Auth/resetPassword', ['token' => $token]);
            }
        } else {
            if (empty($token)) {
                $_SESSION['error'] = "Token no proporcionado.";
                $this->pages->render('Auth/forgotPassword');
                return;
            }
            $this->pages->render('Auth/resetPassword', ['token' => $token]);
        }
    }

    public function verifyEmail($token = null) {
        if (empty($token)) {
            $_SESSION['error'] = "Token de verificación no proporcionado.";
            $this->pages->render('Auth/login');
            return;
        }
    
        $user = $this->userServices->getUserByVerificationToken($token);
        if (!$user) {
            $_SESSION['error'] = "Token de verificación inválido.";
            $this->pages->render('Auth/login');
            return;
        }
    
        if ($this->userServices->verifyUser($user->getEmail())) {
            $_SESSION['success'] = "Email verificado con éxito. Ahora puedes iniciar sesión.";
            $this->pages->render('Auth/login');
        } else {
            $_SESSION['error'] = "Hubo un problema al verificar tu email.";
            $this->pages->render('Auth/login');
        }
    }
}