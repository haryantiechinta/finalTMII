<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: login_form.php");
    exit;
}

include "db.php";
$db = new Database();
$conn = $db->getConnection();

if (!isset($_GET['ticket_id'])) {
    die("Tiket tidak ditemukan.");
}

$ticket_id = intval($_GET['ticket_id']);
$sql = "SELECT * FROM tickets WHERE id = $ticket_id";
$result = $conn->query($sql);
$ticket = $result->fetch_assoc();

if(!$ticket) {
    echo "Tiket tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Pemesanan - TMII</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding: 40px; background: #f9f9f9; }
    .card { max-width: 600px; margin: auto; border-radius: 12px; }
  </style>
</head>
<body>

<div class="card p-4 shadow">
  <h3 class="mb-3 text-center text-warning">Form Pemesanan Tiket</h3>
  <p><b><?= $ticket['name'] ?></b></p>
  <p>Harga per tiket: <b>Rp <?= number_format($ticket['price'],0,',','.') ?></b></p>
  <hr>

  <form action="confirm_payment.php" method="GET">
      <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
      
      <div class="mb-3">
        <label class="form-label">Nama Pemesan</label>
        <input type="text" name="nama_pemesan" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Tanggal Kunjungan</label>
        <input type="date" name="tanggal_kunjungan" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Jumlah Tiket</label>
        <input type="number" name="quantity" class="form-control" 
               value="<?= $ticket['min_people'] ?>" 
               min="<?= $ticket['min_people'] ?>" required>
      </div>

      <div class="d-flex justify-content-between">
        <a href="dashboard_user.php" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-warning">Lanjut ke Pembayaran</button>
      </div>
  </form>
</div>

</body>
</html>
