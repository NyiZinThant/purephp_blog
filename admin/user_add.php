<?php
session_start();
require "../config/config.php";
if (!isset($_SESSION['user_id']) and !isset($_SESSION['logged_in'])) {
    header('location: login.php');
}
if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $statement = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $statement->execute([
        ":email" => $email,
    ]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        echo "<script>alert('Your email is already used.');</script>";
    } else {
        $statement = $pdo->prepare("INSERT INTO users(name,password,email,role) VALUES (:name,:password,:email,:role)");
        $result = $statement->execute([":name" => $name, ":password" => $password, ":email" => $email, ":role" => $role]);
        if ($result) {
            echo "<script>alert('Successfully Added');window.location.href='users.php';</script>";
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
    <title>Admin Dashboard</title>

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
                                    <form action="user_add.php" method="post">
                                        <div class="form-group">
                                            <label for="name">Username</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Passowrd</label>
                                            <input class="form-control" type="password" name="password" id="password">
                                        </div>
                                        <div class="form-group">
                                            <label>Role</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="role" value="1" id="admin">
                                                <label class="form-check-label" for="admin">
                                                    Admin
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="role" value="0" id="user" checked>
                                                <label class="form-check-label" for="user">
                                                    User
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <input type="submit" class="btn btn-success" value="Submit">
                                            <a href="users.php" class="btn btn-secondary mr-2">Back</a>
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
        <?php include("footer.html") ?>