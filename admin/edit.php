<?php
session_start();
require "../config/config.php";
if (!isset($_SESSION['user_id']) and !isset($_SESSION['logged_in'])) {
    header('location: login.php');
}
$statement = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
$statement->execute([":id" => $_GET['id']]);

$result = $statement->fetchAll();
if ($_POST) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
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
?>
<?php include("header.php") ?>
<!-- Main content -->
<div class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $result[0]['id'] ?>">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= $result[0]['title'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea class="form-control" id="content" name="content" required><?= $result[0]['content'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Image</label>
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
<?php include("footer.html") ?>