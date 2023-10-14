<?php

//    SEND SMS OTP
$curl = curl_init();
$key = "10a708b0026969526aeb";
$senderid = "testingapi";
$otpPhrase = "0123456789abcdefghijklmnopqrstuvwxyz";
$smsotpcode =  substr(str_shuffle($otpPhrase), 0, 12);
$message = "DO NOT SHARE! Your verification code is {$smsotpcode}. No Staff of Cryptozone will ask for this code. Don't share it!";


curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.innotechdev.com/sendmessage.php?key={$key}&message='hi charls'&senderid={$senderid}&phone=0548715098",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
print_r($response);
curl_close($curl);
