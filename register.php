<?php
session_start();
include "db.php";
include "users.php";

$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->register($username, $email, $password, 'user')) {
        $_SESSION['message'] = "Registrasi berhasil, silakan login.";
        header("Location: login_form.php");
    } else {
        $_SESSION['error'] = "Username atau email sudah digunakan!";
        header("Location: register_form.php");
    }
}
?>
