<?php 
require 'auth_admin.php';
require 'db.php';

// Fetch the medicine details if the page is in edit mode
$medicine = [
    'ID' => '',
    'NAME' => '',
    'GENERIC_NAME' => '',
    'MANUFACTURER_NAME' => '',
    'SUPPLIER_ID' => '',
    'price' => ''
];
$stock = [
    'quantity' => '',
    'expiry_date' => ''
];

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    
    // Fetch medicine details from the medicines table
    $result = mysqli_query($conn, "SELECT * FROM medicines WHERE ID = $id");
    if ($row = mysqli_fetch_assoc($result)) {
        $medicine = $row;
    } else {
        die("Medicine not found.");
    }

    // Fetch stock details from the medicines_stock table
    $stockResult = mysqli_query($conn, "SELECT * FROM medicines_stock WHERE medicine_id = $id");
    if ($stockRow = mysqli_fetch_assoc($stockResult)) {
        $stock = $stockRow;
    } else {
        die("Stock details not found.");
    }
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $generic_name = mysqli_real_escape_string($conn, $_POST['generic_name'] ?? '');
    $manufacturer_name = mysqli_real_escape_string($conn, $_POST['manufacturer_name'] ?? '');
    $supplier_id = intval($_POST['supplier_id'] ?? 0);
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date'] ?? '');
    $quantity = intval($_POST['quantity'] ?? 0);
    $price = floatval($_POST['rate'] ?? 0);

    // Update the medicines table
    $medicineQuery = "UPDATE medicines SET 
                      NAME='$name', 
                      GENERIC_NAME='$generic_name', 
                      MANUFACTURER_NAME='$manufacturer_name', 
                      SUPPLIER_ID=$supplier_id, 
                      price=$price 
                      WHERE ID=$id";

    if (!mysqli_query($conn, $medicineQuery)) {
        die("Error updating medicine: " . mysqli_error($conn));
    }

    // Update the medicines_stock table
    $stockQuery = "UPDATE medicines_stock SET 
                   quantity=$quantity, 
                   expiry_date='$expiry_date' 
                   WHERE medicine_id=$id";

    if (!mysqli_query($conn, $stockQuery)) {
        die("Error updating stock: " . mysqli_error($conn));
    }

    header("Location: medicines.php");
    exit();
}

// Get suppliers for the dropdown
$supplierList = mysqli_query($conn, "SELECT ID, NAME FROM suppliers");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Update Medicine</h2>

    <form method="POST" class="row g-3 mb-4">
        <input type="hidden" name="id" value="<?= htmlspecialchars($medicine['ID']) ?>">

        <div class="col-md-3">
            <input type="text" class="form-control" name="name" placeholder="Name" required value="<?= htmlspecialchars($medicine['NAME']) ?>">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" name="generic_name" placeholder="Generic Name" value="<?= htmlspecialchars($medicine['GENERIC_NAME']) ?>">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" name="manufacturer_name" placeholder="Manufacturer" value="<?= htmlspecialchars($medicine['MANUFACTURER_NAME']) ?>">
        </div>
        <div class="col-md-3">
            <select class="form-select" name="supplier_id" required>
                <option value="">Select Supplier</option>
                <?php while ($supplier = mysqli_fetch_assoc($supplierList)): ?>
                    <option value="<?= $supplier['ID'] ?>" <?= $supplier['ID'] == $medicine['SUPPLIER_ID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($supplier['NAME']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control" name="expiry_date" placeholder="Expiry Date" value="<?= htmlspecialchars($stock['expiry_date']) ?>" required>
        </div>
        <div class="col-md-3">
            <input type="number" class="form-control" name="quantity" placeholder="Quantity" value="<?= htmlspecialchars($stock['quantity']) ?>" required>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" class="form-control" name="rate" placeholder="Rate" value="<?= htmlspecialchars($medicine['price']) ?>" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Update Medicine</button>
        </div>
    </form>
</div>
</body>
</html>