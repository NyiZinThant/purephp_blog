<?php
require "config/common.php";
require "config/config.php";
session_start();
if (empty($_SESSION['username'] && $_SESSION['user_id'])) {
    header("location: login.php");
}
$blog_id = $_GET['id'];

$statement = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
$statement->execute([":id" => $_GET['id']]);
$result = $statement->fetchAll();

$cmtStatement = $pdo->prepare("SELECT comments.*, users.name FROM comments LEFT JOIN users ON comments.author_id = users.id WHERE comments.post_id=:post_id");
$cmtStatement->execute([":post_id" => $blog_id]);
$cmtResult = $cmtStatement->fetchAll();

if ($_POST) {
    $comment = $_POST['comment'];
    if (empty($comment)) {
        $commentError = "Your comment is empty";
    } else {
        $statement = $pdo->prepare("INSERT INTO comments(content, author_id, post_id) VALUES (:content, :author_id,:post_id)");
        $result = $statement->execute([
            ":content" => $comment,
            ":author_id" => $_SESSION['user_id'],
            ":post_id" => $blog_id
        ]);
        if ($result) {
            header("location: blogdetail.php?id=$blog_id");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blogs | <?= $result[0]['title'] ?></title>

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
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="container">
            <div class="row mt-3">
                <div class="card card-widget">
                    <div class="card-header">
                        <a href="index.php"><i class="fa fa-chevron-left" style="font-size: 20px"></i></a>
                        <div class="card-title text-center" style="float: none;">
                            <h4 class="text-primary text-bold"><?= $result[0]['title'] ?></h4>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <img class="img-fluid mb-2 pad" src="./admin/images/<?= $result[0]['image'] ?>" alt="Photo">
                        <p class="mb-1"><?= $result[0]['content'] ?></p>
                        <hr>
                        <h4 class="text-secondary mt-1">Comments</h4>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer card-comments">
                        <?php foreach ($cmtResult as $value) : ?>
                            <div class="card-comment">
                                <div class="comment-text m-0">
                                    <span class="username">
                                        <?= $value['name'] ?>
                                        <span class="text-muted float-right"><?= $value['created_at'] ?></span>
                                    </span>
                                    <?= $value["content"] ?>
                                </div>
                                <!-- /.comment-text -->
                            </div>
                        <?php endforeach ?>
                        <!-- /.card-comment -->
                    </div>
                    <!-- /.card-footer -->
                    <div class="card-footer">
                        <form action="" method="post">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                            <p class="text-danger"><?= empty($commentError) ? "" : "*" . $commentError ?></p>
                            <div class="img-push">
                                <input type="text" class="form-control form-control-sm" name="comment" placeholder="Press enter to post comment">
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.content -->

            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer m-0">
            <!-- To the right -->
            <div class="float-right mr-5 d-none d-sm-inline">
                <a href="logout.php?csrf=<?= $_SESSION['csrf'] ?>">Logout</a>
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2022 <a href="https://github.com/NyiZinThant">Nyi</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

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