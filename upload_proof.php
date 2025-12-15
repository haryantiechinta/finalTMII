<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: login_form.php");
    exit;
}

include "db.php";
$db = new Database();
$conn = $db->getConnection();

$order_id = $_GET['order_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $targetDir = "uploads/";
    if(!is_dir($targetDir)) mkdir($targetDir);

    $fileName = time() . "_" . basename($_FILES["proof"]["name"]);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["proof"]["tmp_name"], $targetFile)) {
        $sql = "UPDATE orders SET proof='$fileName' WHERE id='$order_id'";
        if ($conn->query($sql)) {
            echo "✅ Bukti transfer berhasil diupload! Tunggu konfirmasi admin.";
            header("Refresh:2; url=orders_list.php");
        } else {
            echo "❌ Gagal menyimpan bukti: " . $conn->error;
        }
    } else {
        echo "❌ Upload gagal.";
    }
}
?>

<h2>Upload Bukti Transfer</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="proof" required>
    <button type="submit">Upload</button>
</form>
