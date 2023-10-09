<?php
session_start();
//IMPORT THE DB CONN AND auxiliaries.php
require_once "./includes/conn.php";
require_once "./includes/auxiliaries.php";

// AUTHENTICATION
if (!isset($_SESSION['user'])) {
  header("location: ./login.php");
  exit();
}

if (isset($_POST['submit'])) {
  $phoneNumber = strip_tags($_POST['phoneNumber']);
  $message = strip_tags($_POST['message']);

  $senderid = "testingapi";
  if (!empty($message) && !empty($phoneNumber)) {

    $curl = curl_init();
    $key = "10a708b0026969526aeb";
    $senderid = "testingapi";
    // $otpPhrase = "0123456789abcdefghijklmnopqrstuvwxyz";
    //    $smsotpcode =  substr(str_shuffle($otpPhrase), 0, 12);
    //    $message = "DO NOT SHARE! Your verification code is {$smsotpcode}. No Staff of Cryptozone will ask for this code. Don't share it!";


    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.innotechdev.com/sendmessage.php?key={$key}&message={$message}&senderid={$senderid}&phone={$phoneNumber}",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    $errorNumber = curl_errno($curl);
    $errorMessage = curl_error($curl);
    echo $response;

    curl_close($curl);
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/index.css" />
  <title>SMS Message Sender</title>
</head>

<body>
  <nav class="navbar">
    <a href="#">Company</a>
  </nav>
  <div class="wrapper">
    <div class="message-container">
      <form class="sms-form" action="" method="POST">
        <label for="phoneNumber">Phone Number:</label>
        <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Enter the phone number" required />

        <label for="message">Type your message here:</label>
        <textarea id="message" name="message" rows="6" placeholder="Type your message here" required></textarea>

        <button type="submit" name="submit">Send Message</button>
      </form>
    </div>
  </div>
</body>

</html>