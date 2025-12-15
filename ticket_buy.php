<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: login_form.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_id = $_POST['ticket_id'];
    header("Location: confirm_payment.php?ticket_id=" . $ticket_id);
    exit;
}
?>
