<?php
require 'db.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['customer_email'])) {
    header("Location: customer_login.php");
    exit();
}

$search = "";
$medicines = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search = isset($_GET['search']) ? $_GET['search'] : "";

    // Fetch medicines and stock details from the medicines and medicines_stock tables
    $sql = "
        SELECT M.id, M.name, M.generic_name, M.manufacturer_name, MS.quantity, M.price, MS.expiry_date 
        FROM medicines M
        INNER JOIN medicines_stock MS ON M.id = MS.medicine_id
        WHERE MS.quantity > 0 AND M.name LIKE ?
    ";

    $stmt = $conn->prepare($sql);
    $likeSearch = "%" . $search . "%";
    $stmt->bind_param("s", $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $medicines[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="global.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
        }
        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }
        .sidebar h3 {
            margin-top: 0;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin-top: 15px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 300px;
        }
        button {
            padding: 8px 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>Dashboard</h3>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['customer_email']); ?></p>
    <a href="customer_invoices.php" class="btn btn-info">View My Invoices</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <form method="get" class="search-bar">
        <input type="text" name="search" placeholder="Search medicines..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Generic Name</th>
                <th>Manufacturer</th>
                <th>Available Quantity</th>
                <th>Price</th>
                <th>Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($medicines)): ?>
                <tr><td colspan="6">No medicines found.</td></tr>
            <?php else: ?>
                <?php foreach ($medicines as $med): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($med['name']); ?></td>
                        <td><?php echo htmlspecialchars($med['generic_name']); ?></td>
                        <td><?php echo htmlspecialchars($med['manufacturer_name']); ?></td>
                        <td><?php echo $med['quantity']; ?></td>
                        <td><?php echo number_format($med['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($med['expiry_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>