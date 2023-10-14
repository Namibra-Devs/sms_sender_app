<?php
session_start();
//IMPORT THE DB CONN AND auxiliaries.phpS
require_once "./includes/conn.php";
require_once "./includes/auxiliaries.php";
$alert = "";

if (!isset($_SESSION['user'])) {
    // User is not authenticated, redirect to login page
    header("location: login.php");
    exit();
}


if (isset($_GET['id'])) {
    // Retrieve the IDs from the query string as an array
    $id = $_GET['id'];

    // Loop through the IDs and delete the corresponding records
    $sql = "DELETE FROM admin WHERE id = :id";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$stmt->execute()) {
        // Handle the error if the delete operation fails
        echo "Error deleting record with ID: $id";
    } else {
        // Redirect to the index page after deleting
        header("location: admins.php");
    }
} else {
    header("location: index.php");
}
