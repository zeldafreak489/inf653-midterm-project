<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';
    include_once '../../functions/helper.php';

    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Author object
    $author = new Author($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    
    if (isset($data->author)){
        if (isset($data->id) && isValid($data->id, "Author")) {
            // Set ID to update
            $author->id = $data->id;
            $author->author = $data->author;
    
            // Update author
            if($author->update()) {
                $author_arr = array(
                    'id' => $author->id,
                    'author' => $author->author
                );
                // return as json
                echo(json_encode($author_arr));
            } 
            // update fails
            else {
                echo json_encode(
                    array('message' => 'Author Not Updated')
                );
            }
        } 
        // ID is not found
        else {
           echo json_encode(
               array('message' => 'author_id Not Found')
           );
        }
    } 
    // Author not included
    else {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }

    