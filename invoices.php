<?php
require 'auth_admin.php';
require 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch medicines
$medicines = [];
$result = mysqli_query($conn, "SELECT ID, NAME, price FROM medicines");
while ($row = mysqli_fetch_assoc($result)) {
    $medicines[] = $row;
}

// Fetch customers
$customers = [];
$customerResult = mysqli_query($conn, "SELECT EMAIL, NAME FROM customers");
while ($row = mysqli_fetch_assoc($customerResult)) {
    $customers[] = $row;
}

// Row count (no restrictions)
$row_count = isset($_POST['add_row']) ? (int)$_POST['row_count'] + 1 : 1;

// Handle invoice submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_invoice'])) {
    $customer_email = $_POST['customer_email'];
    $medicines_selected = $_POST['medicines'];
    $quantities = $_POST['quantities'];

    if (!empty($customer_email)) {
        $invoice_number = 'INV-' . time();

        foreach ($medicines_selected as $index => $medicine_id) {
            if (empty($medicine_id)) continue;

            $quantity = (int)$quantities[$index];
            $medicine = array_filter($medicines, fn($med) => $med['ID'] == $medicine_id);
            $medicine = reset($medicine);
            $rate = (float)$medicine['price'];
            $total_price = $quantity * $rate;

            $stmt = $conn->prepare("INSERT INTO invoice_medicines (invoice_number, customer_email, medicine_name, medicine_price, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssddd", $invoice_number, $customer_email, $medicine['NAME'], $rate, $quantity, $total_price);
            $stmt->execute();
        }

        header("Location: invoices.php?success=1");
        exit();
    }
}

// Fetch invoices
$invoice_data = mysqli_query($conn, "
    SELECT IM.invoice_number, C.NAME AS customer_name, IM.medicine_name, IM.medicine_price, IM.quantity, IM.total_price
    FROM invoice_medicines IM
    LEFT JOIN customers C ON IM.customer_email = C.EMAIL
    ORDER BY IM.invoice_number DESC, IM.invoice_id ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Create Invoice</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success">Invoice created successfully!</div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="row_count" value="<?= $row_count ?>">

        <div class="mb-3">
            <label for="customer_email" class="form-label">Customer</label>
            <select name="customer_email" id="customer_email" class="form-control">
                <option value="">Select Customer</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= htmlspecialchars($customer['EMAIL']) ?>"><?= htmlspecialchars($customer['NAME']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Medicine</th>
                <th>Quantity</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < $row_count; $i++): ?>
                <tr>
                    <td>
                        <select name="medicines[]" class="form-control">
                            <option value="">Select</option>
                            <?php foreach ($medicines as $med): ?>
                                <option value="<?= htmlspecialchars($med['ID']) ?>"><?= htmlspecialchars($med['NAME']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="number" name="quantities[]" class="form-control" min="1"></td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>

        <div class="d-flex gap-2">
            <button type="submit" name="add_row" class="btn btn-secondary">Add Row</button>
            <button type="submit" name="submit_invoice" class="btn btn-success">Submit Invoice</button>
            <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </form>

    <hr>

    <h3>All Invoices</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Invoice No.</th>
            <th>Customer</th>
            <th>Medicine</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($invoice_data)): ?>
            <tr>
                <td><?= htmlspecialchars($row['invoice_number']) ?></td>
                <td><?= htmlspecialchars($row['customer_name'] ?? 'Unknown') ?></td>
                <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                <td><?= number_format($row['medicine_price'], 2) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= number_format($row['total_price'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
