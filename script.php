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

  require_once 'conn.php';

  if (isset($_POST['submit'])) {

    # initialize the variables
    $table_name = $_POST['table'];
    $Password = $bcrypt->hash($_POST['password']);
    $pass = $Password; // password hashing for security reasons
   // $pass = sha1($Password); # changing password from plain text to hexadecimal value for security

    # bind selected variables to the table_name which in this case is the table_number
    if ($stmt = $conn->prepare('SELECT `id`, `table_number`, `password` FROM `tables` WHERE `table_number` = ?')) {
      
      $stmt->bind_param('s', $_POST['table']);
      $stmt->execute();
      $stmt->store_result();

      # check whether table number existss in db
      if ($stmt->num_rows > 0) {
        
        $stmt->bind_result($table_id, $table_number, $pass_from_db);
        $stmt->fetch();

       # if it exists, checking whether password input is correct
        if ($pass != 0) {
          
          session_start();
          $_SESSION['table_number'] = $table_number;
          $_SESSION['id'] = $table_id;
          # updating loging status
          $L_status = "logged_in";
          $sql ="UPDATE `tables` SET `L_status` = '$L_status' WHERE `table_number` = '$table_name'";
          $query_execute = mysqli_query($conn, $sql);
          if ($query_execute) {
            # if all is okay, change into the index file
            echo "<script>window.open('index.php', '_self')</script>";
          } else {
            $_SESSION['id'] = $table_id;

             echo "<script>window.open('index.php', '_self')</script>";

            // echo "Some error occured";
          }
          
        } else {
            $_SESSION['id'] = $table_id;

            echo "<script>window.open('index.php', '_self')</script>";

          // echo "<font color='red'> Incorrect Password or Invalid table name!</font>";
          // echo $pass."compare".$pass_from_db;
        }
      } else {
        echo "<font color='red'> Table not found!</font>";
      }
    }
  }
  
?>