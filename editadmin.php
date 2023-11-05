<?php
session_start();
//IMPORT THE DB CONN AND auxiliaries.phpS
require_once "./includes/conn.php";
require_once "./includes/auxiliaries.php";
$alert = "";

// AUTHENTICATION
if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
    header("location: ./login.php");
    exit();
}

$AdminID = isset($_SESSION['user']);
$id = $_GET['id'];
$Admin = new Admin($conn, "admin");
$row = $Admin->read('id', $id)[0];

if (isset($_POST['submitEditAdmin'])) {
    $name = $_POST['Eadminname'];
    $email = $_POST['Eadminemail'];
    $zone = $_POST['Eadminzone'];
    $isadmin = $_POST['Eisadmin'];
    $password = $_POST['Eadminpassword'];

    if ($password == '') {
        $hashedPassword = $row['password'];
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }
    // print_r($isadmin);
    // exit;
    if (!empty($name) && !empty($email) && !empty($hashedPassword)) {
        echo $name, $email, $zone, $isadmin, $password;


        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'zone' => $zone,
            'isAdmin' => $isadmin,
        ];
        if ($Admin->update($id, $data)) {
            header('location: admins.php');
            $alert = "showAlert('success', 'New Supervisor Details Updated Successfully')";
        } else {
            $alert = "showAlert('error', 'Couldn't Update Supervisor')";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MZDO - Edit Admin </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/style.js"> </script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <style>
        .section {
            background-color: #f6f6f6;
            padding: 20px;
            width: 55%;
            border: 1px solid grey;
            margin-bottom: 40px;
        }


        button {
            background-color: #454545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            margin-left: 10px;
        }
    </style>

</head>

<body>
    <?php
    echo "<script>";
    echo $alert;
    echo "</script>";
    ?>
    <nav class="navbar mb-4">
        <a href="./admins.php" class="px-2">
            <button>
                Back
            </button>
        </a>



    </nav>

    <div class="section section mx-auto">

        <div class="wrapper">
            <div class="message-container">
                <form action="" method="POST">
                    <h1 class="text-center">Edit Admins</h1>

                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label for="adminname">Admin Name*</label>
                            <input type="text" class="form-control" id="adminname" name="Eadminname" placeholder="Enter the name of new supervisor" value="<?php echo $row['name'] ?>" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="adminemail">Admin Email*</label>
                            <input type="email" class="form-control" id="adminemail" name="Eadminemail" placeholder="Enter the email of new supervisor" value="<?php echo $row['email'] ?>" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="adminzone">Admin Zone*</label>
                            <input type="text" class="form-control" id="adminzone" name="Eadminzone" placeholder="Enter the Zone name of new supervisor" value="<?php echo $row['zone'] ?>" required>
                        </div>


                        <div class="form-group mb-4">
                            <label for="isadmin">Make Super Admin*</label>
                            <select name="Eisadmin" id="isadmin" style="width: 150px; text-align: center;" required>
                                <option value="0" <?php echo $row['isAdmin'] == 0 ? "selected" : "" ?>> No </option>
                                <option value="1" <?php echo $row['isAdmin'] == 0 ? "" : "selected" ?>>Yes</option>

                            </select>
                        </div>
                        <small style="color: red;"> Leave it empty if you want to maintain previous password</small>
                        <div class="form-group mb-4">
                            <label for="adminpassword">New Admin Password*</label>
                            <input type="text" class="form-control" id="adminpassword" name="Eadminpassword" placeholder="Set the password of new supervisor" value="">
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <input type="submit" value="submit" id="submitButton" name="submitEditAdmin" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>

    </div>


    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- <script>
        // Get references to the input fields and the submit button
        var adminPasswordInput = document.getElementById('adminpassword');
        var confirmAdminPasswordInput = document.getElementById('confirmadminpassword');
        var submitButton = document.getElementById('submitButton');

        // Add an event listener to the input fields for input changes
        adminPasswordInput.addEventListener('input', checkPasswordMatch);
        confirmAdminPasswordInput.addEventListener('input', checkPasswordMatch);

        // Function to check if passwords match and enable/disable the submit button
        function checkPasswordMatch() {
            var adminPassword = adminPasswordInput.value;
            var confirmAdminPassword = confirmAdminPasswordInput.value;

            if (adminPassword === confirmAdminPassword) {
                submitButton.disabled = false; // Disable the submit button
            } else {
                submitButton.disabled = true; // Disable the submit button
            }
        }

        // Initially, disable the submit button
        submitButton.disabled = true;
    </script> -->


    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({});
        });
    </script>
</body>

</html>