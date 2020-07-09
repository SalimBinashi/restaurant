<!DOCTYPE html>
<html>
<head>
	<title>Cinta Restaurant | Table Login</title>
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
    .container2 {
    	
    	margin-top: 10%;
    	margin-left: 50%;
    	
    	

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
<body>
	<!--The navigation bar-->
 <header id="header" style="background-color: #8fd4f2;">
    <div class="container">

      <div class="logo float-left">
        <h1 class="text-light"><a href="#"><span><font color="white"><strong><i>Cinta Foods</i></strong></font></span></a></h1>
      </div>

      <nav class="nav-menu float-right d-none d-lg-block bg-default">
      </nav>

    </div>
  </header>
	<!--To be done during system installation-->
	<div class="container2">
	<form method="POST" action="script.php">
    		<div class="row" style="height: 800px; margin-top: 20px;">
      			<div class="col-md-4 ">
			
			<h4><label for="tables">Select Table</label></h4>
			
			<select name="table" id="tables">
				<option value=""></option>
				<?php 
					require_once 'conn.php'; //db connection

					$sql = "SELECT  `table_number` FROM `tables`"; //using select query
					$tables = mysqli_query($conn, $sql);

					while ($data = mysqli_fetch_array($tables)) {
						# echo the result from db
						echo "<option value=".$data['table_number'].">".$data['table_number']."</option>"; //display the data in the options
					}

				?>
			</select>
			<br>Password:
			<input type="password" name="password"/>
			<button class="button" type="submit" name="submit">Login Table</button>
			
		</div>
	</div>
	</form>
</div>
	
	<?php mysqli_close($conn);// close connection?>
</body>
</html>