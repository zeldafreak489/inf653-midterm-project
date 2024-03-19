<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];
 
    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }

    include_once '../../functions/helper.php';

    if ($method === 'GET' && isset($_GET['id'])) {
        include 'read_single.php';
    }

    if ($method === 'GET' && !isset($_GET['id'])) {
        include 'read.php';
    }

    if ($method === 'POST') {
        include 'create.php';
    }

    if ($method === 'PUT') {
        include 'update.php';
    }

    if ($method === 'DELETE') {
        include 'delete.php';
    }