<?php

require_once 'config.php';
require_once 'utils.php';

if (!isset($_SESSION['isAuth']) || !($_SESSION['isAuth'] === true)) {
    die(header("Location: /"));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once 'views/premium.html';
    die();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        empty($_POST['color']) || is_array($_POST['color'])
    ) {
        die("Color are required !");
    }

    $_SESSION['color'] = $_POST['color'];

    echo '
        Update successfully ! Redirecting to index.php ...
    <script>
        setTimeout(() => {
            window.location.href = "/";
        }, 1000);
    </script>';
}


?>