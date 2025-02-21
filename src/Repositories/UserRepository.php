<?php
namespace Repositories;

use Lib\BaseDatos;
use Models\User;
use PDO;
use PDOException;

class UserRepository {
    private BaseDatos $db;
    
    public function __construct() {
        $this->db = new BaseDatos();
    }

    public function registerUser(User $usuario): bool {
        try {
            $ins = $this->db->prepare("INSERT INTO usuarios (nombre, apellidos, email, password, rol, verification_token, verification_token_expiry, is_verified) VALUES (:nombre, :apellidos, :email, :password, :rol, :verification_token, :verification_token_expiry, :is_verified)");
    
            $ins->bindValue(':nombre', $usuario->getNombre());
            $ins->bindValue(':apellidos', $usuario->getApellidos());
            $ins->bindValue(':email', $usuario->getEmail());
            $ins->bindValue(':password', $usuario->getPassword());
            $ins->bindValue(':rol', $usuario->getRol());
            $ins->bindValue(':verification_token', $usuario->getVerificationToken());
            $ins->bindValue(':verification_token_expiry', $usuario->getVerificationTokenExpiry());
            $ins->bindValue(':is_verified', false);
    
            return $ins->execute();
        } catch (PDOException $err) {
            error_log("Error al crear el usuario: " . $err->getMessage());
            return false;
        }
    }

    public function setVerificationToken(string $email, string $token, int $expiry): bool {
        try {
            $stmt = $this->db->prepare("UPDATE usuarios SET verification_token = :token, verification_token_expiry = :expiry WHERE email = :email");
            $stmt->bindValue(':token', $token);
            $stmt->bindValue(':expiry', $expiry);
            $stmt->bindValue(':email', $email);
            return $stmt->execute();
        } catch (PDOException $err) {
            error_log("Error al establecer el token de verificacion: " . $err->getMessage());
            return false;
        }
    }
    
    public function getUserByVerificationToken(string $token): ?User {
        try {
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE verification_token = :token AND verification_token_expiry > :now LIMIT 1");
            $stmt->bindValue(':token', $token);
            $stmt->bindValue(':now', time());
            $stmt->execute();
    
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return User::fromArray($data);
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error al obtener el usuario por token de verificación: " . $e->getMessage());
            return null;
        }
    }
    
    public function verifyUser(string $email): bool {
        try {
            $stmt = $this->db->prepare("UPDATE usuarios SET is_verified = 1, verification_token = NULL, verification_token_expiry = NULL WHERE email = :email");
            $stmt->bindValue(':email', $email);
            return $stmt->execute();
        } catch (PDOException $err) {
            error_log("Error al verificar el usuario: " . $err->getMessage());
            return false;
        }
    }

    public function getUserByEmail(string $email): ?User {
        try {
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
    
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return User::fromArray($data);
            }
            return null;
        } catch (PDOException $err) {
            error_log("Error al obtener el usuario por email: " . $err->getMessage());
            return null;
        }
    }

    public function getUserEmailById(int $userId): ?string {
        try {
            $stmt = $this->db->prepare("SELECT email FROM usuarios WHERE id = :id LIMIT 1");
            $stmt->bindValue(':id', $userId);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['email'] : null;
        } catch (PDOException $err) {
            error_log("Error al obtener el email del usuario: " . $err->getMessage());
            return null;
        }
    }

    public function updatePassword(string $email, string $newPassword): bool {
        try {
            $stmt = $this->db->prepare("UPDATE usuarios SET password = :password WHERE email = :email");
            $stmt->bindValue(':password', $newPassword);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return true;
        } catch (PDOException $err) {
            error_log("Error al actualizar la contraseña: " . $err->getMessage());
            return false;
        }
    }

    public function setResetToken(string $email, string $token, int $expiry): bool {
        try {
            $stmt = $this->db->prepare("UPDATE usuarios SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email");
            $stmt->bindValue(':token', $token);
            $stmt->bindValue(':expiry', $expiry);
            $stmt->bindValue(':email', $email);
            return $stmt->execute();
        } catch (PDOException $err) {
            error_log("Error al establecer el token de restablecimiento: " . $err->getMessage());
            return false;
        }
    }

    public function getUserByResetToken(string $token): ?User {
        try {
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE reset_token = :token AND reset_token_expiry > :now LIMIT 1");
            $stmt->bindValue(':token', $token);
            $stmt->bindValue(':now', time());
            $stmt->execute();
    
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return User::fromArray($data);
            }
            return null;
        } catch (PDOException $err) {
            error_log("Error al obtener el usuario por token: " . $err->getMessage());
            return null;
        }
    }

    public function updatePasswordAndClearToken(string $email, string $newPassword): bool {
        try {
            $stmt = $this->db->prepare("UPDATE usuarios SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE email = :email");
            $stmt->bindValue(':password', $newPassword);
            $stmt->bindValue(':email', $email);
            return $stmt->execute();
        } catch (PDOException $err) {
            error_log("Error al actualizar la contraseña y limpiar el token: " . $err->getMessage());
            return false;
        }
    }
}