<?php
session_start();
include 'koneksi.php';

$user_id = $_SESSION['user']['id'] ?? null;
if (!$user_id) {
    die("Anda harus login dulu sebelum membeli tiket.");
}

$tanggal = $_POST['tanggal_kunjungan'] ?? null;
$nama = $_POST['nama_pemesan'] ?? null;
$no_telp = $_POST['no_telp'] ?? null;
$metode = $_POST['payment_method'] ?? null;
$ticket_ids = $_POST['ticket_id'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$status = 'pending';

// Validasi data
if (!$nama || !$no_telp || !$metode || empty($ticket_ids)) {
    die("Data tidak lengkap.");
}

$total_harga = 0;
foreach ($ticket_ids as $i => $ticket_id) {
    $qty = intval($quantities[$i] ?? 1);
    $stmt = $conn->prepare("SELECT price FROM tickets WHERE id = ?");
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $total_harga += $row['price'] * $qty;
    }
    $stmt->close();
}

// Upload bukti pembayaran
if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] !== UPLOAD_ERR_OK) {
    die("Upload bukti pembayaran gagal atau belum diupload.");
}

$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$uploadFileName = uniqid() . '_' . basename($_FILES['bukti_pembayaran']['name']);
$uploadPath = $uploadDir . $uploadFileName;
if (!move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $uploadPath)) {
    die("Gagal memindahkan file bukti pembayaran.");
}

// Simpan ke database
foreach ($ticket_ids as $i => $ticket_id) {
    $qty = intval($quantities[$i] ?? 1);

    $stmt = $conn->prepare("INSERT INTO orders 
        (user_id, ticket_id, nama_pemesan, no_telp, tanggal_kunjungan, order_date, bukti_pembayaran, status, quantity, total_harga, payment_method)
        VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssssdss", $user_id, $ticket_id, $nama, $no_telp, $tanggal, $uploadFileName, $status, $qty, $total_harga, $metode);
    $stmt->execute();
    $stmt->close();
}

echo "<script>
    alert('✅ Pembayaran berhasil! Tunggu verifikasi admin.');
    window.location.href = 'purchase_history.php';
</script>";
?>
