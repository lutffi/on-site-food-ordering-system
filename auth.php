<?php
session_start();

$valid_user = 'kasir';
$valid_pass = '1234';

if ($_POST['username'] === $valid_user && $_POST['password'] === $valid_pass) {
    $_SESSION['login'] = true;
    header("Location: dashboard_kasir.php");
    exit;
} else {
    echo "Login gagal!";
}
