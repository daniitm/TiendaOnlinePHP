<?php
namespace Models;

class Category {
    protected static array $errores = [];

    public function __construct(
        private int|null $id,
        private string $nombre
    ) {}

    public static function fromArray(array $data): Category {
        return new Category(
            id: $data['id'] ?? null,
            nombre: $data['nombre'] ?? ''
        );
    }

    public function validation(): bool {
        self::$errores = [];
        if (empty($this->nombre)) {
            self::$errores['nombre'] = 'El nombre es obligatorio';
        }
        return empty(self::$errores);
    }

    public function sanitize(): void {
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
    }

    public static function getErrors(): array {
        return self::$errores;
    }

    public function setId(int|null $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function getId(): int|null {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }
}
?>