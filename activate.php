<?php
        session_start();
        $table_id = $_SESSION['table_id'];
        require_once 'conn.php';
        

        $Status = "online";

    if (isset($_POST['status'])) {
      # code...

      $sql ="UPDATE `tables` SET `status` = '$Status' WHERE `table_id` = $table_id";
      $query_execute = mysqli_query($conn, $sql);
          if ($query_execute) {
            # code...
            echo "Activated";
            header("location:calculator.php");
          }
      
    }
  ?>