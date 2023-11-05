<?php
session_start();
//IMPORT THE DB CONN AND auxiliaries.php
require_once "./includes/conn.php";
require_once "./includes/auxiliaries.php";
$alert = "";

// AUTHENTICATION
if (!isset($_SESSION['user'])) {
  header("location: ./login.php");
  exit();
}

$id = $_SESSION['user'];
$Admin = new Admin($conn, "admin");
$isAdmin = $Admin->read('id', $id)[0]['isAdmin'];

if (isset($_POST['submit'])) {
  $message = strip_tags($_POST['message']);
  $phoneNumber = strip_tags($_POST['phoneNumber']);
  $amount = strip_tags($_POST['amount']);
  $date = strip_tags($_POST['date']);
  $name = strip_tags($_POST['name']);

  // Message placeholders
  $placeholders = array(
    '{phone}' => $phoneNumber,
    '{amount}' => $amount . ' cedis',
    '{date}' => $date,
    '{name}' => $name
  );

  // Replace placeholders in the message
  $finalMessage = str_replace(array_keys($placeholders), array_values($placeholders), $message);

  $senderid = "testingapi";
  if (!empty($finalMessage) && !empty($phoneNumber)) {
    $user = new Admin($conn, 'message');
    $userInfo = [
      'name' => $name,
      'phone' => $phoneNumber,
      'amount' => $amount,
      'date' => $date,
      'supervisor' => $id,
    ];
    $user->create($userInfo);

    // API URL
    $apiUrl = 'https://api.innotechdev.com/sendmessage.php';

    // Query parameters
    $key = '10a708b0026969526aeb';
    $senderId = 'testingapi';

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $apiUrl . '?key=' . $key . '&message=' . urlencode($finalMessage) . '&senderid=' . $senderId . '&phone=' . $phoneNumber);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL session
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
      echo 'cURL error: ' . curl_error($ch);
    } else {
      // Check the HTTP status code to ensure a successful request
      $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if ($httpStatus === 200) {
        $alert = "showAlert('success', 'Message Sent Successfully')";
      } else {
        $alert = "showAlert('error', 'Couldn't Send message')";
      }
    }

    // Close the cURL session
    curl_close($ch);
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/index.css" />

  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="./js/style.js"> </script>

  <title>SMS Message Sender</title>
  <style>
    .ml {
      margin-left: 30px;
    }
  </style>
</head>

<body>
  <?php
  echo "<script>";
  echo $alert;
  echo "</script>";
  ?>
  <nav class="navbar">
    <a href="#">Company</a>
    <div>
      <?php
      if ($isAdmin) {
        echo "<a href='admins.php' class='ml'><button>";
        echo  "Admins";
        echo "</button></a>";
      }
      ?>
      <a href="expenses.php" class="ml"><button>
          Expenses
        </button>
      </a>
      <a href="sponsorships.php" class="ml"><button>
          Sponsorship
        </button>
      </a>

      <a href="analytics.php" class="ml"><button>
          Reports
        </button>
      </a>

      <a href="./messages.php" class="ml">
        <button>
          sent messages
        </button>
      </a>

      <a href="./logout.php" class="ml">
        <img src="./includes/icons/shutdown.png" style="margin-bottom: -25px">
      </a>
    </div>

  </nav>
  <div class="wrapper">
    <div class="message-container">
      <form class="sms-form" action="" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter the name" required />

        <label for="phoneNumber">Phone Number:</label>
        <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Enter the phone number" required />
        <div class="grouped">
          <div>
            <label for="amount">Amount</label>
            <input type="text" id="amount" name="amount" placeholder="Enter the Amount" required />
          </div>

          <div> <label for="date">Date:</label>
            <input type="date" id="date" name="date" placeholder="Choose date" required />
          </div>

        </div>
        <label for="message">Type your message here:</label>
        <textarea id="message" name="message" rows="6" placeholder="Type your message here" required></textarea>

        <button type="submit" name="submit">Send Message</button>
      </form>
    </div>
  </div>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

</body>

</html>