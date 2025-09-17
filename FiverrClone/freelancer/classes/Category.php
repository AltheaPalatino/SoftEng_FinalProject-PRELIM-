<?php
require_once __DIR__ . '/Database.php';

if (!class_exists('Category')) {

    class Category extends Database {

        public function addCategory($name) {
            $sql = "INSERT INTO categories (category_name) VALUES (?)";
            return $this->executeNonQuery($sql, [$name]);
        }

        public function addSubcategory($category_id, $name) {
            $sql = "INSERT INTO subcategories (category_id, subcategory_name) VALUES (?, ?)";
            return $this->executeNonQuery($sql, [$category_id, $name]);
        }

        public function getCategoriesWithSubcategories() {
            $sql = "SELECT category_id AS id, category_name AS name 
            FROM categories 
            ORDER BY category_name ASC";
            $stmt = $this->pdo->query($sql);
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sql = "SELECT subcategory_id AS id, subcategory_name AS name, category_id 
            FROM subcategories 
            ORDER BY subcategory_name ASC";
            $stmt = $this->pdo->query($sql);
            $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categories as &$cat) {
                $cat['subcategories'] = [];
                foreach ($subcategories as $sub) {
                    if ($sub['category_id'] == $cat['id']) {
                        $cat['subcategories'][] = $sub;
            }
        }
    }

    return $categories;
}

        public function getCategories() {
            $sql = "SELECT category_id, category_name FROM categories ORDER BY category_name";
            return $this->executeQuery($sql);
        }
    }
}
