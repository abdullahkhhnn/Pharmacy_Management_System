<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include("../db/db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch customer data
    $sql = "SELECT * FROM customers WHERE ID = $id";
    $result = $conn->query($sql);
    $customer = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $contact_number = $_POST['contact_number'];
        $address = $_POST['address'];

        // Update customer data
        $update_sql = "UPDATE customers SET NAME='$name', CONTACT_NUMBER='$contact_number', ADDRESS='$address' WHERE ID=$id";
        if ($conn->query($update_sql) === TRUE) {
            echo "Customer updated successfully";
            header("Location: view_customers.php"); // Redirect to customer list page
        } else {
            echo "Error updating customer: " . $conn->error;
        }
    }
} else {
    echo "Customer ID not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <link rel="stylesheet" href="../css/global.css">
</head>
<body>
    <?php include("admin_sidebar.php"); ?>

    <div class="content">
        <h2>Edit Customer</h2>
        <form method="POST" action="edit_customer.php?id=<?= $customer['ID'] ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="<?= $customer['NAME'] ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" value="<?= $customer['CONTACT_NUMBER'] ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" value="<?= $customer['ADDRESS'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Customer</button>
        </form>
    </div>
</body>
</html>
