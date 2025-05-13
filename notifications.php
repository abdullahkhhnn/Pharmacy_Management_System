<?php
require 'auth_admin.php';
require 'db.php';

// Dates
$today = date('Y-m-d');
$three_months_later = date('Y-m-d', strtotime('+3 months'));

// Fetch expiry alerts
$expiryStmt = $conn->prepare("
    SELECT m.name, s.expiry_date
    FROM medicines_stock s
    JOIN medicines m ON s.medicine_id = m.id
    WHERE s.expiry_date <= ?
    ORDER BY s.expiry_date ASC
");
$expiryStmt->bind_param("s", $three_months_later);
$expiryStmt->execute();
$expiryResult = $expiryStmt->get_result();

// Fetch quantity alerts
$quantityResult = $conn->query("
    SELECT m.name, s.quantity
    FROM medicines_stock s
    JOIN medicines m ON s.medicine_id = m.id
    WHERE s.quantity < 10
    ORDER BY s.quantity ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicine Alerts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Medicine Notifications</h2>
    <a href="admin_dashboard.php" class="btn btn-primary mb-4">&larr; Back to Dashboard</a>

    <!-- Expiry Alert Table -->
    <div class="mb-5">
        <h4 class="text-danger">Expiry Alert: Medicines Expiring in Less than 3 Months</h4>
        <table class="table table-bordered">
            <thead class="table-danger">
                <tr>
                    <th>Medicine Name</th>
                    <th>Expiry Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($expiryResult->num_rows === 0): ?>
                    <tr><td colspan="2" class="text-center">No expiry alerts found.</td></tr>
                <?php else: ?>
                    <?php while ($row = $expiryResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['expiry_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Quantity Alert Table -->
    <div>
        <h4 class="text-warning">Quantity Alert: Medicines with Less than 10 Units</h4>
        <table class="table table-bordered">
            <thead class="table-warning">
                <tr>
                    <th>Medicine Name</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($quantityResult->num_rows === 0): ?>
                    <tr><td colspan="2" class="text-center">No quantity alerts found.</td></tr>
                <?php else: ?>
                    <?php while ($row = $quantityResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
