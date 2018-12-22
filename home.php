<?php

/*  Sākam sesiju-->  */
session_start();


/* Ja tukšs, tad met atpakaļ uz citu lapu   */
 if(empty($_SESSION['username'])) {
   header("location: register.php");
   exit();
 }


/* Ja viss strādā, tad:   */

echo "Congratulations! You are logged in!<br>";
echo $_SESSION['username'];
?>

<a href="logout.php">Logout</a>
