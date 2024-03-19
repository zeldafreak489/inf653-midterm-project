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

    // Check if id is set
    if (isset($_GET['id'])) {
        // Get ID
        $quote->id = $_GET['id'];

        // Get quote
        $quote->read_single();

        // Create array
        if ((isset($quote->id)) && (isset($quote->quote))) {
            $quote_arr = array(
                'id' => $quote->id,
                'quote' => $quote->quote,
                'author' => $quote->author_id,
                'category' => $quote->category_id
            );
            echo(json_encode($quote_arr));
        } else {
            echo(json_encode(array('message' => 'No Quotes Found')));
        }
    }  else {
        echo(json_encode(array('message' => 'No Quotes Found')));
    }