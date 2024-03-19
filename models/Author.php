<?php
    class Author {
        // DB Stuff
        private $conn;
        private $table = 'authors';

        // Properties
        public $id;
        public $author;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Create new author
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));

            // Bind data
            $stmt->bindParam(':author', $this->author);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete Author
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

        // Get Single Author
        public function read_single() {
            // Create query
            $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = ? LIMIT 1';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);

            $stmt->execute();

            // Execute query
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set Properties
            if (isset($row['id']) && isset($row['author'])) {
                $this->id = $row['id'];
                $this->author = $row['author'];
                return true;
            } else {
                return false;
            }
        }

        // Get Authors
        public function read() {
            // Create query
            $query = 'SELECT
                id,
                author
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

        // Update Author
        public function update() {
            // Create query
            $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';

            // Prepare statment
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':author', $this->author);
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