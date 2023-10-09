<?php
session_start();
//IMPORT THE DB CONN AND auxiliaries.phpS
require_once "./includes/conn.php";
require_once "./includes/auxiliaries.php";
$alert = "";

$Admin = new Admin($conn, "message");
$results = $Admin->readAll();


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
            width: 70%;
            border: 1px solid grey;
            /* display: flex; */
            /* flex-direction: column; */
            /* flex-wrap: wrap; */
            /* justify-content: center; */
            /* align-items: center; */
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


        .card {
            background-color: #fff;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            width: 300px;
            margin-bottom: 20px;
        }

        .card p {
            margin: 0;
            font-weight: bold;
        }
    </style>
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
        <div class="section mx-auto">
            <h1 class="text-center">Sent Messages</h1>
            <table id="dataTable" class="table table-bordered table-striped display">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row) { ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['date']; ?></td>
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
    </script>
</body>

</html>


</body>

</html>