<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB and connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate Category object
    $category = new Category($db);

    // Get raw category data
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->category)) {
        $category->category = $data->category;

        // Create category
        if($category->create()) {
            $category_arr = array(
                'id' => $db->lastInsertId(),
                'category' => $category->category
            );
            echo(json_encode($category_arr));
        } else {
            echo json_encode(
                array('message' => 'Category Not Created')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }    