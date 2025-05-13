<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['NAME']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($address) || empty($contact) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check if the email already exists
        $checkEmailStmt = $conn->prepare("SELECT EMAIL FROM customers WHERE EMAIL = ?");
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            $error = "This email is already registered. Please use another email.";
        } else {
            // Insert the new customer into the database
            $stmt = $conn->prepare("INSERT INTO customers (NAME, EMAIL, PASSWORD, ADDRESS, phone) VALUES (?, ?, ?, ?, ?)");
            if ($stmt && $stmt->bind_param("sssss", $name, $email, $password, $address, $contact) && $stmt->execute()) {
                header("Location: customer_login.php?msg=registered");
                exit();
            } else {
                $error = "Something went wrong, please try again.";
            }
            $stmt->close();
        }
        $checkEmailStmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="container">
        <h2>Customer Registration</h2>
        <form method="post">
            <input type="text" name="NAME" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="contact" placeholder="Contact Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>