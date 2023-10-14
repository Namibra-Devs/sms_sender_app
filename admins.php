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

$fetchAdmin = new Admin($conn, "admin");
$results = $fetchAdmin->readAll('id');


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
                    <p>Are you sure want to delete this Farmer?</p>
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
                                <ul style="display: flex" class="m-0">
                                    <a class="nav-link btn btn-warning text-light mr-2" href="./edit.php?id=<?php echo $result['id']; ?>">
                                        Edit
                                    </a>
                                    <a href="#" class="nav-link btn btn-danger text-light" data-toggle="modal" data-target="#confirm-delete" data-href="./delete.php?id=<?php echo $result['id']; ?>">
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

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({});
        });

        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
    </script>
</body>

</html>


</body>

</html>