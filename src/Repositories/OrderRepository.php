<?php
namespace Repositories;

use Lib\BaseDatos;
use Models\Order;
use PDOException;
use PDO;

class OrderRepository {
    private BaseDatos $db;

    public function __construct() {
        $this->db = new BaseDatos();
    }

    public function findOrdersByUserId(int $userId): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha DESC, hora DESC");
            $stmt->bindValue(':usuario_id', $userId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            error_log("Error al obtener los pedidos: " . $err->getMessage());
            return [];
        }
    }

    public function saveOrderWithLines(Order $order, array $cart): ?int {
        try {
            $this->db->query("START TRANSACTION");

            //Insertar el pedido
            $orderData = [
                'usuario_id' => $order->getUsuarioId(),
                'provincia' => $order->getProvincia(),
                'localidad' => $order->getLocalidad(),
                'direccion' => $order->getDireccion(),
                'coste' => $order->getCoste(),
                'estado' => $order->getEstado(),
                'fecha' => $order->getFecha(),
                'hora' => $order->getHora()
            ];

            $this->db->insertarDatos('pedidos', $orderData);

            //Obtener el ID del pedido recien insertado
            $stmt = $this->db->prepare("SELECT LAST_INSERT_ID() as id");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $orderId = $result['id'];

            //Insertar las lineas de pedido
            foreach ($cart as $item) {
                $lineData = [
                    'pedido_id' => $orderId,
                    'producto_id' => $item['id'],
                    'unidades' => $item['cantidad']
                ];
                $this->db->insertarDatos('lineas_pedidos', $lineData);
            }

            $this->db->query("COMMIT");

            return $orderId;
        } catch (PDOException $err) {
            $this->db->query("ROLLBACK");
            error_log("Error al guardar el pedido y sus líneas: " . $err->getMessage());
            return null;
        }
    }

    public function findAllOrders(): array {
        try {
            $stmt = $this->db->prepare("SELECT p.*, u.nombre as nombre_usuario 
                                        FROM pedidos p 
                                        JOIN usuarios u ON p.usuario_id = u.id 
                                        ORDER BY p.fecha DESC, p.hora DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            error_log("Error al obtener todos los pedidos: " . $err->getMessage());
            return [];
        }
    }
    
    public function updateStatus(int $orderId, string $newStatus): bool {
        try {
            $stmt = $this->db->prepare("UPDATE pedidos SET estado = :estado WHERE id = :id");
            $stmt->bindValue(':estado', $newStatus);
            $stmt->bindValue(':id', $orderId);
            return $stmt->execute();
        } catch (PDOException $err) {
            error_log("Error al actualizar el estado del pedido: " . $err->getMessage());
            return false;
        }
    }
}
?>