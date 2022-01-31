<?php
require "../config/config.php";
$statement = $pdo->prepare("DELETE FROM users WHERE id=:id");
$statement->execute([":id"=>$_GET['id']]);
header('location: users.php');