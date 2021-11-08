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

  <title>Admin | Add Tables</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="icon" type="image/png" href="/favicon2.png"/>
  <link rel="icon" type="image/png" href="favicon2.png"/>
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
              <li class="breadcrumb-item"><a href="#">Tables</a></li>
              <li class="breadcrumb-item active"><a href='logout.php'>Log out</a></li>
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
      // create Bcrypt class
      class Bcrypt{
        private $rounds;

        public function __construct($rounds = 12) {
          if (CRYPT_BLOWFISH != 1) {
            throw new Exception("bcrypt not supported in this installation. See http://php.net/crypt");
          }

          $this->rounds = $rounds;
        }

        public function hash($input){
          $hash = crypt($input, $this->getSalt());

          if (strlen($hash) > 13)
            return $hash;

          return false;
        }

        public function verify($input, $existingHash){
          $hash = crypt($input, $existingHash);

          return $hash === $existingHash;
        }

        private function getSalt(){
          $salt = sprintf('$2a$%02d$', $this->rounds);

          $bytes = $this->getRandomBytes(16);

          $salt .= $this->encodeBytes($bytes);

          return $salt;
        }

        private $randomState;
        private function getRandomBytes($count){
          $bytes = '';

          if (function_exists('openssl_random_pseudo_bytes') &&
              (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) { // OpenSSL is slow on Windows
            $bytes = openssl_random_pseudo_bytes($count);
          }

          if ($bytes === '' && is_readable('/dev/urandom') &&
             ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
            $bytes = fread($hRand, $count);
            fclose($hRand);
          }

          if (strlen($bytes) < $count) {
            $bytes = '';

            if ($this->randomState === null) {
              $this->randomState = microtime();
              if (function_exists('getmypid')) {
                $this->randomState .= getmypid();
              }
            }

            for ($i = 0; $i < $count; $i += 16) {
              $this->randomState = md5(microtime() . $this->randomState);

              if (PHP_VERSION >= '5') {
                $bytes .= md5($this->randomState, true);
              } else {
                $bytes .= pack('H*', md5($this->randomState));
              }
            }

            $bytes = substr($bytes, 0, $count);
          }

          return $bytes;
        }

        private function encodeBytes($input){
          // The following is code from the PHP Password Hashing Framework
          $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

          $output = '';
          $i = 0;
          do {
            $c1 = ord($input[$i++]);
            $output .= $itoa64[$c1 >> 2];
            $c1 = ($c1 & 0x03) << 4;
            if ($i >= 16) {
              $output .= $itoa64[$c1];
              break;
            }

            $c2 = ord($input[$i++]);
            $c1 |= $c2 >> 4;
            $output .= $itoa64[$c1];
            $c1 = ($c2 & 0x0f) << 2;

            $c2 = ord($input[$i++]);
            $c1 |= $c2 >> 6;
            $output .= $itoa64[$c1];
            $output .= $itoa64[$c2 & 0x3f];
          } while (true);

          return $output;
        }
      }

      // intialize bcrypt
      $bcrypt = new Bcrypt(15);
      error_reporting(E_ALL);
      ini_set('display_errors', 1);
      // db connection
            require_once 'conn.php';
      # initialize the variables
          if (isset($_POST['submit'])) {

            $Table_number = $_POST['table_number'];
            $Password = $bcrypt->hash($_POST['password']);
            $pass = $Password; // password hashing for security reasons
            $L_status = "0";
            $Status = "offline";
            # Here is where we add table to database
           $sql = "INSERT INTO `tables`(`table_number`,`password`, `login_status`, `status`) VALUES('$Table_number', '$pass', '$L_status', ' $Status')";
              # here we bind parameters
            $query_execute = mysqli_query($conn,$sql);
            if ($query_execute) {
              # check whether statement was executed
              echo "Table Added Successfully";
              echo "<script>window.open('add_table.php', '_self');</script>";
              
              
            } else {
              # error message
              echo "<br> Some Error Occured";

              

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
                <h3><?php 
              require_once 'conn.php'; //db connection

            $query = "SELECT * FROM tables";
  
 
            $result = mysqli_query($conn, $query);
            // get total number of users
            $tables = mysqli_num_rows($result); 

            echo "$tables";?></h3>

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
    <strong>Copyright &copy; 2021 <a href="../index.php">Cinta Foods</a>.</strong> All rights reserved.
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
