<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Quote object
    $quote = new Quote($db);

    // Get raw author data
    $data = json_decode(file_get_contents("php://input"));
    
    if (isset($data->id)) {
        // Set ID to delete
        $quote->id = $data->id;

        // Delete quote
        if($quote->delete()) {
            echo json_encode(
                array('id' => $quote->id)
            );
        } else {
            echo json_encode(
                array('message' => 'Quote Not Deleted')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }