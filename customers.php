<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
include("../db/db_connect.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Management</title>
    <link rel="stylesheet" href="../css/global.css"> <!-- Your custom styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="d-flex">
    <?php include("admin_sidebar.php"); ?>

    <div class="content w-100">
        <h2>Customer Management</h2>
        <a href="add_customer.php" class="btn btn-success mb-3">Add New Customer</a>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM customers";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['ID']}</td>
                            <td>{$row['NAME']}</td>
                            <td>{$row['CONTACT_NUMBER']}</td>
                            <td>{$row['ADDRESS']}</td>
                            <td>
                                <a href='edit_customer.php?id={$row['ID']}' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='delete_customer.php?id={$row['ID']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure?')\">Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
