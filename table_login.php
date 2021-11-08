<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect them to their respective homepage
if(isset($_SESSION["loggedin"]) == true){
    echo "<script>window.open('index.php', '_self');</script>";
    
}
 $error = ''; 
?>

<?php
 
// Include config file
require_once 'conn.php';
 
// Define variables and initialize with empty values
$table_number = $password = "";
$table_number_err = $password_err = $error = "";

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
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if table_number is empty
    if(empty(trim($_POST["table_number"]))){
        $table_number_err = "Please select a table.";
    } else{
        $table_number = trim($_POST["table_number"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if(empty($table_number_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, table_number, password FROM tables WHERE table_number = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_table_number);
            
            // Set parameters
            $param_table_number = $table_number;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if table_number exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $table_number, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if($bcrypt->verify($password, $hashed_password)){
                           // change login status
                            //query
                        	$updateQuery = "UPDATE tables SET login_status = 1 WHERE table_number = '$table_number'";

                        	//prepare
                        	$stmt = mysqli_prepare($conn, $updateQuery) or die(mysqli_error($conn));

				          //check if execution is successfull
				        if (mysqli_stmt_execute($stmt)) {

	                       

	                           

	                                $_SESSION["loggedin"] = true;
	                                $_SESSION["id"] = $id;
	                                $_SESSION['table_number'] = $table_number;
	                                $_SESSION['login_status'] = 1;

	                                echo "$role"; 
	                                
	                                header("location: index.php");

	                            }
					          } else {
					              echo "Some error occured";

					          }

                        } else{
                            // Password is not valid, display a generic error message
                            $error = "Invalid table number or password.";
                        }
                    }
                } else{
                    // table number doesn't exist, display a generic error message
                    $error = "Invalid table_number or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

             }
    }
    
   
?>
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
	<form method="POST" action="table_login.php">
    		<div class="row" style="height: 800px; margin-top: 20px;">
      			<div class="col-md-4 ">
			
			<h4><label for="tables">Select Table</label></h4>
			
			<select name="table_number" id="tables">
				<option value="" required></option>
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
			<input type="password" name="password" required/>
			<button class="button" type="submit" name="submit">Login Table</button>
			<?php echo $error != '' ? '<div id="error">' . $error . '</div>' : ''; ?>
		</div>
	</div>
	</form>
	
</div>
	
</body>
</html>