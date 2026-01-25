<?php

require_once 'config.php';
require_once 'utils.php';

if (!isset($_SESSION['isAuth']) || !( $_SESSION['isAuth'] === true)) {
    die(header("Location: login.php"));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once 'views/index.html';
    die();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ( empty($_POST['note']) || is_array($_POST['note'])){
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Note is required!']);
        die();
    }

    $_SESSION['note'] = $_POST['note'];

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true, 
        'message' => 'Note saved successfully!',
        'note' => $_POST['note']
    ]);
}

?>