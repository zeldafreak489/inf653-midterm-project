<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Category object
    $category = new Category($db);

    // Category query
    $result = $category->read();
    
    // Get row count
    $num = $result->rowCount();

    // Check if any categories
    if ($num > 0) {
        // Post array
        $categories_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $category_item = array(
                'id' => $id,
                'category' => $category
            );

            // Push to "data"
            array_push($categories_arr, $category_item);
        }

        // Turn to JSON and output
        echo json_encode($categories_arr);

    } else {
        // No Categories
        echo json_encode(
            array('message' => 'No Categories Found')
        );
    }
