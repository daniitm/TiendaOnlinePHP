<?php

namespace Repositories;

use Lib\BaseDatos;
use Models\Category;
use PDO;
use PDOException;

class CategoryRepository 
{
    private BaseDatos $db;

    public function __construct() 
    {
        $this->db = new BaseDatos();
    }

    public function save(Category $category): bool {
        try {
            $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");

            $stmt->bindValue(':nombre', $category->getNombre());

            return $stmt->execute();
        } catch (PDOException $err) {
            error_log("Error al crear la categoría: " . $err->getMessage());
            return false;
        }
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM categorias");
            if (!$stmt) {
                throw new PDOException("Error al ejecutar la consulta SQL.");
            }
            $categories = $this->db->extraer_todos();
            return array_map(fn($data) => Category::fromArray($data), $categories);
        } catch (PDOException $err) {
            error_log("Error al obtener las categorias: " . $err->getMessage());
            return [];
        }
    } 

    public function findById(int $id): ?Category {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = :id LIMIT 1");
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return Category::fromArray($data);
            }
            return null;
        } catch (PDOException $err) {
            error_log("Error al obtener la categoría por ID: " . $err->getMessage());
            return null;
        }
    }

    public function update(Category $category): bool {
        try {
            $stmt = $this->db->prepare("UPDATE categorias SET nombre = :nombre WHERE id = :id");

            $stmt->bindValue(':nombre', $category->getNombre());
            $stmt->bindValue(':id', $category->getId());

            $stmt->execute();
            return true;
        } catch (PDOException $err) {
            error_log("Error al actualizar la categoría: " . $err->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return true;
        } catch (PDOException $err) {
            error_log("Error al eliminar la categoría: " . $err->getMessage());
            return false;
        }
    }
}
?>