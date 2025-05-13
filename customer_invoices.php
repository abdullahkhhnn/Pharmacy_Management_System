<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'db.php'; // DB connection file

// Check if customer is logged in
if (!isset($_SESSION['customer_email'])) {
    header("Location: customer_login.php");
    exit();
}

$customer_email = $_SESSION['customer_email'];

// Fetch customer's invoices
$query = "
    SELECT IM.invoice_number, IM.medicine_name, IM.medicine_price, IM.quantity, IM.total_price
    FROM invoice_medicines IM
    WHERE IM.customer_email = ?
    ORDER BY IM.invoice_number DESC, IM.invoice_id ASC
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $customer_email);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
if (!$result) {
    die("Error fetching result: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Your Invoices</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">You have no invoices.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Invoice No.</th>
                <th>Medicine</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['invoice_number']) ?></td>
                    <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                    <td><?= number_format($row['medicine_price'], 2) ?></td>
                    <td><?= (int)$row['quantity'] ?></td>
                    <td><?= number_format($row['total_price'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="customer_dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
