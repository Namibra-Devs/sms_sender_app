 <?php
    session_start();
    //IMPORT THE DB CONN AND auxiliaries.phpS
    require_once "./includes/conn.php";
    require_once "./includes/auxiliaries.php";
    $alert = "";
    //CHECK IF THE SUBMIT BUTTON IS CLICKED
    if (isset($_POST['submit'])) {

        $email = strip_tags($_POST['email']);
        $password = strip_tags($_POST['password']);

        //CREATE AN OBJECT OF THE CLASS ADMIN
        $Admin = new Admin($conn, "admin");

        //CALL THE READ FUNCTION OF THE ADMIN CLASS AND STORE RESPONSE IN $results
        $results = $Admin->read("email", $email);

        //CHECK IF THE NUMBER OF ROW RETURNED IS > 0
        if (!empty($results)) {
            $retrievedEmail = $results[0]["email"];
            $retrievedID = $results[0]["id"];
            $retrievedPassword = $results[0]["password"];

            //CHECK IF USER EMAIL IS EQUAL TO RETRIEVED EMAIL AND USER PASSWORD IS EQUAL TO RETRIEVED PASSWORD
            if ($email == $retrievedEmail && password_verify($password, $retrievedPassword)) {
                //REDIRECT USER TO SEND SMS PAGE
                $_SESSION['user'] = $retrievedID;
                $_SESSION['name'] = $results[0]["name"];
                $_SESSION['zone'] = $results[0]["zone"];
                header("location: ./index.php");
                exit();
            } else {
                //SHOW ERROR TO USER
                $alert = "showAlert('error', 'wrong email or password')";
            }
        } else {
            //SHOW ERROR TO USER
            $alert = "showAlert('error', 'Account Not found')";
        }
    }
    ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>SMS Sender</title>
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700;1,900&display=swap" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
     <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
     <link rel="stylesheet" href="css/style.css">
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="./js/style.js"> </script>

     </link>

 </head>

 <body>
     <?php
        echo "<script>";
        echo $alert;
        echo "</script>";
        ?>
     <div class="container">
         <nav class="navbar">
             <a href="#">Company</a>
         </nav>
         <div class="login-container">
             <div class="form">
                 <h2>Admin Login</h2>
                 <form action="" method="POST" autocomplete="off">
                     <div class="form-details">
                         <label for="email">Email</label>
                         <input type="email" name="email" id="email" required>
                         <label for="password">Password</label>
                         <input type="password" name="password" id="password" required>
                         <a href="#" style="text-align: right;">Forgot your password?</a>
                         <button type="submit" name="submit">Log In</button>
                     </div>
                 </form>
             </div>
         </div>
     </div>

 </body>

 </html>