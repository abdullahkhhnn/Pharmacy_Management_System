<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include("../db/db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete customer
    $sql = "DELETE FROM customers WHERE ID = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Customer deleted successfully";
        header("Location: view_customers.php"); // Redirect to customer list page
    } else {
        echo "Error deleting customer: " . $conn->error;
    }
} else {
    echo "Customer ID not provided.";
    exit();
}
?>
