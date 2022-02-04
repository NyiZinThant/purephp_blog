<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if (!isset($_SESSION['user_id']) and !isset($_SESSION['logged_in']) and $_SESSION['role'] != 1) {
    header('location: login.php');
}
$statement = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
$statement->execute([":id" => $_GET['id']]);

$result = $statement->fetchAll();
if ($_POST) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    postCSRF();
    if (empty($title) or empty($content)) {
        if (empty($title)) {
            $titleError = "Title is required";
        }
        if (empty($content)) {
            $contentError = "Content is required";
        }
    } else {
        if ($_FILES['image']['name'] != null) {
            $file = "images/" . ($_FILES['image']['name']);
            $imageType = pathinfo($file, PATHINFO_EXTENSION);
            if ($imageType != "png" and $imageType != "jpg" and $imageType != "jpeg") {
                echo "<script>alert('Input must be png,jpg,jpeg')</script>";
            } else {
                $image = $_FILES["image"]['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], $file);

                $statement = $pdo->prepare("UPDATE posts SET title=:title,content=:content,image=:image WHERE id=:id");
                $result = $statement->execute([":title" => $title, ":content" => $content, ":image" => $image, ":id" => $id]);
                if ($result) {
                    echo "<script>alert('Successfully Updated');window.location.href='index.php';</script>";
                }
            }
        } else {
            $statement = $pdo->prepare("UPDATE posts SET title=:title,content=:content WHERE id=:id");
            $result = $statement->execute([":title" => $title, ":content" => $content, ":id" => $id]);
            if ($result) {
                echo "<script>alert('Successfully Updated');window.location.href='index.php';</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Edit Blog</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Admin Panel</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= $_SESSION['username'] ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Blogs
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="users.php" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Users
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content mt-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                        <input type="hidden" name="id" value="<?= $result[0]['id'] ?>">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <p class="text-danger d-inline-block ml-2"><?= empty($titleError) ? "" : "*" . $titleError ?></p>
                                            <input type="text" class="form-control" id="title" name="title" value="<?= escape($result[0]['title']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="content">Content</label>
                                            <p class="text-danger d-inline-block ml-2"><?= empty($contentError) ? "" : "*" . $contentError ?></p>
                                            <textarea class="form-control" id="content" name="content"><?= escape($result[0]['content']) ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Image</label>
                                            <p class="text-danger d-inline-block ml-2">
                                                <img src="images/<?= $result[0]['image'] ?>" alt="image" width="150px" height="150px" class="my-2">
                                                <input class="form-control py-1" type="file" name="image" id="file">
                                        </div>
                                        <div class="form-group mb-0">
                                            <input type="submit" class="btn btn-success" value="Submit">
                                            <a href="index.php" class="btn btn-secondary mr-2">Back</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include("footer.php") ?>