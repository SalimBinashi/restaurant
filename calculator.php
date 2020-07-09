<?php

session_start();

$table_number = $_SESSION['table_number'];  

//We will be getting this table_number from the login page where user logs in successfully and creates as session using his or her table_number whil will be used as a global variable to all pages.

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Main Meals</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">
  <link href="assets/img/.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i,900" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    #meals_buttons{
      width: 450px;
	  text-align: left;
    }
    #item_button{
      float: right;
      border-radius: 7px;
      height: 40px;
    }
    #items_section{
      overflow: scroll;
      max-height: 1000px;
    }
    #selected_item_button{
      width: 200px;
      float: right;
    }
    #calculate_button{
      float: left;
      width: 150px;
    }
    #clear_button{
      float: right;
      width: 150px;
    }
    #items_list{
      overflow: scroll;
      max-height: 260px;
    }
    #print_button{
      float: left;
      width: 150px;
    }
    #pay_button{
      float: right;
      width: 150px;
    }
	#order_button{
		float: center;
		width: 225px;
	}
  </style>
</head>
 <!--The navigation bar-->
 <header id="header" style="background-color: #8fd4f2;">
    <div class="container">

      <div class="logo float-left">
        <h1 class="text-light"><a href="index.html"><span><font color="white"><strong><i>Cinta Foods</i></strong></font></span></a></h1>
      </div>

      <nav class="nav-menu float-right d-none d-lg-block bg-default">
      </nav>

    </div>
  </header> 

<body>

  <div class="container-fluid">
    <div class="row" style="height: 800px; margin-top: 20px;">
      <div class="col-md-4 ">
        <h4>Classes of meals we offer</h4></br>

        <!--The buttons for classes of meals-->

        <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
        <button class="btn btn-info" id="meals_buttons" name="main" type="submit">Main meal</button><br><br>
        <button class="btn btn-warning" id="meals_buttons" name="appetizer"><font color="white">Appetizer</font></button><br><br>
        <button class="btn btn-success" id="meals_buttons" name="starter">Starter</button><br><br>
        <button class="btn btn-danger" id="meals_buttons" name="beverages">Beverages</button><br><br>
        <button class="btn btn-info" id="meals_buttons" name="buffet">Buffet</button>
      </form>
      </div>
      <div class="col-md-4 " id="items_section">

        <!--This is the second column which will display all meals within the class the user has selected or all meals available by default if user hasn't chisen any class of meals-->

        <center>
        <h4>Meals we offer</h4>
      </center>
<?php
         
         require_once 'conn.php';

         if (isset($_POST['main'])) {
           $query = "SELECT * FROM meals WHERE classification = 'Main meal'";
           $query_execute = mysqli_query($conn,$query);
           if ($query_execute) {
            while ($row = mysqli_fetch_assoc($query_execute)) {
             $name = $row['name'];
             $price = $row['price'];
             $image = $row['image'];
             $id = $row['id'];

             echo "
               <div class='card'>
               <div class='card-body'><img src='$image' height='400px' width='100%'></div>
               <div class='card-footer'>
               Name: $name</br>
               Price: $price</br>
               <form method='POST' action='calculator.php?item_id=$id'>
               <button class='btn btn-success' id='item_button' name='item'>Select meal</button>
               </form>
               </div>
               </div></br>
             ";
            }
           }
         }
         elseif (isset($_POST['appetizer'])) {
            $query = "SELECT * FROM meals WHERE classification = 'Appetizer'";
           $query_execute = mysqli_query($conn,$query);
           if ($query_execute) {
            while ($row = mysqli_fetch_assoc($query_execute)) {
             $name = $row['name'];
             $price = $row['price'];
             $image = $row['image'];

            $id = $row['id'];

             echo "
               <div class='card'>
               <div class='card-body'><img src='$image' height='400px' width='100%'></div>
               <div class='card-footer'>
               Name: $name</br>
               Price: $price</br>
               <form method='POST' action='calculator.php?item_id=$id'>
               <button class='btn btn-success' id='item_button' name='item'>Select meal</button>
               </form>
               </div>
               </div></br>
             ";
            }
           }
         }
          elseif (isset($_POST['starter'])) {
            $query = "SELECT * FROM meals WHERE classification = 'Starter'";
           $query_execute = mysqli_query($conn,$query);
           if ($query_execute) {
            while ($row = mysqli_fetch_assoc($query_execute)) {
             $name = $row['name'];
             $price = $row['price'];
             $image = $row['image'];

             $id = $row['id'];

             echo "
               <div class='card'>
               <div class='card-body'><img src='$image' height='400px' width='100%'></div>
               <div class='card-footer'>
               Name: $name</br>
               Price: $price</br>
               <form method='POST' action='calculator.php?item_id=$id'>
               <button class='btn btn-success' id='item_button' name='item'>Select meal</button>
               </form>
               </div>
               </div></br>
             ";
            }
           }
         }
          elseif (isset($_POST['beverages'])) {
           $query = "SELECT * FROM meals WHERE classification = 'Beverages'";
           $query_execute = mysqli_query($conn,$query);
           if ($query_execute) {
            while ($row = mysqli_fetch_assoc($query_execute)) {
             $name = $row['name'];
             $price = $row['price'];
             $image = $row['image'];

            $id = $row['id'];

             echo "
               <div class='card'>
               <div class='card-body'><img src='$image' height='400px' width='100%'></div>
               <div class='card-footer'>
               Name: $name</br>
               Price: $price</br>
               <form method='POST' action='calculator.php?item_id=$id'>
               <button class='btn btn-success' id='item_button' name='item'>Select meal</button>
               </form>
               </div>
               </div></br>
             ";
            }
           }
         }
           elseif (isset($_POST['buffet'])) {
            $query = "SELECT * FROM meals WHERE classification = 'Buffet'";
           $query_execute = mysqli_query($conn,$query);
           if ($query_execute) {
            while ($row = mysqli_fetch_assoc($query_execute)) {
             $name = $row['name'];
             $price = $row['price'];
             $image = $row['image'];

            $id = $row['id'];

             echo "
               <div class='card'>
               <div class='card-body'><img src='$image' height='400px' width='100%'></div>
               <div class='card-footer'>
               Name: $name</br>
               Price: $price</br>
               <form method='POST' action='calculator.php?item_id=$id'>
               <button class='btn btn-success' id='item_button' name='item'>Select meal</button>
               </form>
               </div>
               </div></br>
             ";
            }
           }
         }

         //This is the query we use to display all meals available by default if the user hasn't chosen any class of meals or rather has not clicked any button.

         else{
           $query = "SELECT * FROM meals";
           $query_execute = mysqli_query($conn,$query);
           if ($query_execute) {
            while ($row = mysqli_fetch_assoc($query_execute)) {
             $name = $row['name'];
             $price = $row['price'];
             $image = $row['image'];
           $id = $row['id'];

             echo "
               <div class='card'>
               <div class='card-body'><img src='$image' height='400px' width='100%'></div>
               <div class='card-footer'>
               Name: $name</br>
               Price: $price</br>
               <form method='POST' action='calculator.php?item_id=$id'>
               <button class='btn btn-success' id='item_button' name='item'>Select meal</button>
               </form>
               </div>
               </div></br>
             ";
           }
         }
         }

?>
      </div>
      <div class="col-md-4"> 

        <!--This is the third column which will display the item selected by the user, list of items he or she has selected and the total price of all the items selected based on the users table_number which is a global variable obtained and passed while creating a session while logging in. It was also display all the necessary components if the user has not selected any item or clicked any button from the column with all meals-->
<?php
        require_once 'conn.php';

        if (isset($_POST['item'])) {
          if (isset($_GET['item_id'])) {
             $item_id = $_GET['item_id'];
              $query = "SELECT * FROM meals WHERE id = '$item_id'";
               $query_execute = mysqli_query($conn,$query);
           if ($query_execute) {
            while ($row = mysqli_fetch_assoc($query_execute)) {
             $class = $row['classification']; 
             $name = $row['name'];
             $type = $row['type'];
             $price = $row['price'];
             $image = $row['image'];
             $id = $row['id'];
           echo "
               <div class='card'>
               <div class='card-header'>
              <h4>Item description</h4>
              </div>
               <div class='card-body'>
               Class: $class</br>
               Type: $type</br>
               Name: $name</br>
               Price: $price<br><br>
               <form method='POST' action='calculator.php?item_id=$id'>
               <input type='number' class='form-control' placeholder='Enter quantity you want' name='quantity' required='required'></br>
               </div>
               <div class='card-footer'>
               <button class='btn btn-warning' id='selected_item_button' name='select_item'><font color='white'>Add to cart</font></button>
               </form>
               </div>
               </div></br>
             ";
            
           }
         }

        
         }
           $query_run = "SELECT * FROM items_selected WHERE table_number = '$table_number'";
               $query_execute_run = mysqli_query($conn,$query_run);
           if ($query_execute_run) {
            

             echo "
               
               <div class='card'>
                <div class='card-header'>
              <h4>Items selected</h4>
              </div>
               <div class='card-body' id='items_list'>
                 <table border='1px' width='100%'' cellpadding='1px' cellspacing='1px'>
                 <tr>
                    <th><center>Name</center></th>
                   <th><center>Price</center></th>
                   <th><center>Quantity</center></th>
                   <th><center>Remove</center></th>
                 </tr>";
                 while ($row = mysqli_fetch_assoc($query_execute_run)) { 
             $name = $row['name'];
             $price = $row['price'];
             $quantity = $row['quantity'];
             $id = $row['id'];
             echo"
             <tr>
                  <td><center>$name</center></td>
                 <td><center>$price</center></td>
                 <td><center>$quantity</center></td>
                 <td><center><a href='calculator.php?item_to_remove_id=$id'><font color='red'>Remove</font></a></center></td>
                 </tr>
                 ";
               }
              
               require_once 'conn.php';
               $sqli = "SELECT total_price FROM total_price WHERE table_number ='$table_number' AND payment_status = 'Pending'";
               $execute_sqli = mysqli_query($conn,$sqli);
               while ($row = mysqli_fetch_assoc($execute_sqli)) {
                $total_cost = $row['total_price'];
              }
                 echo"
                 </table>
               </div>
               <div class='card-footer'>
                <form method='POST' action='calculator.php?item_id=$id'>
               <button class='btn btn-success' id='calculate_button' name='calculate'>Calculate total</button>
               <button class='btn btn-danger' id='clear_button' name='clear'>Clear list</button>
               </form>
               </div>
               </div></br>

               <div class='card'>
                 <div class='card-header'>
              <h4>Total cost</h4>
              </div>
               <div class='card-body'>
               <form class='form-inline'>
               <label>Total:</label>
               <input class='form-control' style='width: 100%;' value='$total_cost'>
               </form>
               </div>
               <form method='POST' action='calculator.php'>
               <div class='card-footer'>
               <button class='btn btn-success' id='order_button' name='order'>Order</button>
               </div>
               </div></br>
               </form>
             

             ";

           
         }

             
          }
       
       //This is the query we use to display the default components on this coluimn if no button has been clicked from the column with meals.

        else{

          require_once 'conn.php';

           echo "
               <div class='card'>
               <div class='card-header'>
              <h4>Item description</h4>
              </div>
               <div class='card-body'>
               Class: </br>
               Type: </br>
               Name: </br>
               Price: <br><br>
               <form method='POST' action='calculator.php'>
               <input type='number' class='form-control' placeholder='Enter quantity you want' name='quantity' required='required'></br>
               </div>
               <div class='card-footer'>
               <button class='btn btn-warning' id='selected_item_button' name='select_item'><font color='white'>Add to cart</font></button>
               </form>
               </div>
               </div></br>
             ";
          
         

        
       
           $query_run = "SELECT * FROM items_selected WHERE table_number = '$table_number'";
               $query_execute_run = mysqli_query($conn,$query_run);
           if ($query_execute_run) {
            

             echo "
               
               <div class='card'>
                <div class='card-header'>
              <h4>Items selected</h4>
              </div>
               <div class='card-body' id='items_list'>
                 <table border='1px' width='100%'' cellpadding='1px' cellspacing='1px'>
                 <tr>
                    <th><center>Name</center></th>
                   <th><center>Price</center></th>
                   <th><center>Quantity</center></th>
                   <th><center>Remove</center></th>
                 </tr>";
                 while ($row = mysqli_fetch_assoc($query_execute_run)) { 
             $name = $row['name'];
             $price = $row['price'];
             $quantity = $row['quantity'];
             $id = $row['id'];
             echo"
             <tr>
                  <td><center>$name</center></td>
                 <td><center>$price</center></td>
                 <td><center>$quantity</center></td>
                 <td><center><a href='calculator.php?item_to_remove_id=$id'><font color='red'>Remove</font></a></center></td>
                 </tr>
                 ";
               }
             }
              
               require_once 'conn.php';
               $sqli = "SELECT total_price FROM total_price WHERE table_number ='$table_number' AND payment_status = 'Pending'";
               $execute_sqli = mysqli_query($conn,$sqli);
               while ($row = mysqli_fetch_assoc($execute_sqli)) {
                $total_cost = $row['total_price'];
              }
                 echo"
                 </table>
               </div>
               <div class='card-footer'>
                <form method='POST' action='calculator.php'>
               <button class='btn btn-success' id='calculate_button' name='calculate'>Calculate total</button>
               <button class='btn btn-danger' id='clear_button' name='clear'>Clear list</button>
               </form>
               </div>
               </div></br>

               <div class='card'>
                 <div class='card-header'>
              <h4>Total cost</h4>
              </div>
               <div class='card-body'>
               <form class='form-inline'>
               <label>Total:</label>
               <input class='form-control' style='width: 100%;' value='$total_cost'>
               </form>
               </div>
               <form method='POST' action='calculator.php'>
               <div class='card-footer'>
               <button class='btn btn-success' id='order_button' name='order'>Order</button>
               </div>
               </div></br>
               </form>
             

             ";
           }

           
         
        

?>
<?php

        //This the query that handles the requested executed when user clicks the button 'Add to cart' or rather adding item to his/her own list of items.

        if (isset($_POST['select_item'])) {
          if (isset($_GET['item_id'])) {
             $item_id = $_GET['item_id'];
            require_once 'conn.php';
            $quantity = $_POST['quantity'];
            $payment_status = "Pending";
			$status = "Pending";
             $queryy = "SELECT * FROM meals WHERE id = '$item_id'";
               $query_run = mysqli_query($conn,$queryy);
               if ($query_run) {
              while ($row = mysqli_fetch_assoc($query_run)) { 
               $name = $row['name'];
               $price = $row['price'];
               $total_price = $price * $quantity;

               $sqli = "INSERT INTO items_selected(table_number,name,price,quantity,total_price,payment_status) VALUES('$table_number','$name','$price','$quantity','$total_price','$payment_status')";
               $execute = mysqli_query($conn,$sqli);
               if ($execute) {
				   
			   }
               else{
                echo "unsuccessful";
              }
			  
             }
           }
         }
       }
?>
			  
<?php

        //This the query that handles the requested executed when user clicks the button 'Add to cart' or rather adding item to his/her own list of items.

        if (isset($_POST['order'])) {
          if (isset($_GET['item_id'])) {
             $item_id = $_GET['item_id'];
            require_once 'conn.php';
            $quantity = $_POST['quantity'];
			$status = "Pending";
             $queryy2 = "SELECT * FROM meals WHERE id = '$item_id'";
               $query_run2 = mysqli_query($conn,$queryy2);
               if ($query_run2) {
              while ($row = mysqli_fetch_assoc($query_run2)) { 
               $name = $row['name'];
               $price = $row['price'];
               $total_price = $price * $quantity;

               $sqli2 = "INSERT INTO orders(table_number,order,quantity,status) VALUES('$table_number','$name','$quantity','$status')";
               $execute2 = mysqli_query($conn,$sqli2);
               if ($execute2) {
				   
			   }
               else{
                echo "unsuccessful";
              }
			  
             }
           }
         }
       }
?>

<?php 
   
        //This a query that handles the request executed when user clicks the button 'Calculate' or rather calculating the total cost of the items in his/her list.

        if (isset($_POST['calculate'])) {
            require_once 'conn.php';
            $payment_status = "Pending";
             $queryy = "SELECT total_price FROM items_selected WHERE table_number = '$table_number' AND payment_status ='Pending'";
               $queryy_run = mysqli_query($conn,$queryy);
               if ($queryy_run) {
              while ($row = mysqli_fetch_array($queryy_run)) { 
               $total[] = $row['total_price'];
               $total_price = array_sum($total);
             
              $sqli = "INSERT INTO total_price(table_number,total_price,payment_status) VALUES('$table_number','$total_price','$payment_status')";
               $execute = mysqli_query($conn,$sqli);
               if ($execute) {
               }
               else{
                echo "unsuccessful";
           }
         }
         }
       }
?>

<?php

         //This is a query that handles the request executed when user clicks the button 'Clear list' or rather wants to clear all the items in his/her list.

         if (isset($_POST['clear'])) {
            require_once 'conn.php';

            $sqli = "DELETE FROM items_selected WHERE table_number ='$table_number'";
            $execute_sqli = mysqli_query($conn,$sqli);
            if ($execute_sqli) {
            }
            else{
              echo "unsuccess";
            }
          }
?>

<?php

        //This is a query that handles the request executed when user clicks the link 'Remove' from the table with a list of the items he/she selected or rather when a user requests to remove a single item from his/her list

        if (isset($_GET['item_to_remove_id'])) {
          $item_to_remove_id = $_GET['item_to_remove_id'];
           $sqli = "DELETE FROM items_selected WHERE id = '$item_to_remove_id'";
            $execute_sqli = mysqli_query($conn,$sqli);
            if ($execute_sqli) {
            }
            else{
              echo "unsuccess";
            }
        }
?>
      </div>
    </div>
  </div>

   <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-table_number-form/validate.js"></script>
  <script src="assets/vendor/jquery-sticky/jquery.sticky.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>