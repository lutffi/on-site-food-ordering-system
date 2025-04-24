<?php
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

if ($username != 'kasir' || $password != '1234') {
    $_SESSION['error'] = 'Username atau password salah!';
    header('Location: login.php');
    exit;
}

$_SESSION['user'] = $username;
header('Location: dashboard_kasir.php');
exit;
?>
