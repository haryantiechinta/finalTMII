<?php
session_start();
include "db.php";
include "users.php";

$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $user->login($username, $password);

    if ($result) {
        $_SESSION['user'] = $result;
        if ($result['role'] == 'admin') {
            header("Location: admin_dashboard.php");
            exit;
        } else {
            header("Location: dashboard_user.php");
            exit;
        }
    } else {
        // sementara untuk debug
        echo "Login gagal! Username atau password salah.";
        // header("Location: login_form.php");  // bisa diaktifkan setelah yakin
    }
}
?>
