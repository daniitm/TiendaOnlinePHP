<?php
namespace Models;

class Product {
    protected static array $errores = [];

    public function __construct(
        private int|null $id,
        private int $categoria_id,
        private string $nombre,
        private string $descripcion,
        private float $precio,
        private int $stock,
        private float $oferta,
        private string $fecha,
        private string $imagen
    ) {}

    public static function fromArray(array $data): Product {
        return new Product(
            id: $data['id'] ?? null,
            categoria_id: (int)($data['categoria_id'] ?? 0),
            nombre: htmlspecialchars(strip_tags($data['nombre'] ?? '')),
            descripcion: htmlspecialchars(strip_tags($data['descripcion'] ?? '')),
            precio: (float)($data['precio'] ?? 0),
            stock: (int)($data['stock'] ?? 0),
            oferta: (float)($data['oferta'] ?? 0), 
            fecha: $data['fecha'] ?? date('Y-m-d H:i:s'), 
            imagen: htmlspecialchars(strip_tags($data['imagen'] ?? ''))
        );
    }

    public function validation(): bool {
        self::$errores = [];
        if (empty($this->nombre)) {
            self::$errores['nombre'] = 'El nombre es obligatorio';
        }
        if (empty($this->descripcion)) {
            self::$errores['descripcion'] = 'La descripción es obligatoria';
        }
        if ($this->precio <= 0) {
            self::$errores['precio'] = 'El precio debe ser mayor a 0';
        }
        if ($this->stock < 0) {
            self::$errores['stock'] = 'El stock no puede ser negativo';
        }
        if (empty($this->oferta !== null) && ($this->oferta < 0 || $this->oferta > 100)) {
            self::$errores['oferta'] = 'La oferta debe ser un porcentaje entre 0 y 100';
        }
        if ($this->fecha !== null && !$this->validateDate( $this->fecha)) {
            self::$errores['fecha'] = 'La fecha no es válida. Use el formato YYYY-MM-DD HH:MM:SS';
        }

        if (empty($this->imagen) || !filter_var($this->imagen, FILTER_VALIDATE_URL)) {
            self::$errores['imagen'] = 'La imagen debe ser una URL válida';
        }

        if (empty(self::$errors)) {
            $this->sanitize();
        }
        return empty(self::$errores);
    }

    private function validateDate($date, $format = 'Y-m-d H:i:s'): bool {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function sanitize(): void {
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = filter_var($this->precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $this->stock = filter_var($this->stock, FILTER_SANITIZE_NUMBER_INT);
        $this->oferta = filter_var($this->oferta, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
    }

    public static function getErrors(): array {
        return self::$errores;
    }

    public function setId(int|null $id): void {
        $this->id = $id;
    }

    public function setCategoriaId(int $categoria_id): void {
        $this->categoria_id = $categoria_id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function setPrecio(float $precio): void {
        $this->precio = $precio;
    }

    public function setStock(int $stock): void {
        $this->stock = $stock;
    }

    public function setOferta(string $oferta): void {
        $this->oferta = $oferta;
    }

    public function setFecha(string $fecha): void {
        $this->fecha = $fecha;
    }

    public function setImagen(string $imagen): void {
        $this->imagen = $imagen;
    }
    
    public function getId(): int|null {
        return $this->id;
    }

    public function getCategoriaId(): int {
        return $this->categoria_id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function getPrecio(): float {
            return (float)$this->precio;
        }

    public function getStock(): int {
        return $this->stock;
    }

    public function getOferta(): string {
        return $this->oferta;
    }

    public function getFecha(): string {
        return $this->fecha;
    }

    public function getImagen(): string {
        return $this->imagen;
    }
}
?>