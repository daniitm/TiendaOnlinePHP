<?php
namespace Models;

class Order {
    protected static array $errores = [];

    public function __construct(
        private int|null $id,
        private int $usuario_id,
        private string $provincia,
        private string $localidad,
        private string $direccion,
        private float $coste,
        private string $estado,
        private string $fecha,
        private string $hora,
        private string|null $payment_id = null 
    ) {}

    public static function fromArray(array $data): Order {
        return new Order(
            id: $data['id'] ?? null,
            usuario_id: (int)($data['usuario_id'] ?? 0),
            provincia: $data['provincia'] ?? '',
            localidad: $data['localidad'] ?? '',
            direccion: $data['direccion'] ?? '',
            coste: (float)($data['coste'] ?? 0),
            estado: $data['estado'] ?? '',
            fecha: $data['fecha'] ?? date('Y-m-d'),
            hora: $data['hora'] ?? date('H:i:s'),
            payment_id: $data['payment_id'] ?? null
        );
    }

    public function validation(): bool {
        self::$errores = [];
        if (empty($this->usuario_id)) {
            self::$errores['usuario_id'] = 'El ID de usuario es obligatorio';
        }
        if (empty($this->provincia)) {
            self::$errores['provincia'] = 'La provincia es obligatoria';
        }
        if (empty($this->localidad)) {
            self::$errores['localidad'] = 'La localidad es obligatoria';
        }
        if (empty($this->direccion)) {
            self::$errores['direccion'] = 'La dirección es obligatoria';
        }
        if ($this->coste <= 0) {
            self::$errores['coste'] = 'El coste debe ser mayor a 0';
        }
        if (empty($this->estado)) {
            self::$errores['estado'] = 'El estado es obligatorio';
        }
        if (!$this->validateDate($this->fecha)) {
            self::$errores['fecha'] = 'La fecha no es válida';
        }
        if (!$this->validateTime($this->hora)) {
            self::$errores['hora'] = 'La hora no es válida';
        }

        if (empty(self::$errores)) {
            $this->sanitize();
        }
        return empty(self::$errores);
    }

    private function validateDate($date, $format = 'Y-m-d'): bool {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    private function validateTime($time, $format = 'H:i:s'): bool {
        $d = \DateTime::createFromFormat($format, $time);
        return $d && $d->format($format) === $time;
    }

    public function sanitize(): void {
        $this->provincia = htmlspecialchars(strip_tags($this->provincia));
        $this->localidad = htmlspecialchars(strip_tags($this->localidad));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->coste = filter_var($this->coste, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        $this->hora = htmlspecialchars(strip_tags($this->hora));
    }

    public static function getErrors(): array {
        return self::$errores;
    }

    // Getters
    public function getId(): int|null {
        return $this->id;
    }

    public function getUsuarioId(): int {
        return $this->usuario_id;
    }

    public function getProvincia(): string {
        return $this->provincia;
    }

    public function getLocalidad(): string {
        return $this->localidad;
    }

    public function getDireccion(): string {
        return $this->direccion;
    }

    public function getCoste(): float {
        return $this->coste;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function getFecha(): string {
        return $this->fecha;
    }

    public function getHora(): string {
        return $this->hora;
    }

    public function getPaymentId(): ?string {
        return $this->payment_id;
    }

    public function setPaymentId(?string $payment_id): void {
        $this->payment_id = $payment_id;
    }

    // Setters
    public function setId(int|null $id): void {
        $this->id = $id;
    }

    public function setUsuarioId(int $usuario_id): void {
        $this->usuario_id = $usuario_id;
    }

    public function setProvincia(string $provincia): void {
        $this->provincia = $provincia;
    }

    public function setLocalidad(string $localidad): void {
        $this->localidad = $localidad;
    }

    public function setDireccion(string $direccion): void {
        $this->direccion = $direccion;
    }

    public function setCoste(float $coste): void {
        $this->coste = $coste;
    }

    public function setEstado(string $estado): void {
        $this->estado = $estado;
    }

    public function setFecha(string $fecha): void {
        $this->fecha = $fecha;
    }

    public function setHora(string $hora): void {
        $this->hora = $hora;
    }
}
?>