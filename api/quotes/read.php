<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Quote object
    $quote = new Quote($db);

    // Quote query
    $result = $quote->read();
    
    // Get row count
    $num = $result->rowCount();

    // Check if any quotes
    if ($num > 0) {
        // Post array
        $quotes_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author' => $author_name,
                'category' => $category_name
            );

            // Push to "data"
            array_push($quotes_arr, $quote_item);
        }

        // Turn to JSON and output
        echo json_encode($quotes_arr);

    } else {
        // No Quotes
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }