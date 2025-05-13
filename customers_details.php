<?php
require 'auth_admin.php';
require 'db.php';

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: customers_details.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name && $email && $phone && $address && $password) {
        // Insert customer data without hashing the password
        $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $address, $password);
        $stmt->execute();
        header("Location: customers_details.php");
        exit();
    } 
    else {
        $error = "Please fill out all fields.";
    }
}

// Fetch all customers
$result = mysqli_query($conn, "SELECT id, name, email, phone, address, password FROM customers");
$customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Customer Management</h2>

    <a href="admin_dashboard.php" class="btn btn-primary mb-3">&larr; Back to Main Menu</a>

    <!-- Add Customer Form -->
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-2">
            <input type="text" class="form-control" name="name" placeholder="Name" required>
        </div>
        <div class="col-md-2">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" name="phone" placeholder="Phone" required>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" name="address" placeholder="Address" required>
        </div>
        <div class="col-md-2">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success">Add</button>
        </div>
        <?php if (!empty($error)): ?>
            <div class="col-12 text-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </form>

    <!-- Customer Table -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($customers)): ?>
                <tr><td colspan="6" class="text-center">No customers found.</td></tr>
            <?php else: ?>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= htmlspecialchars($customer['name']) ?></td>
                        <td><?= htmlspecialchars($customer['email']) ?></td>
                        <td><?= htmlspecialchars($customer['phone']) ?></td>
                        <td><?= htmlspecialchars($customer['address']) ?></td>
                        <td><?= htmlspecialchars($customer['password']) ?></td>
                        <td>
                            <a href="customer_update.php?edit=<?= $customer['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="?delete=<?= $customer['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>