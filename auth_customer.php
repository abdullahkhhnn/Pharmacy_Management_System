<?php
session_start();
if (!isset($_SESSION['customer'])) {
    header("Location: customer_login.php");
    exit();
}
?>
<link rel="stylesheet" href="global.css">
