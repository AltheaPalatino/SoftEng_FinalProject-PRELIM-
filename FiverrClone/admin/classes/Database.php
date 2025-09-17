<?php
if (!class_exists('Database')) {
    class Database {
        protected $pdo;
        private $host = "localhost";
        private $db   = "fiverr_clone";
        private $user = "root";
        private $pass = "";
        private $charset = "utf8mb4";

        public function __construct() {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
                $this->pdo = new PDO($dsn, $this->user, $this->pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database Connection Failed: " . $e->getMessage());
            }
        }

        public function getPdo() {
            return $this->pdo;
        }
    }
}
