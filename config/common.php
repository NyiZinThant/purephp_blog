<?php
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])){
        echo "Invalid CSRF Token";
        die();
    }else{
        unset($_SESSION['csrf']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_SESSION['csrf'] != $_GET['csrf']){
        header('location: index.php?error=csrf');
        die();
    }else{
        unset($_SESSION['csrf']);
    }
}
