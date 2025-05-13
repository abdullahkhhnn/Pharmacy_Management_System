<?php
require 'auth_admin.php';
require 'db.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM medicines WHERE ID = $id");
    header("Location: medicines.php");
    exit();
}

// Get supplier list
$supplierList = mysqli_query($conn, "SELECT ID, NAME FROM suppliers");
if (!$supplierList) {
    die("Error fetching suppliers: " . mysqli_error($conn));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NAME = mysqli_real_escape_string($conn, $_POST['NAME'] ?? '');
    $GENERIC_NAME = mysqli_real_escape_string($conn, $_POST['GENERIC_NAME'] ?? '');
    $MANUFACTURER_NAME = mysqli_real_escape_string($conn, $_POST['MANUFACTURER_NAME'] ?? '');
    $SUPPLIER_ID = intval($_POST['SUPPLIER_ID'] ?? 0);
    $EXPIRY_DATE = mysqli_real_escape_string($conn, $_POST['EXPIRY_DATE'] ?? '');
    $QUANTITY = intval($_POST['QUANTITY'] ?? 0);
    $PRICE = floatval($_POST['RATE'] ?? 0);

    // Insert medicine data into the medicines table
    $sql = "INSERT INTO medicines (NAME, GENERIC_NAME, MANUFACTURER_NAME, SUPPLIER_ID, price)
            VALUES ('$NAME', '$GENERIC_NAME', '$MANUFACTURER_NAME', $SUPPLIER_ID, $PRICE)";
    if (mysqli_query($conn, $sql)) {
        $medicine_id = mysqli_insert_id($conn); // Get the last inserted medicine ID

        // Insert stock data into the medicines_stock table
        $stock_sql = "INSERT INTO medicines_stock (medicine_id, quantity, expiry_date)
                      VALUES ($medicine_id, $QUANTITY, '$EXPIRY_DATE')";
        if (!mysqli_query($conn, $stock_sql)) {
            die("Error inserting into medicines_stock: " . mysqli_error($conn));
        }
    } else {
        die("Error inserting into medicines: " . mysqli_error($conn));
    }

    header("Location: medicines.php");
    exit();
}

// Fetch all medicine data from the medicines and medicines_stock tables
$medicines = mysqli_query($conn, "
    SELECT M.ID AS MEDICINE_ID, M.NAME AS MEDICINE_NAME, M.GENERIC_NAME, M.MANUFACTURER_NAME,
    S.NAME AS SUPPLIER_NAME, MS.EXPIRY_DATE, MS.QUANTITY, M.price AS RATE FROM medicines M
    LEFT JOIN suppliers S ON M.SUPPLIER_ID = S.ID
    LEFT JOIN medicines_stock MS ON M.ID = MS.medicine_id
    ORDER BY M.ID DESC
");
if (!$medicines) {
    die("Error fetching medicines: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medicines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Add New Medicine</h2>

    <a href="admin_dashboard.php" class="btn btn-primary mb-3">&larr; Back to Main Menu</a>

    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" class="form-control" name="NAME" placeholder="Name" required>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" name="GENERIC_NAME" placeholder="Generic Name">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" name="MANUFACTURER_NAME" placeholder="Manufacturer">
        </div>
        <div class="col-md-3">
            <select class="form-select" name="SUPPLIER_ID" required>
                <option value="">Select Supplier</option>
                <?php while ($supplier = mysqli_fetch_assoc($supplierList)): ?>
                    <option value="<?= htmlspecialchars($supplier['ID']) ?>">
                        <?= htmlspecialchars($supplier['NAME']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control" name="EXPIRY_DATE" required>
        </div>
        <div class="col-md-3">
            <input type="number" class="form-control" name="QUANTITY" placeholder="Quantity" required>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" class="form-control" name="RATE" placeholder="Rate" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Add</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Generic</th>
                <th>Manufacturer</th>
                <th>Supplier</th>
                <th>Expiry</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($m = mysqli_fetch_assoc($medicines)): ?>
            <tr>
                <td><?= htmlspecialchars($m['MEDICINE_NAME']) ?></td>
                <td><?= htmlspecialchars($m['GENERIC_NAME']) ?></td>
                <td><?= htmlspecialchars($m['MANUFACTURER_NAME']) ?></td>
                <td><?= htmlspecialchars($m['SUPPLIER_NAME']) ?></td>
                <td><?= htmlspecialchars($m['EXPIRY_DATE']) ?></td>
                <td><?= htmlspecialchars($m['QUANTITY']) ?></td>
                <td><?= htmlspecialchars($m['RATE']) ?></td>
                <td>
                    <a href="medicine_update.php?edit=<?= $m['MEDICINE_ID'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?delete=<?= $m['MEDICINE_ID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this medicine?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>