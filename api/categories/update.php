<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';
    include_once '../../functions/helper.php';

    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Category object
    $category = new Category($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    
    if (isset($data->category)){
        if (isset($data->id) && isValid($data->id, "Category")) {
            // Set ID to update
            $category->id = $data->id;
            $category->category = $data->category;
    
            // Update category
            if($category->update()) {
                $category_arr = array(
                    'id' => $category->id,
                    'category' => $category->category
                );
                // return as json
                echo(json_encode($category_arr));
            } 
            // Update fails
            else {
                echo json_encode(
                    array('message' => 'Category Not Updated')
                );
            }
        } 
        // ID is not found
        else {
            echo json_encode(
                array('message' => 'category_id Not Found')
            );
        }
    } 
    // Category not included
    else {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }

    