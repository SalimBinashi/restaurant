<?php
  session_start();
  if (!isset($_SESSION['table_id'])) {
    header('Location:table_login.php');
  }

  $table_id = $_SESSION['table_id'];
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  /*html { 
  background: url(images/bg5.jpg) no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}*/
body{
  margin: 0;
  padding: 0;
  font-family: arial; 
}
.button {
  border-radius: 4px;
  background-color: #f4511e;
  border: none;
  color: #FFFFFF;
  text-align: center;
  font-size: 28px;
  padding: 20px;
  width: auto;
  border-radius: 50px;
  transition: all 0.5s;
  cursor: pointer;
  margin: 5px;
  margin-top: 20%;
}

.button span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}

.button span:after {
  content: '\00bb';
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.5s;
}

.button:hover span {
  padding-right: 25px;
}

.button:hover span:after {
  opacity: 1;
  right: 0;
}
.container{
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  animation: animate 30s ease-in-out infinite;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background: no-repeat center center fixed;
  background-size: cover;
  

}

@keyframe animate{
  0%,100%{
    background-image: url(images/hd1.jpg);
  }
  25%{
    background-image: url(images/hd2.jpg);
  }
  50%{
    background-image: url(images/hd3.jpg);
  }
  75%{
    background-image: url(images/hd4.jpg);
  }
  
}
</style>
</head>
<body>
  <div class="container">
    <div class="outer">
      <div class="details">
        <form method="POST" action="activate.php">
          <center><button class="button" type="submit" name="status"><span>Activate Table </span></button></center>
        </form>
        
      </div>
    
  </div>
  </div>
  </body>
</html>