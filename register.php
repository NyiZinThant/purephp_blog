<?php
session_start();
require "./config/config.php";
require "./config/common.php";
if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    postCSRF();
    if (empty($name) or empty($email) or empty($password) or empty($password) or strlen($_POST['password']) < 4) {
        if (empty($name)) {
            $nameError = "Username is required";
        }
        if (empty($email)) {
            $emailError = "Email is required";
        }
        if (empty($password)) {
            $passwordError = "Password is required";
        } elseif (strLen($_POST['password']) < 4) {
            $passwordError = "Password should be 4 characters at least";
        }
    } else {
        $statement = $pdo->prepare("SELECT * FROM users WHERE email=:email");
        $statement->execute([
            ":email" => $email,
        ]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo "<script>alert(Your email is already used.)</script>";
        } else {
            $statement = $pdo->prepare("INSERT INTO users(name,password,email) VALUES (:name,:password,:email)");
            $result = $statement->execute([
                ":name" => $name,
                ":password" => $password,
                ":email" => $email
            ]);
            if ($result) {
                echo "<script>alert('Successfully registered and you can now login.');window.location.href='login.php';</script>";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog App | Register</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="./index.html"><b>Blog</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Register</p>

                <form action="register.php" method="post">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                    <p class="text-danger"><?= empty($nameError) ? "" : "*" . $nameError ?></p>
                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <p class="text-danger"><?= empty($emailError) ? "" : "*" . $emailError ?></p>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <p class="text-danger"><?= empty($passwordError) ? "" : "*" . $commentError ?></p>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-block" type="submit">Register</button>
                                <a href="login.php" class="btn btn-default btn-block" type="button">Sign In</a>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="./plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="./dist/js/adminlte.min.js"></script>
</body>

</html>