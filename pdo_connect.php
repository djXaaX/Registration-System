<?php
/*  Datubāzes configs  */
$DB_USER = 'root';
$DB_PASS = '';
$DB_HOST = 'localhost';
$DB_DATABASE = 'registration';

/*
1. Jāsāk ar DSN (Database Source Name), kas identificē, kurai datubāzei jāpieslēdzās
  1.1 Katrai datubāzei ir savs DSN formāts
  // MySQL
  $dsn = 'mysql:host=localhost;dbname=registration';
  $dsn = 'mysql:host=localhost;port=3307;dbname=registration';

  // SQLite 3
  $dsn = 'sqlite:/path/to/registration.db';

  // MS SQL Server
  $dsn = 'sqlsrv:Server=localhost;Database=registration';

2. Prefix ar kolu identificē PDO draiveri
3. Vērtības vai to pārus atšķir ar koliem

  // Dokumentācija http://www.php.net/manual/en/pdo.drivers.php/

5. DSN ir datubāzei specifisks viss pārējais PDO kods ir datubāžu neitrāls,
   ja es gribu pieslēgties citai datubāzei, es  vienkārši samainu $dsn mainīgo
   un attiecīgi pārējais kods strādās.
*/
/*  PDO configs  */
$dsn = 'mysql:host='.$DB_HOST.';dbname='.$DB_DATABASE.'';
$db = new PDO($dsn, $DB_USER, $DB_PASS);
