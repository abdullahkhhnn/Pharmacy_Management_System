<?php
require 'auth_admin.php';
require 'db.php';

$editCustomer = null;

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    if ($id > 0) {
        $stmt = $conn->prepare("SELECT * FROM customers WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $editCustomer = $result->fetch_assoc();
        if (!$editCustomer) {
            echo 'Customer not found.';
        }
    } else {
        echo 'Invalid customer ID.';
    }
} else {
    echo 'No customer ID provided.';
}

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $password = $_POST['password'];
    $id       = $_POST['edit_id'];

    // Ensure all fields have data
    if ($id && $name && $email && $phone && $address) {
        if (!empty($password)) {
            // Update with password
            $stmt = $conn->prepare("UPDATE customers SET name=?, email=?, phone=?, address=?, password=? WHERE id=?");
            $stmt->bind_param("sssssi", $name, $email, $phone, $address, $password, $id);
        } else {
            // Update without password
            $stmt = $conn->prepare("UPDATE customers SET name=?, email=?, phone=?, address=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);
        }

        if ($stmt->execute()) {
            header("Location: customers_details.php");  // Redirect after update
            exit();
        } else {
            echo 'Error updating customer: ' . $stmt->error;
        }
    } else {
        echo 'Please fill out all fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Edit Customer</h2>

    <?php if ($editCustomer): ?>
        <!-- Form for Editing Customer -->
        <form method="POST" class="row g-3 mb-4">
            <input type="hidden" name="edit_id" value="<?= htmlspecialchars($editCustomer['ID']) ?>">
            <div class="col-md-3">
                <input type="text" class="form-control" name="name" placeholder="Name" required
                       value="<?= htmlspecialchars($editCustomer['name'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required
                       value="<?= htmlspecialchars($editCustomer['email'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="phone" placeholder="Phone" required
                       value="<?= htmlspecialchars($editCustomer['phone'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="address" placeholder="Address" required
                       value="<?= htmlspecialchars($editCustomer['address'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <input type="password" class="form-control" name="password" placeholder="New Password (optional)">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    <?php else: ?>
        <p>Customer not found.</p>
    <?php endif; ?>

    <!-- Back to Customers -->
    <a href="customers_details.php" class="btn btn-secondary">Back to Customers</a>
</div>
</body>
</html>