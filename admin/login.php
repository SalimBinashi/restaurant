<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect them to their respective homepage
if(isset($_SESSION["loggedin"]) == true){
	if ($_SESSION["role"] == "chef") {
		// chef...
		header("location: chef.php");
    	exit;
	} else if ($_SESSION["role"] == "cashier") {
		// cashier...
		header("location: cashier.php");
    	exit;
	} else if ($_SESSION["role"] == "admin") {
		// code...
		header("location: index.php");
    	exit;
	}
    
}
 
// Include config file
 require_once 'conn.php';
 
// Define variables and initialize with empty values
$email = $role = $password = "";
$email_err = $password_err = $login_err = "";

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
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if($bcrypt->verify($password, $hashed_password)){
                           // change login status
                            //query
                        	$updateQuery = "UPDATE users SET login_status = 1 WHERE email = '$email'";

                        	//prepare
					          $stmt = mysqli_prepare($conn, $updateQuery);

					          //check if execution is successfull
					          if (mysqli_stmt_execute($stmt)) {

                                //PICK UP FROM HERE, YOU WERE TRYING TO SET ROLE FROM DN TO YOUR VARIABLE
                                    $roleQuery = "SELECT role FROM users WHERE email = '$email'";
                                    $roleChecker = mysqli_query($conn, $roleQuery);

                                    if ($result = $conn->query($roleQuery)) {
                                        while ($row = $result->fetch_assoc()) {
                                        $role = $row['role']; 
                                        }

                                        $_SESSION["loggedin"] = true;
                                        $_SESSION["id"] = $id;
                                        $_SESSION["email"] = $email; 
                                        $_SESSION["role"] = $role; 

                                        echo "$role";
                                            
                                        
                                        // if successful check role
                                        if ($role == "chef") {
                                          // navigate to chef
                                          header("location: chef.php");
                                          echo "Chef";

                                        } else if ($role == "cashier") {
                                          // navigate to cashier
                                          header("location: cashier.php");
                                          echo "Cashier";

                                        } else if ($role == "admin") {
                                          // navigate to admin page
                                          header("location: index.php");
                                          echo "Admin";

                                        }
                                    }
					          } else {
					              echo "Some error occured";

					          }

                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else{
                    // email doesn't exist, display a generic error message
                    $login_err = "Invalid email or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cinta Foods | Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="/favicon2.png"/>
    <link rel="icon" type="image/png" href="favicon2.png"/>
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
	<center>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label><b>Email</b></label>
                <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <label><b>Password</b></label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>
</center>
</body>
</html>