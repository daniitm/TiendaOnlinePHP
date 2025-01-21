<?php

namespace Repositories;

use Lib\BaseDatos;
use Models\Product;
use PDO;
use PDOException;

class ProductRepository
{
    private BaseDatos $db;

    public function __construct()
    {
        $this->db = new BaseDatos();
    }

    public function save(Product $product): bool
    {
        try {
            $ins = $this->db->prepare("INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, oferta, fecha, imagen) VALUES (:categoria_id, :nombre, :descripcion, :precio, :stock, :oferta, :fecha, :imagen)");

            $ins->bindValue(':categoria_id', $product->getCategoriaId());
            $ins->bindValue(':nombre', $product->getNombre());
            $ins->bindValue(':descripcion', $product->getDescripcion());
            $ins->bindValue(':precio', $product->getPrecio());
            $ins->bindValue(':stock', $product->getStock());
            $ins->bindValue(':oferta', $product->getOferta());
            $ins->bindValue(':fecha', $product->getFecha());
            $ins->bindValue(':imagen', $product->getImagen());

            $ins->execute();
            return true;
        } catch (PDOException $err) {
            error_log("Error al crear el producto: " . $err->getMessage());
            return false;
        }
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM productos");
            if (!$stmt) {
                throw new PDOException("Error al ejecutar la consulta SQL.");
            }
            $products = $this->db->extraer_todos();
            return array_map(fn($data) => Product::fromArray($data), $products);
        } catch (PDOException $err) {
            error_log("Error al obtener los productos: " . $err->getMessage());
            return [];
        }
    }

    public function findById(int $id): ?Product
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = :id LIMIT 1");
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return Product::fromArray($data);
            }
            return null;
        } catch (PDOException $err) {
            error_log("Error al obtener el producto por ID: " . $err->getMessage());
            return null;
        }
    }

    public function getStockById(int $id): int
    {
        $stmt = $this->db->prepare("SELECT stock FROM productos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int) $result['stock'] : 0;
    }

    public function update(Product $product): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE productos SET categoria_id = :categoria_id, nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock, oferta = :oferta, fecha = :fecha, imagen = :imagen WHERE id = :id");

            $stmt->bindValue(':categoria_id', $product->getId());
            $stmt->bindValue(':nombre', $product->getNombre());
            $stmt->bindValue(':descripcion', $product->getDescripcion());
            $stmt->bindValue(':precio', $product->getPrecio());
            $stmt->bindValue(':stock', $product->getStock());
            $stmt->bindValue(':oferta', $product->getOferta());
            $stmt->bindValue(':fecha', $product->getFecha());
            $stmt->bindValue(':imagen', $product->getImagen());
            $stmt->bindValue(':id', $product->getId());

            $stmt->execute();
            return true;
        } catch (PDOException $err) {
            error_log("Error al actualizar el producto: " . $err->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM productos WHERE id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return true;
        } catch (PDOException $err) {
            error_log("Error al eliminar el producto: " . $err->getMessage());
            return false;
        }
    }

    public function decreaseStock(int $productId, int $quantity): bool {
        try {
            $stmt = $this->db->prepare("UPDATE productos SET stock = stock - :quantity WHERE id = :id AND stock >= :quantity");
            $stmt->bindValue(':quantity', $quantity);
            $stmt->bindValue(':id', $productId);
            
            $stmt->execute();
            return true;
        } catch (PDOException $err) {
            error_log("Error al actualizar el stock: " . $err->getMessage());
            return false;
        }
    }

    public function getProductStock($productId) {
        $sql = "SELECT stock FROM productos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

}
