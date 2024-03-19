<?php 
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';
    include_once '../../models/Author.php';
    include_once '../../models/Quote.php';    

    function isValid($id, $model_class) {
        $database = new Database();
        $db = $database->connect();
        $model = new $model_class($db);
        $model->id = $id;
        if($model->read_single()) {
            return true;
        }
        return false;
    }