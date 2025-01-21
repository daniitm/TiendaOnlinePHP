<?php
namespace Models;

class User {
    protected static array $errores = [];
    
    public function __construct(
        private int|null $id, 
        private string $nombre, 
        private string $apellidos,
        private string $email, 
        private string $password,
        private string $rol
    ){}      

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setNombre(string $nombre) {
        $this->nombre = $nombre;
    }

    public function setApellidos(string $apellidos) {
        $this->apellidos = $apellidos;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function setRol(string $rol) {
        $this->rol = $rol;
    }

    public function getId(): int|null {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getApellidos(): string {
        return $this->apellidos;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRol(): string {
        return $this->rol;
    }

    public static function getErrors(): array {
        return self::$errores;
    }

    public function validation(): bool {
        self::$errores = [];
        if (empty($this->nombre)) {
            self::$errores['nombre'] = 'El nombre es obligatorio';
        }
        if (empty($this->apellidos)) {
            self::$errores['apellidos'] = 'El apellido es obligatorio';
        }
        if (empty($this->email)) {
            self::$errores['email'] = 'El email es obligatorio';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores['email'] = 'El email no es válido';
        }
        if (empty($this->password)) {
            self::$errores['password'] = 'La contraseña es obligatoria';
        }

        if (empty(self::$errores)) {
            $this->sanitize();
        }
        return empty(self::$errores);
    }

    public function sanitize(): void {
        $this->nombre = htmlspecialchars($this->nombre, ENT_QUOTES, 'UTF-8');
        $this->apellidos = htmlspecialchars($this->apellidos, ENT_QUOTES, 'UTF-8');
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
    }

    public static function fromArray(array $data): User {
        return new User(
            id: $data['id'] ?? null,
            nombre: $data['nombre'] ?? '',        
            apellidos: $data['apellidos'] ?? '', 
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            rol: $data['rol'] ?? 'user'        
        );
    }

    public static function toArray(User $user): array {
        return [
            'id' => $user->getId(),
            'nombre' => $user->getNombre(),
            'apellidos' => $user->getApellidos(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRol()  
        ];
    }
}
?>