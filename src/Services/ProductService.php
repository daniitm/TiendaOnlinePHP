<?php
namespace Services;

use Repositories\ProductRepository;
use Models\Product;

class ProductService {
    private ProductRepository $productRepository;

    public function __construct() {
        $this->productRepository = new ProductRepository();
    }

    public function registerProduct(Product $product): bool {
        return $this->productRepository->save($product);
    }

    public function getAllProducts(): array {
        return $this->productRepository->findAll();
    }

    public function getProductById(int $id): ?Product {
        return $this->productRepository->findById($id);
    }

    public function getStockById(int $id): int{
        return $this->productRepository->getStockById($id);
    }

    public function updateProduct(Product $product): bool {
        return $this->productRepository->update($product);
    }

    public function deleteProduct(int $id): bool {
        return $this->productRepository->delete($id);
    }

    public function updateStock(int $productId, int $quantity): bool {
        return $this->productRepository->decreaseStock($productId, $quantity);
    }

    public function getProductStock($productId) {
        return $this->productRepository->getProductStock($productId);
    }
}
?>