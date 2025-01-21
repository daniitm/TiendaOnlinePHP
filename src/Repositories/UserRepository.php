<?php
    namespace Repositories;

    use Lib\BaseDatos;
    use Models\User;
    use PDO;
    use PDOException;

    class UserRepository{
        private BaseDatos $db;
        
        public function __construct(){
            $this->db = new BaseDatos();
        }

        public function registerUser(User $usuario): bool {
            try {
                $ins = $this->db->prepare("INSERT INTO usuarios (nombre, apellidos, email, password, rol) VALUES (:nombre, :apellidos, :email, :password, :rol)");
    
                $ins->bindValue(':nombre', $usuario->getNombre());
                $ins->bindValue(':apellidos', $usuario->getApellidos());
                $ins->bindValue(':email', $usuario->getEmail());
                $ins->bindValue(':password', $usuario->getPassword());
                $ins->bindValue(':rol', $usuario->getRol());
    
                $ins->execute();
                return true;
            } catch (PDOException $err) {
                error_log("Error al crear el usuario: " . $err->getMessage());
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
    }


    