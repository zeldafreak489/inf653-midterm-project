<?php
    class Quote {
        // DB Stuff
        private $conn;
        private $table = 'quotes';

        // Properties
        public $id;
        public $quote;
        public $author_id;
        public $category_id;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Quotes
        public function read() {
            // If author_id is set in URL, set value to $author_id variable
            $author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;

            // If category_id is set in URL, set value to $category_id variable
            $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

            // Create query
            $query = 'SELECT q.id, q.quote, a.author as author_name,
                        c.category as category_name 
                        FROM ' . $this->table . ' q 
                        LEFT JOIN authors a ON q.author_id = a.id 
                        LEFT JOIN categories c ON q.category_id = c.id';

            if ($author_id && !$category_id) {
                $query .= ' WHERE q.author_id = :author_id';
            }

            if ($category_id && !$author_id) {
                $query .= ' WHERE q.category_id = :category_id';
            }

            if ($author_id && $category_id) {
                $query .= ' WHERE q.author_id = :author_id AND q.category_id = :category_id';
            }

            $query .= ' ORDER BY q.id ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            if ($author_id && !$category_id) {
                $stmt->bindParam(':author_id', $author_id);
            }

            if ($category_id && !$author_id) {
                $stmt->bindParam(':category_id', $category_id);
            }

            if ($author_id && $category_id) {
                $stmt->bindParam(':author_id', $author_id);
                $stmt->bindParam(':category_id', $category_id);
            }

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Quote
        public function read_single() {
            // Create query
            $query = 'SELECT q.id, q.quote, a.author as author_name,
                            c.category as category_name 
                            FROM ' . $this->table . ' q 
                            LEFT JOIN authors a ON q.author_id = a.id 
                            LEFT JOIN categories c ON q.category_id = c.id
                            WHERE q.id = ?';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->id);

            $stmt->execute();

            // Execute query
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set Properties
            if (isset($row['id']) && isset($row['quote']) && isset($row['category_name']) && isset($row['author_name'])) {
                $this->id = $row['id'];
                $this->quote = $row['quote'];
                $this->author_id = $row['author_name'];
                $this->category_id = $row['category_name'];
                return true;
            } else {
                return false;
            }
        }

        // Create new quote
        public function create() {
            // Create query
            $query = '
                    INSERT INTO ' . $this->table . ' (quote, author_id, category_id) 
                    VALUES (:quote, :author_id, :category_id)
                    RETURNING id
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);

            // Execute query
            if ($stmt->execute()) {
                // Fetch the id and set it
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id = $row['id'];
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Update Quote
        public function update() {
            // Create query
            $query = '
                UPDATE ' . $this->table . ' 
                SET quote = :quote, author_id = :author_id, category_id = :category_id
                WHERE id = :id
                RETURNING id;
            ';

            // Prepare statment
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);

            // Execute query
            if ($stmt->execute()) {
                // Fetch and set id
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id = $row['id'];
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete Quote
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
    }