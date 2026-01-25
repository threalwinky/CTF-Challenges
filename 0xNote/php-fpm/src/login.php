<?php

require_once 'config.php';

if (isset($_SESSION['isAuth']) && ($_SESSION['isAuth'] === true) ) {
    die(header("Location: index.php"));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once 'views/login.html';
    die();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ( empty($_POST['username']) || is_array($_POST['username'])){
        die("username are required ! ");
    }

    $_SESSION['username'] = $_POST['username'];
    $_SESSION['color'] = "Black";
    $_SESSION['isAuth'] = true;
    $_SESSION['note'] = "";

    echo '
        Login successfully ! Redirecting to index.php ...
    <script>
        window.location.href = "index.php";
    </script>';
}   
?>