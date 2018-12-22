<?php
//login.php

/*  Sākam sesiju-->  */
session_start();

/*  Ievotojam BCryptu  */
require 'lib/password.php';

/*  Pec login nospiešanas  */

if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['signin']))) {
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

      $findUserAndPass = "SELECT id, username, password FROM users WHERE username = :username";
      $find = $db->prepare($findUserAndPass);
      $find->bindValue(':username', $username);
      $find->execute();
      $user = $find->fetch(PDO::FETCH_ASSOC);


      if ($user === false) {
        $errInvalidUserInput = '<div class="error">Incorrect username / password combination!</div>';
      } else {
        $validPassword = password_verify($password, $user['password']);

        if($validPassword){

            //Provide the user with a login session.
            $_SESSION['username'] = $user['username'];
            // $_SESSION['logged_in'] = time();

            //Redirect to our protected page, which we called home.php
            header('location:home.php');
            exit;

        } else{
            $errInvalidUserInput = '<div class="error">Incorrect username / password combination!</div>';
        }
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
        <title>Login</title>
    </head>
    <body>
      <h1>Log In</h1>
      <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
          <label maxlength="25" for="username">Username</label>
          <input pattern="[A-Za-z0-9 ]+" placeholder="Enter your username, bro" type="text" id="username" name="username" value="<?php if  (isset($username)) { echo $username;} ?>"><br>
          <?php if (isset($errUsername)) { echo $errUsername;} ?>
          <?php if (isset($errUsernameLenght)) { echo $errUsernameLenght;} ?>
          <?php if (isset($errPatternMatchUsername)) { echo $errPatternMatchUsername;} ?>



          <label for="password">Password</label>
          <input maxlength="15" pattern="[A-Za-z0-9 ]+{15}" placeholder="Password" type="password" id="password" name="password"><br>
          <?php if (isset($errPassword)) { echo $errPassword;} ?>
          <?php if (isset($errPasswordLenght)) { echo $errPasswordLenght;} ?>
          <?php if (isset($errPatternMatchPassword)) { echo $errPatternMatchPassword;} ?>

          <?php if (isset($errInvalidUserInput)) { echo $errInvalidUserInput;} ?>



          <input type="submit" name="signin" value="Signin"></button>
      </form>
    </body>
</html>
