<?php
/*  Izmantojot PDO, lai pieslēgtos datubāzei, vienmēr vajag Izmantojot
    try/catch struktūru -->

    try --> palaiž kodu
    catch --> ja PDO izmet exceptionu, tad to notver catch blokā

    citādāk var nozagt kkādu info  */
try {
  require_once 'pdo_connect.php';
} catch (Exception $e) {
    $error = $e->getMessage();
}
/*  Tests  */
if ($db) {
  echo 'Connected';
} else {
  echo 'Error connection database';
}

?>
