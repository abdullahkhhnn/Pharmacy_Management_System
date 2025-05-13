<?php
require 'auth_admin.php';
require 'db.php';

// Initialize empty variables
$edit_id = '';
$name = '';
$contact = '';
$email = '';
$address = '';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Ensure $id is a valid number before proceeding with the deletion
    if ($id > 0) {
        // Using prepared statement to prevent SQL injection
        $stmt = $conn->prepare("DELETE FROM suppliers WHERE ID = ?");
        $stmt->bind_param("i", $id); // "i" indicates the type (integer)
        $stmt->execute();
        $stmt->close();
    }

    header("Location: suppliers.php");
    exit();
}

// Handle form submission for add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Debugging
    var_dump($_POST); // Check if contact is coming through

    if (!empty($_POST['edit_id'])) {
        $edit_id = intval($_POST['edit_id']);
        $stmt = $conn->prepare("UPDATE suppliers SET name=?, contact_number=?, email=?, address=? WHERE ID=?");
        $stmt->bind_param("ssssi", $name, $contact, $email, $address, $edit_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO suppliers (name, contact_number, email, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $contact, $email, $address);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: suppliers.php");
    exit();
}

// Fetch supplier data for editing
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM suppliers WHERE ID = $edit_id");
    if ($row = mysqli_fetch_assoc($result)) {
        $name = $row['NAME'];
        $contact = $row['CONTACT_NUMBER'];
        $email = $row['EMAIL'];
        $address = $row['ADDRESS'];
    }
}

// Fetch all suppliers
$result = mysqli_query($conn, "SELECT * FROM suppliers");
$suppliers = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Suppliers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Supplier Management</h2>

    <a href="admin_dashboard.php" class="btn btn-primary mb-3">&larr; Back to Main Menu</a>

    <!-- Supplier Form -->
    <form method="POST" class="row g-3 mb-4">
        <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit_id) ?>">
        <div class="col-md-3">
            <input type="text" class="form-control" name="name" placeholder="Supplier Name" required value="<?= htmlspecialchars($name) ?>">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" name="contact" placeholder="Contact" required value="<?= htmlspecialchars($contact) ?>">
        </div>
        <div class="col-md-3">
            <input type="email" class="form-control" name="email" placeholder="Email" required value="<?= htmlspecialchars($email) ?>">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" name="address" placeholder="Address" required value="<?= htmlspecialchars($address) ?>">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>

    <!-- Supplier Table -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($suppliers as $supplier): ?>
            <tr>
                <td><?= $supplier['ID'] ?></td>
                <td><?= htmlspecialchars($supplier['NAME']) ?></td>
                <td><?= htmlspecialchars($supplier['CONTACT_NUMBER']) ?></td>
                <td><?= htmlspecialchars($supplier['EMAIL']) ?></td>
                <td><?= htmlspecialchars($supplier['ADDRESS']) ?></td>
                <td>
                    <a href="?edit=<?= $supplier['ID'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?delete=<?= $supplier['ID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
