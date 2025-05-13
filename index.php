<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Pharmacy Management System</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 100px;
      text-align: center;
    }
    h1 {
      margin-bottom: 30px;
    }
    .btn-group {
      margin-top: 20px;
    }
  </style>
</head>
<body>

<div class="container">
  <h1>Welcome to Pharmacy Management System</h1>
  <p>Please select your role to continue</p>
  
  <div class="btn-group d-flex flex-column align-items-center gap-3">
    <a href="admin_login.php" class="btn btn-primary w-50">Admin Login</a>
    <a href="customer_login.php" class="btn btn-success w-50">Customer Login</a>
    <a href="customer_register.php" class="btn btn-outline-success w-50">Customer Register</a>
  </div>
</div>

</body>
</html>