<?php
require 'auth_admin.php';
require 'db.php';

$admin_username = $_SESSION['admin'];
$query = mysqli_query($conn, "SELECT name FROM admin WHERE username='$admin_username'");
$admin_data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }

    .main-content {
      padding: 60px 20px 20px 20px;
    }

    .sidebar {
      height: 100%;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #343a40;
      color: white;
      padding-top: 60px;
    }

    .sidebar a {
      padding: 10px 20px;
      display: block;
      text-decoration: none;
      color: white;
    }

    .sidebar a:hover {
      background-color: #495057;
    }

    .container {
      margin-left: 250px;
      padding: 20px;
    }

    .welcome-section img {
      width: 100%;
      height: auto;
      border-radius: 8px;
      margin-top: 20px;
    }

    .welcome-section h1 {
      margin-top: 20px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4 class="text-center">Pharmacy System</h4>
  <hr>
  <p class="text-center">Welcome, <strong><?= htmlspecialchars($admin_data['name']) ?></strong></p>
  <a href="customers_details.php">Customers</a>
  <a href="medicines.php">Medicines</a>
  <a href="suppliers.php">Suppliers</a>
  <a href="invoices.php">Invoices</a>
  <a href="notifications.php" class="btn btn-danger">Notifications</a>
  <a href="admin_logout.php"> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="container mt-5 welcome-section">
    <h1 class="display-5">Welcome, <?= htmlspecialchars($admin_data['name']) ?>!</h1>
    <p class="lead">Use the sidebar menu to manage medicines, customers, suppliers, and notifications.</p>
    <!-- Added Image below the welcome message -->
    <div class="text-center">
      <img src="picture.jpg" alt="Pharmacy Image" class="img-fluid">
    </div>
  </div>
</div>

</body>
</html>
