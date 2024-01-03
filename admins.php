<?php
session_start();
//IMPORT THE DB CONN AND auxiliaries.phpS
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

$results = $Admin->readAll('id');

if (isset($_POST['submitCreateAdmin'])) {
    $name = $_POST['adminname'];
    $email = $_POST['adminemail'];
    $zone = $_POST['adminzone'];
    $isadmin = $_POST['isadmin'];
    $password = password_hash($_POST['confirmadminpassword'], PASSWORD_DEFAULT);
    // print_r($isadmin);
    // exit;
    if (!empty($name && $email && $password)) {

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'zone' => $zone,
            'isAdmin' => $isadmin,
        ];
        if ($Admin->create($data)) {
            header('location: admins.php');
            $alert = "showAlert('success', 'New Supervisor Created Successfully')";
        } else {
            $alert = "showAlert('error', 'Couldn't Create Supervisor')";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MZDO - Admins</title>
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
            width: 85%;
            border: 1px solid grey;

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
        <a href="./index.php" class="px-2">
            <button>
                Back
            </button>
        </a>



    </nav>

    <!-- modals start -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" style="margin: 0;" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this Supervisor?</p>
                    <p class="text-danger">All funds records related to this Supervisor will also be deleted</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->
    <div class="container ">
        <!--Create Modal -->
        <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Supervisor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="card-body">
                                <div class="form-group mb-4">
                                    <label for="adminname">Admin Name*</label>
                                    <input type="text" class="form-control" id="adminname" name="adminname" placeholder="Enter the name of new supervisor" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="adminemail">Admin Email*</label>
                                    <input type="email" class="form-control" id="adminemail" name="adminemail" placeholder="Enter the email of new supervisor" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="adminzone">Admin Zone*</label>
                                    <input type="text" class="form-control" id="adminzone" name="adminzone" placeholder="Enter the Zone name of new supervisor" required>
                                </div>


                                <div class="form-group mb-4">
                                    <label for="isadmin">Make Super Admin*</label>
                                    <select name="isadmin" id="isadmin" style="width: 150px; text-align: center;" required>
                                        <option value="0" selected>No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="adminpassword">New Admin Password*</label>
                                    <input type="text" class="form-control" id="adminpassword" name="adminpassword" placeholder="Set the password of new supervisor" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="confirmadminpassword">Confirm New Admin Password*</label>
                                    <input type="text" class="form-control" id="confirmadminpassword" name="confirmadminpassword" placeholder="Confirm password of new supervisor" required>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <input type="submit" value="submit" id="submitButton" name="submitCreateAdmin" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- end modal -->


        <button class="m-3 btn btn-success " data-bs-toggle="modal" data-bs-target="#exampleModal">New Supervisor</button>

        <!-- <a class="btn btn-primary m-3" href="./edit.php?id=<?php echo 1; ?>">
            Add Admin
        </a> -->
        <div class="section mx-auto">
            <h1 class="text-center">Registered Admins</h1>

            <table id="dataTable" class="table table-bordered table-striped display">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Zone</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result) { ?>
                        <tr>
                            <td><?php echo $result['name']; ?></td>
                            <td><?php echo $result['email']; ?></td>
                            <td><?php echo $result['zone']; ?></td>
                            <td><?php echo $result['isAdmin'] ? "Admin" : "Supervisor"; ?></td>
                            <td>
                                <ul style="display: flex; justify-content: space-evenly" class="m-0">

                                    <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete" data-href="./delete.php?table=admin&id=<?php echo $result['id']; ?>">
                                        Delete
                                    </a>
                                </ul>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
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
    </script>
    <script>
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({});
        });
    </script>
</body>

</html>