<?php
namespace Services;

use Repositories\CategoryRepository;
use Models\Category;

class CategoryService {
    private CategoryRepository $categoryRepository;

    public function __construct() {
        $this->categoryRepository = new CategoryRepository();
    }

    public function registerCategory(Category $category): bool {
        return $this->categoryRepository->save($category);
    }

    public function getAllCategories(): array {
        return $this->categoryRepository->findAll();
    }

    public function getCategoryById(int $id): ?Category {
        return $this->categoryRepository->findById($id);
    }

    public function updateCategory(Category $category): bool {
        return $this->categoryRepository->update($category);
    }

    public function deleteCategory(int $id): bool {
        return $this->categoryRepository->delete($id);
    }
}
?>