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
$adminName = $_SESSION['name'];
$adminZone = $_SESSION['zone'];
$Admin = new Admin($conn, "admin");
$isAdmin = $Admin->read('id', $id)[0]['isAdmin'];

if (isset($_POST['range-date-submit'])) {
    $range = 1;
    $start_date = $_POST['start-date'];
    $end_date = $_POST['end-date'];
} else {
    $range = 0;
}

//QUERY FOR OVERALL TOTAL AMOUNT
if ($range) {
    $sql = "SELECT SUM(amount) AS total_amount FROM message 
        WHERE date >= '$start_date' AND date <= '$end_date'";
} else {
    $sql = "SELECT SUM(amount) AS total_amount FROM message";
}
$stmt = $conn->prepare($sql);
$stmt->execute();
$totalAmount = $stmt->fetch(PDO::FETCH_ASSOC)['total_amount'];

//QUERY FOR INIVIDUAL AMOUNT
$fetchDetails = new Admin($conn, "message");
if ($isAdmin) {
    if ($range) {
        $query = "
    SELECT 
        a.zone AS zone,
        a.name AS name,
        SUM(m.amount) AS amount
    FROM admin a
    JOIN message m ON a.id = m.supervisor
    WHERE date >= '$start_date' AND date <= '$end_date' GROUP BY a.zone, a.name
";
    } else {
        $query = "
    SELECT 
        a.zone AS zone,
        a.name AS name,
        SUM(m.amount) AS amount
    FROM admin a
    JOIN message m ON a.id = m.supervisor
    GROUP BY a.zone, a.name
";
    }
} else {
    if ($range) {
    } else {
        $query = "
    SELECT 
        a.zone AS zone,
        a.name AS name,
        SUM(m.amount) AS amount
    FROM admin a
    JOIN message m ON a.id = m.supervisor
    WHERE a.id = $id AND date >= '$start_date' AND date <= '$end_date'
    GROUP BY a.zone, a.name
";
    }
    $query = "
    SELECT 
        a.zone AS zone,
        a.name AS name,
        SUM(m.amount) AS amount
    FROM admin a
    JOIN message m ON a.id = m.supervisor WHERE a.id= $id GROUP BY a.zone, a.name
";
}
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($results);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MZDO - Reports</title>
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
            width: 70%;
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
    </div>
    <div class="container ">
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
            <h1 class="text-center">Analytics</h1>

            <table id="dataTable1" class="table table-bordered table-striped display">
                <h4 class="text-center text-primary">Project Earnings</h4>
                <div>
                    <thead>
                        <tr>
                            <th>Community</th>
                            <th> Overall Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td><?php echo 'Zongo Community'; ?></td>
                            <td><?php echo $totalAmount; ?></td>

                        </tr>

                    </tbody>
                </div>


            </table>
            <table id="dataTable<?php echo $isAdmin  ? '' : 1 ?>" class="table table-bordered table-striped display">
                <h4 class="text-center text-primary">Zone Earnings</h4>

                <thead>
                    <tr>
                        <th>Zone</th>
                        <th>Supervisor</th>
                        <th>Total Zone Amount</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($results as $row) { ?>
                        <tr>

                            <td><?php echo $row['zone']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                        </tr>

                    <?php } ?>

                </tbody>



            </table>



        </div>
    </div>
    <script>
        var endDate = document.getElementById('end-date');
        var startDate = document.getElementById('start-date');
        var clearbtn = document.getElementById('clear-range');

        var ClearRange = function() {
            console.log('clear')
            startDate.value = '';
            endDate.value = '';

            location.reload()
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({});
        });
    </script>
</body>

</html>