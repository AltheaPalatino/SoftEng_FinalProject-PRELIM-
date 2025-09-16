<?php
class Category {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addCategory($name) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function getAllCategories() {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY category_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
