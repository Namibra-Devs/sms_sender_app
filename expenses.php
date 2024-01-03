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
$Admin = new Admin($conn, "expense");
$ApprovedAdmin = new Admin($conn, "admin");

$r = $ApprovedAdmin->readAll('id');


if (isset($_POST['range-date-submit'])) {
    $range = 1;
    $start_date = $_POST['start-date'];
    $end_date = $_POST['end-date'];
    $sql = "SELECT expense.*, admin.name FROM expense
        INNER JOIN admin ON admin.id = expense.approved_by
        WHERE expense.date BETWEEN :start_date AND :end_date";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
} else {
    $range = 0;
    $sql = "SELECT expense.*, admin.name
        FROM expense
        INNER JOIN admin ON admin.id = expense.approved_by";
    $stmt = $conn->prepare($sql);
}


$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($range) {
    $sql = "SELECT SUM(amount) AS total_amount FROM expense 
        WHERE date >= '$start_date' AND date <= '$end_date'";
} else {
    $sql = "SELECT SUM(amount) AS total_amount FROM expense";
}
$stmt = $conn->prepare($sql);
$stmt->execute();
$totalAmount = $stmt->fetch(PDO::FETCH_ASSOC)['total_amount'];

if (isset($_POST['submitSponsor'])) {
    $purpose = $_POST['purpose'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $approved_by = $_POST['approved_by'];

    if (!empty($purpose && $amount && $approved_by)) {

        $data = [
            'purpose' => $purpose,
            'amount' => $amount,
            'date' => $date,
            'approved_by' => $approved_by,
        ];
        if ($Admin->create($data)) {
            header('location: expenses.php');
            $alert = "showAlert('success', 'New Expenses Created Successfully')";
        } else {
            $alert = "showAlert('error', 'Couldn't Create Expenses')";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MZDO - Expenses</title>
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
            width: 98%;
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
                    <p>Are you sure want to delete this Expenses?</p>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->
    <div class="container">
        <!--Create Modal -->
        <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Sponsership</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="card-body">
                                <div class="form-group mb-4">
                                    <label for="name">Purpose</label>
                                    <textarea class="form-control" id="purpose" name="purpose" placeholder="Enter the purpose of the amount" required></textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="amount">Amount (GH cedis)*</label>
                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter the amount" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="approved_by">Approved By*</label>
                                    <select name="approved_by" id="approved_by" style="width: 150px; text-align: center;" required>
                                        <?php foreach ($r as $aproveadmin) { ?>
                                            <option value="<?php echo $aproveadmin['id'] ?>" selected><?php echo $aproveadmin['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="date">Date*</label>
                                    <input type="date" class="form-control" id="date" name="date" placeholder="Choose Date" required>
                                </div>





                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <input type="submit" value="submit" id="submitButton" name="submitSponsor" class="btn btn-success">
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


        <button class="m-3 btn btn-success " data-bs-toggle="modal" data-bs-target="#exampleModal">New Expenses</button>

        <!-- <a class="btn btn-primary m-3" href="./edit.php?id=<?php echo 1; ?>">
            Add Admin
        </a> -->
        <div class="section mx-auto">
            <h1 class="text-center">Expenses</h1>
            <div class="container">
                <div class="mb-4">
                    <form action="" method="POST" class="form-row" style="display: flex; justify-content: space-evenly">
                        <div class="form-group col-md-3">
                            <label for="start-date">Start Date</label>
                            <input type="date" class="form-control" id="start-date" value="<?php if (isset($_POST['start-date'])) {
                                                                                                echo $_POST['start-date'];
                                                                                            } ?>" name="start-date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="end-date">End Date</label>
                            <input type="date" class="form-control" id="end-date" value="<?php if (isset($_POST['end-date'])) {
                                                                                                echo $_POST['end-date'];
                                                                                            } ?>" name="end-date">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" name="range-date-submit" class="mt-4 btn btn-primary">Submit</button>
                        </div>




                    </form>
                </div>
                <div class="section mx-auto">
                    <h1 class="text-center">Expenses details</h1>

                    <table id="dataTable1" class="table table-bordered table-striped display">
                        <h4 class="text-center text-primary">Project Earnings</h4>
                        <div>

                            <tbody>

                                <tr>
                                    <th><?php echo 'Overall Total Expenses'; ?></td>
                                    <th width="50%"><?php echo $totalAmount; ?></td>

                                </tr>

                            </tbody>
                        </div>


                    </table>

                    <table id="dataTable" class="table table-bordered table-striped display">
                        <thead>
                            <tr>
                                <th>Purpose</th>
                                <th>Amount</th>
                                <th>Approved By</th>
                                <th>Date</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($results as $result) { ?>
                                <tr>
                                    <td><?php echo $result['purpose']; ?></td>
                                    <td><?php echo $result['amount']; ?></td>
                                    <td><?php echo $result['name']; ?></td>
                                    <td><?php echo $result['date']; ?></td>
                                    <td>
                                        <ul style="display: flex; justify-content: space-evenly" class="m-0">

                                            <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete" data-href="./delete.php?table=expense&id=<?php echo $result['id']; ?>">
                                                Delete
                                            </a>
                                        </ul>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

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