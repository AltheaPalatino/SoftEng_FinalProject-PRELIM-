<?php
require_once __DIR__ . '/Database.php';

if (!class_exists('Category')) {

   class Category extends Database {

    public function addCategory($name) {
        $sql = "INSERT INTO categories (category_name) VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':name' => $name]);
    }

    public function addSubcategory($category_id, $name) {
        $sql = "INSERT INTO subcategories (category_id, subcategory_name) VALUES (:cat_id, :name)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':cat_id' => $category_id, ':name' => $name]);
    }

    public function getCategoriesWithSubcategories() {
        $sql = "SELECT c.category_id, c.category_name, s.subcategory_id, s.subcategory_name
                FROM categories c
                LEFT JOIN subcategories s ON c.category_id = s.category_id
                ORDER BY c.category_name, s.subcategory_name";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rows as $row) {
            $catId = $row['category_id'];
            if (!isset($categories[$catId])) {
                $categories[$catId] = [
                    'id' => $row['category_id'],
                    'name' => $row['category_name'],
                    'subcategories' => []
                ];
            }
            if ($row['subcategory_id']) {
                $categories[$catId]['subcategories'][] = [
                    'id' => $row['subcategory_id'],
                    'name' => $row['subcategory_name']
                ];
            }
        }
        return $categories;
    }

    public function getCategories() {
        $sql = "SELECT category_id, category_name FROM categories ORDER BY category_name";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function deleteCategory($category_id) {
    // Delete subcategories first
    $sql1 = "DELETE FROM subcategories WHERE category_id = :cat_id";
    $stmt1 = $this->pdo->prepare($sql1);
    $stmt1->execute([':cat_id' => $category_id]);

    $sql2 = "DELETE FROM categories WHERE category_id = :cat_id";
    $stmt2 = $this->pdo->prepare($sql2);
    return $stmt2->execute([':cat_id' => $category_id]);
}

public function deleteSubcategory($subcategory_id) {
    $sql1 = "DELETE FROM proposals WHERE subcategory_id = :sub_id";
    $stmt1 = $this->pdo->prepare($sql1);
    $stmt1->execute([':sub_id' => $subcategory_id]);

    $sql2 = "DELETE FROM subcategories WHERE subcategory_id = :sub_id";
    $stmt2 = $this->pdo->prepare($sql2);
    return $stmt2->execute([':sub_id' => $subcategory_id]);
}


}
}