<?php
//register.php

/*  Sākam sesiju-->  */
session_start();

/*  Ievotojam BCryptu  */
require 'lib/password.php';

/*  Pec reģistrēties nospiešanas  */

if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['register']))) {
  if (isset($_POST['username'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING );
  } else {
    $username = "";
  }
  if (isset($_POST['password'])) {
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING );
  } else {
    $password = "";
  }



  $formErrors = false;



  if ($username === '') {
    $errUsername = '<div class="error">Sorry, your name is required field</div>';
    $formErrors = true;
  }
  if ($password === '') {
    $errPassword = '<div class="error">Sorry, your password is required field</div>';
    $formErrors = true;
  }
  if (strlen($username) <= 6) {
    $errUsernameLenght = '<div class="error">Sorry, the username must be at least 6 characters long</div>';
    $formErrors = true;
  }
  if (strlen($password) <= 8) {
    $errPasswordLenght = '<div class="error">Sorry, the password must be at least 8 characters</div>';
    $formErrors = true;
  }


  if ( !(preg_match('/[a-zA-Z]+/', $username )) ) {
    $errPatternMatchUsername = '<div class="error">Sorry, the name must be in the format: Username</div>';
    $formErrors = true;
  }
  if ( !(preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $password )) ) {
    $errPatternMatchPassword = '<div class="error">Sorry, the password must be in the format: Capital first letter number or special character</div>';
    $formErrors = true;
  }

  if (!($formErrors)) {
    /*  Datubāzes konfigs  */
    try {
      require 'pdo_connect.php';


      /*  Kā šito labāk uzrkastīt? , lai, kad viņu eksekjuto, viņš iet kopā ar augstāk -$formErrors, citādāk ja iziet cauri validacijai
          tad, ja niks jau eksistē, tad  tikai tad viņš to pasaka, nevis jau kopā ar visiem $fromErrors */

      $insertError = "SELECT id FROM users WHERE username=:username";

      $checkUsername = $db->prepare($insertError);
      $checkUsername->bindParam("username", $username, PDO::PARAM_STR);
      $checkUsername->execute();
      if ($checkUsername->rowCount() > 0) { // Ja ir durplikāts - :
          $invalidUsername =  '<div class="error">Lietotājs jau eksistē, ievadīt citu</div>';
      } else {

        $passwordHash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 12));

        $insertUserAndPass = "INSERT INTO users (username, password) values(:username,:password)";

        $insert = $db->prepare($insertUserAndPass);
        $insert->bindParam(':username', $username);
        $insert->bindParam(':password', $passwordHash);
        $insert->execute();
        echo 'You have registered successfully!';
      }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
  }
}

?>
<!DOCTYPE html>
<html>
   <head>
       <meta charset="UTF-8">
       <title>Register/Log in</title>
   </head>
   <body>
     <h1>Register</h1>
     <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
         <label for="username">Username</label>
         <input maxlength="25" pattern="[A-Za-z0-9]+" placeholder="Enter your username MAX: 25s" required type="text" id="username" name="username" value="<?php if  (isset($username)) { echo $username;} ?>"><br>
         <?php if (isset($invalidUsername)) { echo $invalidUsername;} ?>
         <?php if (isset($errUsername)) { echo $errUsername;} ?>
         <?php if (isset($errUsernameLenght)) { echo $errUsernameLenght;} ?>
         <?php if (isset($errPatternMatchUsername)) { echo $errPatternMatchUsername;} ?>


         <label for="password">Password</label>
         <input maxlength="25" pattern="[A-Za-z0-9 ]+{15}" placeholder="Password MAX: 15s" required type="password" id="password" name="password"><br>
         <?php if (isset($errPassword)) { echo $errPassword;} ?>
         <?php if (isset($errPasswordLenght)) { echo $errPasswordLenght;} ?>
         <?php if (isset($errPatternMatchPassword)) { echo $errPatternMatchPassword;} ?>


         <input type="submit" name="register" value="Register"></button>
     </form>
   </body>
</html>
