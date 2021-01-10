<?php

require '../koneksi.php';

$viewdata = mysqli_query($conn, "SELECT * FROM movie");

if (isset($_POST["tambah"])) {
  $judul = $_POST["judul"];
  $desk = $_POST["deskripsi"];
  $iframe  = $_POST["iframe"];
  
  $insert = mysqli_query($conn, "INSERT INTO movie (judul, deskripsi, iframe)
  VALUES ('$judul', '$desk', '$iframe')");
  if ($insert) {
    header("location:index.php");
  }else{
    header("location:index.php");
  }
}

########################################################################################################################################################################
if (isset($_POST["import"])) {
  // var_dump($_FILES); die;
require('plugin/excel_reader/php-excel-reader/excel_reader2.php');
require('plugin/excel_reader/SpreadsheetReader.php');
$namafile = $_FILES['file']['name'];
$ekstensi = array("csv", "xlsx");
$ambil_ekstensi1 = explode(".", $namafile);
$eks = $ambil_ekstensi1[1];
//upload data excel kedalam folder uploads
$target_dir = "uploads/".basename($_FILES['file']['name']);
if (in_array($eks, $ekstensi)) {
move_uploaded_file($_FILES['file']['tmp_name'],$target_dir);
$Reader = new SpreadsheetReader($target_dir);
foreach ($Reader as $Key => $Row)
{
$query = mysqli_query($conn, "INSERT INTO movie (judul, deskripsi, iframe) VALUES ('".$Row[0]."','".$Row[1]."','".$Row[2]."')") or die (mysqli_erorr($conn));
}
if ($query) {
  unlink('uploads/'.$namafile);
  header("location:index.php");
  echo 'berhasil';
  }else{
    header("location:index.php");
    echo 'gagal';
  }
}else{
  echo "<script>
  alert('ekstensi file tidak sesuai, harap format file berekstensi ms.excel (csv/xlsx)')
  document.location.href('index.php')
  </script>"; 
}
}
########################################################################################################################################################################


if (isset($_POST["edit"])) {
  $id = $_POST["id"];
  $judul = $_POST["judul"];
  $desk = $_POST["deskripsi"];
  $iframe  = $_POST["iframe"];
  $edit = mysqli_query($conn, "UPDATE movie SET judul='$judul', deskripsi='$desk', iframe='$iframe'
          WHERE id='$id'");
  if ($edit) {
    header("location:index.php");;
  }else{
    header("location:index.php");
  }
}

if (isset($_POST["hapus"])) {
  $id= $_POST["id"];

  $delete = mysqli_query($conn, "DELETE FROM movie WHERE id='$id'");
  if ($delete) {
    header("location:index.php");
  }else{
    header("location:index.php");
  }
}

?>



<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
<title>List Movie</title>
</head>
<body>
<div class="login-box">
<!-- /.login-logo -->
<div class="card">
<div class="card-body login-card-body">
<center>
<h2>List Movie [Admin]</h2>
<a href="../index.php" class="btn-sm btn-danger">Logout</a>
<br><br>
</center>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah">
Tambah</button>

########################################################################################################################################################################
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#import">
Import</button>
########################################################################################################################################################################
<br>
<a href="index.php">Refresh</a>
<br><br>
<table id="example" class="table table-striped table-bordered" style="width:100%">
<thead align="center">
<tr>
<th>No</th>
<th>Judul</th>
<th>Deksripsi</th>
<th>IFrame</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php 
$no = 1;
if (mysqli_num_rows($viewdata)>0) {
  while ($data=mysqli_fetch_assoc($viewdata)) {
    ?>
    <tr>
    <td width="10px"><?= $no++?></td>
    <td width="100px"><?= $data["judul"]?></td>
    <td width="100px"><?= $data["deskripsi"]?></td>
    <td width="300px" align="center">
      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#viewiframe" id="target"
      data-id="<?=$data["id"]?>"
      data-iframe="<?=$data["iframe"]?>">
      Click Here</button>
    </td>
    <td width="50px" align="center">
      <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#edit" id="editdata"
      data-id="<?=$data["id"]?>"
      data-judul="<?=$data["judul"]?>"
      data-deskripsi="<?=$data["deskripsi"]?>"
      data-iframe="<?=$data["iframe"]?>">
      Edit</button>
      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapus" id="hapusdata"
      data-id="<?=$data["id"]?>">
      Hapus</button>
    </td>
    </tr>
    <?php }} ?>
    </tbody>
    </table>
    </div>
    </div>
    <!-- modal -->
    <!-- Modal -->
    <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <!-- form -->
    <form method="post" action="">
    <div class="form-group">
    <label for="judul">Judul</label>
    <input type="text" class="form-control" name="judul" id="judul" placeholder="judul" required>
    </div>
    <div class="form-group">
    <label for="deskripsi">Deskripsi</label>
    <input type="text" class="form-control" name="deskripsi" id="deskripsi" placeholder="deskripsi" required>
    </div>
    <div class="form-group">
    <label for="iframe">Iframe</label>
    <input type="text" class="form-control" name="iframe" id="iframe" placeholder="masukan hanya link, jangan beri tag <iframe></iframe>" required>
    </div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="tambah" id="tambah">Save</button>    
    </form>
    <!-- /. form -->
    </div>
    </div>
    </div>
    </div>
    <!-- /.modal -->
    <!-- Modal -->
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <!-- form -->
    <form method="post" action="">
    <div class="form-group">
    <input type="text" class="form-control" name="id" id="id" placeholder="id" required hidden>
    </div>
    <div class="form-group">
    <label for="judul">Judul</label>
    <input type="text" class="form-control" name="judul" id="judul" placeholder="judul" required>
    </div>
    <div class="form-group">
    <label for="deskripsi">Deskripsi</label>
    <input type="text" class="form-control" name="deskripsi" id="deskripsi" placeholder="deskripsi" required>
    </div>
    <div class="form-group">
    <label for="iframe">Iframe</label>
    <input type="text" class="form-control" name="iframe" id="iframe" placeholder="masukan hanya link, jangan beri tag <iframe></iframe>" required>
    </div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit" id="edit">Save</button>    
    </form>
    <!-- /. form -->
    </div>
    </div>
    </div>
    </div>
    <!-- /.modal -->

     <!-- Modal -->
    <div class="modal fade" id="hapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <!-- form -->
    <form method="post" action="">
    <div class="form-group">
    <input type="text" class="form-control" name="id" id="id" placeholder="id" required hidden>
    </div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="hapus" id="hapus">Hapus</button>    
    </form>
    <!-- /. form -->
    </div>
    </div>
    </div>
    </div>
    <!-- /.modal -->


<div class="modal fade" id="viewiframe" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    Copy and paste the code below to your website
    <span aria-hidden="true">&times;</span>
    </button>
      <textarea name="iframe" id="iframe" cols="30" rows="6" class="form-control"></textarea>
    </div>
  </div>
</div>
    <!-- /.modal -->
    ########################################################################################################################################################################
    <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Import Data</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <!-- form -->
    <form method="post" action="" enctype="multipart/form-data">
    <div class="form-group">
    <input type="file" class="form" name="file" id="file" placeholder="file" required>
    <br>
    <small><b>File Yang Di Izinkan Hanya .xlsx dan .csv</b></small>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="import" id="import">Import</button>    
    </form>
    <!-- /. form -->
    </div>
    </div>
    </div>
    </div>
    ########################################################################################################################################################################
    
    </div>
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
    <script>
    $(document).ready(function() {
      $('#example').DataTable();
    } );
    </script>
    <script>
    $(document).on("click", "#editdata", function(){
      let id = $(this).data('id');
      let judul = $(this).data('judul');
      let deskripsi = $(this).data('deskripsi');
      let iframe = $(this).data('iframe');
      $("#edit #judul").val(judul);
      $("#edit #deskripsi").val(deskripsi);
      $("#edit #id").val(id);
      $("#edit #iframe").val(iframe);
    });

    $(document).on("click", "#target", function(){
      let id = $(this).data('id');
      let judul = $(this).data('judul');
      let deskripsi = $(this).data('deskripsi');
      let iframe = $(this).data('iframe');
      $("#viewiframe #iframe").val('<iframe src="'+iframe+'">'+'</iframe>');
    });

     $(document).on("click", "#hapusdata", function(){
      let id = $(this).data('id');
      $("#hapus #id").val(id);
    });
    </script>
    </body>
    </html>