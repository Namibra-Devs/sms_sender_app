<?php
//DATABASE CONNECTION DETAILS
$dsn = 'mysql:host=localhost;port=3308;dbname=sms_sender_db';
$username = 'root';
$password = '';

//CREATE A NEW PDO INSTANCE FOR DATABASE CONNECTION
$conn = new PDO($dsn, $username, $password);
