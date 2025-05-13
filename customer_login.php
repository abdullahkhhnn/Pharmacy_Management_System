<?php
session_start();
require 'db.php';

if (isset($_SESSION['customer_email'])) {
    header("Location: customer_dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();

        // Check if the password is hashed
        if (password_verify($password, $row['PASSWORD']) || $password === $row['PASSWORD']) {
            // Password matches (either hashed or plain text)
            $_SESSION['customer_email'] = $email;
            header("Location: customer_dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No user found. <a href='customer_register.php'>Register here</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
    <link rel="stylesheet" href="global.css">
    <style>
        .container {
            width: 300px;
            margin: auto;
            padding-top: 50px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
        p {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Customer Login</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <?php if (!empty($error)) echo "<p>$error</p>"; ?>
        <p><a href="customer_register.php">Don't have an account? Register</a></p>
    </form>
</div>

</body>
</html>