<?php
session_start();
if (isset($_SESSION['admin'])){
    header("Location: admin_dashboard.php");
    exit();
}
require 'db.php';
if ($_SERVER['REQUEST_METHOD']=='POST'){
    $username=$_POST['username'];
    $password=$_POST['password'];

    $stmt=$conn->prepare("SELECT * FROM admin WHERE USERNAME = ? AND PASSWORD=?");
    $stmt->bind_param("ss",$username,$password);
    $stmt->execute();
    $result=$stmt->get_result();

    if($result->num_rows===1){
        $_SESSION['admin']=$username;
        header("Location: admin_dashboard.php");
    } 
    else {
        $error="Invalid admin credentials!";
    }
}
?>
<link rel="stylesheet" href="global.css">
<div class="container">
  <h2>Admin Login</h2>
  <form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
  </form>
</div>
