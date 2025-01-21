<?php
namespace Services;

use Repositories\OrderRepository;
use Models\Order;

class OrderService {
    private OrderRepository $orderRepository;

    public function __construct() {
        $this->orderRepository = new OrderRepository();
    }

    public function getOrdersByUserId(int $userId): array {
        return $this->orderRepository->findOrdersByUserId($userId);
    }

    public function createOrderWithLines(Order $order, array $cart): ?int {
        return $this->orderRepository->saveOrderWithLines($order, $cart);
    }

    public function getAllOrders(): array {
        return $this->orderRepository->findAllOrders();
    }
    
    public function updateOrderStatus(int $orderId, string $newStatus): bool {
        return $this->orderRepository->updateStatus($orderId, $newStatus);
    }

}
?>