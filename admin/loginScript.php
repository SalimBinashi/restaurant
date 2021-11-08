<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect them to their respective homepage
if(isset($_SESSION["loggedin"]) == true){
  if ($_SESSION["role"] == "chef") {
    // chef...
    echo "You are not authorized to view this page";
    echo "<script>window.open('chef.php', '_self');</script>";
  } else if ($_SESSION["role"] == "cashier") {
    // cashier...
        echo "You are not authorized to view this page";
        echo "<script>window.open('cashier.php', '_self');</script>";

  }
    
} else {
    // code...
    header("location: login.php");
      exit;
  }
?>
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

  if (isset($_POST['submit'])) {

    # initialize the variables
    $email = $_POST['email'];
    $pass = $bcrypt->hash($_POST['password']);
   // $pass = sha1($Password); # changing password from plain text to hexadecimal value for security

    if ($stmt = $conn -> prepare('SELECT `id`, `email`, `password`, `role` FROM `users` WHERE `email` = ?')) {
      // bind params
      $stmt->bind_param('s', $_POST['email']);
      $stmt->execute();
      $stmt->store_result();

      // from the results check if the email exists
      if ($stmt->num_rows > 0) {
        // record exists
        $stmt->bind_result($id, $email, $pass_from_db, $role);
        $stmt->fetch();

        // checking the password
        if ($pass == $pass_from_db) {

          // create a session
          session_start();
          $_SESSION['email'] = $email;
          $_SESSION['role'] = $role;



          //update the login status
          $L_status = 1;

          $sql = "UPDATE `users` SET `login_status` = '$L_status' WHERE `email` = '$email'";

          //execute
          $query_execute = mysqli_query($conn, $sql);

          //check if execution is successfull
          if ($query_execute) {
            $_SESSION['login_status'] = $login_status;

            // if successful check role
            if ($role === "chef") {
              // navigate to chef
              echo "<script>window.open('chef.php', '_self')</script>";

            } else if ($role === "cashier") {
              // navigate to cashier
              echo "<script>window.open('cahsier.php', '_self')</script>";

            } else if ($role === "admin") {
              // navigate to admin page
              echo "<script>window.open('index.php', '_self')</script>";

            }
          } else {
              echo "Some error occured";

          }
        } else {
            echo "Incorrect email or password";
            echo "$pass";
            echo "$pass_from_db";

        }
      } else {
          echo "User does not exist";

      }
    }
  }
  
?>