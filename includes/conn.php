<?php
//DATABASE CONNECTION DETAILS
$dsn = 'mysql:host=localhost;port=3308;dbname=sms_sender_db';
// $dsn = 'mysql:host=sdb-61.hosting.stackcp.net;dbname=mzdo_db-31393701e8';
$username = 'root';
$password = '';


//CREATE A NEW PDO INSTANCE FOR DATABASE CONNECTION
$conn = new PDO($dsn, $username, $password);
