<?php
session_start();
require "../config/config.php";
if (!isset($_SESSION['user_id']) and !isset($_SESSION['logged_in'])) {
  header('location: login.php');
}
if($_POST){
    $file = "images/".($_FILES['image']['name']);
    $imageType = pathinfo($file, PATHINFO_EXTENSION);
    if($imageType != "png" and $imageType != "jpg" and $imageType != "jpeg"){
        echo "<script>alert('Input must be png,jpg,jpeg')</script>";
    }else{
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = $_FILES["image"]['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],$file);

        $statement = $pdo->prepare("INSERT INTO posts(title,content,image,author_id) VALUES (:title,:content,:image,:author_id)");
        $result = $statement->execute([":title"=>$title,":content"=>$content,":image"=>$image,":author_id"=>$_SESSION["user_id"]]);
        if($result){
            echo "<script>alert('Successfully Added');window.location.href='index.php';</script>";
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
              <form action="add.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" id="content" name="content" required></textarea>
                </div>
                <div class="form-group">
                    <label for="file">Image</label>
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