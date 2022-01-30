<?php
session_start();
require "../config/config.php";
if (!isset($_SESSION['user_id']) and !isset($_SESSION['logged_in'])) {
  header('location: login.php');
}
?>
<?php include("header.php") ?>
<!-- Main content -->
<div class="content mt-4">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Blogs Listings</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <?php
            if(empty($_POST['search'])){
              if(!empty($_GET['pageno'])){
                $pageno = $_GET['pageno'];
              }else{
                $pageno = 1;
              }
              $numOfRecord = 1;
              $offset = ($pageno-1) * $numOfRecord;
              $statement = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
              $statement->execute();
              $rawResult = $statement->fetchAll();
  
              $total_pages = ceil(count($rawResult) / $numOfRecord);
              $statement = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfRecord");
              $statement->execute();
              $result= $statement->fetchAll();
            }else{
              $search = $_POST['search'];
              if(!empty($_GET['pageno'])){
                $pageno = $_GET['pageno'];
              }else{
                $pageno = 1;
              }
              $numOfRecord = 1;
              $offset = ($pageno-1) * $numOfRecord;
              $statement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$search%' ORDER BY id DESC");
              $statement->execute();
              $rawResult = $statement->fetchAll();
  
              $total_pages = ceil(count($rawResult) / $numOfRecord);
              $statement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfRecord");
              $statement->execute();
              $result= $statement->fetchAll();
            }
            ?>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th style="width: 180px">Title</th>
                  <th>Description</th>
                  <th style="width: 160px">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1;
                if ($result) : foreach ($result as $value) : ?>
                    <tr>
                      <td><?= $i ?></td>
                      <td><?= $value['title'] ?></td>
                      <td>
                        <div>
                          <?= substr($value['content'], 0, 150) . " ..." ?>
                        </div>
                      </td>
                      <td>
                        <a href="edit.php?id=<?= $value['id'] ?>" class="btn btn-warning" type="button">Edit</a>
                        <a href="delete.php?id=<?= $value['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete this Blog');" type="button">Delete</a>
                      </td>
                    </tr>
                <?php $i++;
                  endforeach;
                endif ?>
              </tbody>
            </table>
          </div>
          <div class="mx-3">
            <nav aria-label="Page navigation">
              <ul class="pagination justify-content-end">
                <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                <li class="page-item <?php if($pageno <= 1){echo "disabled";} ?>">
                  <a class="page-link" href="<?php if($pageno <= 1){echo "#";}else{echo "?pageno=".$pageno - 1;} ?>">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="?pageno=<?= $pageno ?>"><?= $pageno ?></a></li>
                <li class="page-item <?php if($pageno >= $total_pages){echo "disabled";} ?>">
                  <a class="page-link" href="<?php if($pageno >= $total_pages){echo "#";}else{echo "?pageno=".$pageno+1;} ?>">Next</a>
                </li>
                <li class="page-item"><a class="page-link" href="?pageno=<?= $total_pages ?>">Last</a></li>
              </ul>
            </nav>
          </div>
          <a href="add.php" class="btn btn-success mx-3 mb-3" type="button">Create New Blog Post</a>
          <!-- /.card-body -->
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