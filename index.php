<?php
require "config/config.php";
require "config/common.php";
session_start();
if (empty($_SESSION['username'] && $_SESSION['user_id'])) {
    header("location: login.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blogs</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper container">
        <!-- Content Wrapper. Contains page content -->
        <!-- /.row -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="text-center text-bold">Blog Site</h1>
            </div>
        </div>
        <div class="row">
            <?php
            if (empty($_POST['search'])) {
                if (!empty($_GET['pageno'])) {
                    $pageno = $_GET['pageno'];
                } else {
                    $pageno = 1;
                }
                $numOfRecord = 6;
                $offset = ($pageno - 1) * $numOfRecord;
                $statement = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                $statement->execute();
                $rawResult = $statement->fetchAll();

                $total_pages = ceil(count($rawResult) / $numOfRecord);
                $statement = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfRecord");
                $statement->execute();
                $result = $statement->fetchAll();
            } else {
                $search = $_POST['search'];
                if (!empty($_GET['pageno'])) {
                    $pageno = $_GET['pageno'];
                } else {
                    $pageno = 1;
                }
                $numOfRecord = 6;
                $offset = ($pageno - 1) * $numOfRecord;
                $statement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$search%' ORDER BY id DESC");
                $statement->execute();
                $rawResult = $statement->fetchAll();

                $total_pages = ceil(count($rawResult) / $numOfRecord);
                $statement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfRecord");
                $statement->execute();
                $result = $statement->fetchAll();
            }
            ?>
            <?php $i = 1;
            if ($result) : foreach ($result as $value) : ?>
                    <div class="col-md-4 mt-3">
                        <!-- Box Comment -->
                        <div class="card card-widget">
                            <a href="blogdetail.php?id=<?= $value['id'] ?>">
                                <div class="card-header">
                                    <div class="card-title text-center" style="float: none;">
                                        <h4 class="text-primary"><?= escape($value['title']) ?></h4>
                                    </div>
                                </div>
                            </a>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <a href="blogdetail.php?id=<?= $value['id'] ?>"><img class="img-fluid pad" src="admin/images/<?= $value['image'] ?>" style="width: 325;height: 186;" alt="Photo"></a>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
            <?php $i++;
                endforeach;
            endif ?>
        </div>
        <div class="mx-3">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if ($pageno <= 1) {
                                                echo "disabled";
                                            } ?>">
                        <a class="page-link" href="<?php if ($pageno <= 1) {
                                                        echo "#";
                                                    } else {
                                                        echo "?pageno=" . $pageno - 1;
                                                    } ?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?= $pageno ?>"><?= $pageno ?></a></li>
                    <li class="page-item <?php if ($pageno >= $total_pages) {
                                                echo "disabled";
                                            } ?>">
                        <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                                        echo "#";
                                                    } else {
                                                        echo "?pageno=" . $pageno + 1;
                                                    } ?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?= $total_pages ?>">Last</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <footer class="main-footer m-0">
        <!-- To the right -->
        <div class="float-right mr-5 d-none d-sm-inline">
            <a href="logout.php?csrf=<?= $_SESSION['csrf'] ?>">Logout</a>
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2022 <a href="https://github.com/NyiZinThant">Nyi</a>.</strong> All rights reserved.
    </footer>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
    </a>
    <!-- jQuery -->
    <script src="./plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="./dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="./dist/js/demo.js"></script>
</body>

</html>