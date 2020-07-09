<?php 
  require_once 'conn.php';

  if (isset($_POST['submit'])) {

    # initialize the variables
    $table_name = $_POST['table'];
    $pass = $_POST['password'];
   // $pass = sha1($Password); # changing password from plain text to hexadecimal value for security

    # bind selected variables to the table_name which in this case is the table_number
    if ($stmt = $conn->prepare('SELECT `table_id`, `table_number`, `password` FROM `tables` WHERE `table_number` = ?')) {
      
      $stmt->bind_param('s', $_POST['table']);
      $stmt->execute();
      $stmt->store_result();

      # check whether table number existss in db
      if ($stmt->num_rows > 0) {
        
        $stmt->bind_result($table_id, $table_number, $pass_from_db);
        $stmt->fetch();

       # if it exists, checking whether password input is correct
        if ($pass == $pass_from_db) {
          
          session_start();
          $_SESSION['table_number'] = $table_number;
          $_SESSION['table_id'] = $table_id;
          # updating loging status
          $L_status = "logged_in";
          $sql ="UPDATE `tables` SET `L_status` = '$L_status' WHERE `table_number` = '$table_name'";
          $query_execute = mysqli_query($conn, $sql);
          if ($query_execute) {
            # if all is okay, change into the index file
            echo "<script>window.open('index.php', '_self')</script>";
          } else {
            echo "Some error occured";
          }
          
        } else {
          echo "<font color='red'> Incorrect Password or Invalid table name!</font>";
          echo $pass."compare".$pass_from_db;
        }
      } else {
        echo "<font color='red'> Table not found!</font>";
      }
    }
  }
  
?>