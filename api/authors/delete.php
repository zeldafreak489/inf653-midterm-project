<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Author object
    $author = new Author($db);

    // Get raw author data
    $data = json_decode(file_get_contents("php://input"));

    
    if (isset($data->id)) {
        // Set ID to delete
        $author->id = $data->id;

        // Delete author
        if($author->delete()) {
            echo json_encode(
                array('id' => $author->id)
            );
        } else {
            echo json_encode(
                array('message' => 'Author Not Deleted')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    }