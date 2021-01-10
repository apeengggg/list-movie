<?php

require 'koneksi.php';

$viewdata = mysqli_query($conn, "SELECT * FROM movie");

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
<a href="login.php">Login</a>
<center>
<h2>List Movie</h2>
</center>
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
    <td width="100px"><a href="<?= $data["deskripsi"]?>" target="_blank">Click Here</a></td>
    <td width="300px" align="center">
      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#viewiframe" id="target"
      data-id="<?=$data["id"]?>"
      data-iframe="<?=$data["iframe"]?>">
      Click here</button>
    </td>
    </tr>
    <?php }} ?>
    </tbody>
    </table>
    </div>
    </div>

    <!-- modal -->
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
    $(document).on("click", "#target", function(){
      let id = $(this).data('id');
      let judul = $(this).data('judul');
      let deskripsi = $(this).data('deskripsi');
      let iframe = $(this).data('iframe');
      $("#viewiframe #iframe").val('<iframe src="'+iframe+'">'+'</iframe>');
    });
    </script>
    </body>
    </html>