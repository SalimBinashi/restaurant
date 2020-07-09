<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>AdminLTE 3 | Starter</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    .container2 {
      
      text-align: left;
      

    }
    .button {

    border-radius: 4px;
    background-color: #8fd4f2;
    border: none;
    color: #FFFFFF;
    text-align: center;
    font-size: 15px;
    padding: 10px;
    width: auto;
    border-radius: 40px;
    transition: all 0.5s;
    cursor: pointer;
    margin-top: 10px;

    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <?php
      require_once 'includes/sidebar.php';
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Admin Page</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Admin Page</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
            <!--add a component here-->
            <div class="container2">
  <form method="POST" action="add_table.php">
        <div class="row" style="height: 800px; margin-top: 20px;">
            <div class="col-md-4 ">
      <br>Table Number:
      <input type="text" name="table_number" placeholder="Enter table number" />
      
      <br>Password:
      <input type="password" name="password" placeholder="Enter password" />
      <button class="button" type="submit" name="submit">Add Table</button>
      <?php
      error_reporting(E_ALL);
      ini_set('display_errors', 1);
      // db connection
            require_once '../conn.php';
      # initialize the variables
          if (isset($_POST['submit'])) {

            $Table_number = $_POST['table_number'];
            $Password = $_POST['password'];
            $pass = $Password; // password hashing for security reasons
            $L_status = "logged out";
            $Status = "offline";
            # Here is where we add table to database
           $sql = "INSERT INTO `tables`(`table_number`,`password`, `L_status`, `status`) VALUES('$Table_number', '$pass', '$L_status', ' $Status')";
              # here we bind parameters
            $query_execute = mysqli_query($conn,$sql);
            if ($query_execute) {
              # check whether statement was executed
              echo "Table Added Successfully";
              
              
            } else {
              # error message
              echo "Some Error Occured";
              
              

            }
            
          }
      ?>
    </div>
  </div>
  </form>
</div>

          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            
            <!--add a component here-->
            <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>150</h3>

                <p>Tables</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
            </div>
          </div>
            
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Your Satisfaction is our Priority!
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="../index.php">Cinta Foods</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
