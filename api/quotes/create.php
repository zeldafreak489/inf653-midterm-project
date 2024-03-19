<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../functions/helper.php';

    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Quote object
    $quote = new Quote($db);

    // Get raw quote data
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->quote) && isset($data->author_id) && isset($data->category_id)) {
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        // Check if author_id is valid
        if (isValid($quote->author_id, "Author")) {
            // Check if category_id is valid
            if (isValid($quote->category_id, "Category")) {
                // Create new author
                if($quote->create()) {
                    $quote_arr = array(
                        'id' => $quote->id,
                        'quote' => $quote->quote,
                        'author_id' => $quote->author_id,
                        'category_id' => $quote->category_id
                    );
                    echo(json_encode($quote_arr));
                } else {
                    echo json_encode(
                        array('message' => 'Quote Not Created')
                    );
                }
            } else {
                echo json_encode(
                    array('message' => 'category_id Not Found')
                );
            }
        } else {
            echo json_encode(
                array('message' => 'author_id Not Found')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }    