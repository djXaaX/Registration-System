<?php

/**
 * Start the session.
 */
session_start();


/**
 * Check if the user is logged in.
 */
 if(empty($_SESSION['username'])) {
   header("location: register.php");
   exit();
 }


/**
 * Print out something that only logged in users can see.
 */

echo "Congratulations! You are logged in!<br>";
echo $_SESSION['username'];
?>

<a href="logout.php">Logout</a>
