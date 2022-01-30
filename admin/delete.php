<?php
require "../config/config.php";
$statement = $pdo->prepare("DELETE FROM posts WHERE id=:id");
$statement->execute([":id"=>$_GET['id']]);
header('location: index.php');