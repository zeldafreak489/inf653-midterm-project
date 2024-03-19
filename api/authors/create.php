<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    if (isset($data->author)) {
        $author->author = $data->author;

        // Create new author
        if($author->create()) {
            $author_arr = array(
                'id' => $db->lastInsertId(),
                'author' => $author->author
            );
            echo(json_encode($author_arr));
        } else {
            echo json_encode(
                array('message' => 'Author Not Created')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }    