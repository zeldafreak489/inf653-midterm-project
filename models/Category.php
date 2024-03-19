<?php
    class Category {
        // DB Stuff
        private $conn;
        private $table = 'categories';

        // Properties
        public $id;
        public $category;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Create new category
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));

            // Bind data
            $stmt->bindParam(':category', $this->category);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete Category
        public function delete() {
            // Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Get Single Category
        public function read_single() {
            // Create query
            $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = ? LIMIT 1';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);

            $stmt->execute();

            // Execute query
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set Properties
            if (isset($row['id']) && isset($row['category'])) {
                $this->id = $row['id'];
                $this->category = $row['category'];
                return true;
            } else {
                return false;
            }
        }

        // Get Categories
        public function read() {
            // Create query
            $query = 'SELECT
                id,
                category
            FROM
                ' . $this->table . ' 
            ORDER BY
                id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Update Category
        public function update() {
            // Create query
            $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';

            // Prepare statment
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }