<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../functions/helper.php';

    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Quote object
    $quote = new Quote($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Check if parameters included
    if (!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
        die();
    }

    // Check if parameters exist in database, echo response and die if not
    if (!isValid($data->author_id, "Author")) {
        echo json_encode(
            array('message' => 'author_id Not Found')
        );
        die();
    } else if (!isValid($data->category_id, "Category")) {
        echo json_encode(
            array('message' => 'category_id Not Found')
        );
        die();
    } else if (!isValid($data->id, "Quote")) {
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
        die();
    }

    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Update Quote
    if ($quote->update()) {
        $quote_arr = array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author_id' => $quote->author_id,
            'category_id' => $quote->category_id
        );
        // return as json
        echo(json_encode($quote_arr));
    } // update fails
        else {
            echo json_encode(
                array('message' => 'Quote Not Updated')
            );
    }    