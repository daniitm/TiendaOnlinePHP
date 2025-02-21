<?php
namespace Lib;

class Middleware {
    public static function isLoggedIn() {
        if (!isset($_SESSION['user'])) {
            header('Location: /Tienda25/Auth/login'); 
            exit();
        }
    }

    public static function isAdmin() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /Tienda25/listProducts'); 
            exit();
        }
    }
}

?>