<?php
// confirm_payment.php (revisi + debug)
include 'koneksi.php';

// DEBUG: set ke true via URL ?debug=1
$debug = (isset($_GET['debug']) && $_GET['debug'] === '1') || (isset($_POST['debug']) && $_POST['debug'] === '1');

// Ambil tanggal (coba GET dulu, lalu POST, fallback sekarang)
$tanggal_raw = $_REQUEST['date'] ?? date('Y-m-d'); // $_REQUEST gabungan GET/POST
$tanggal = date('Y-m-d', strtotime($tanggal_raw) ?: time());

// Ambil ticket_id & quantity dari REQUEST (dukungan GET/POST)
$ticket_ids = $_REQUEST['ticket_id'] ?? [];
$quantities = $_REQUEST['quantity'] ?? [];

// Normalisasi
if (!is_array($ticket_ids)) $ticket_ids = [$ticket_ids];
if (!is_array($quantities)) $quantities = [$quantities];

$total_harga = 0;
$tickets_data = [];

// Build qty map mendukung:
// - quantity[123]=2 (associative keyed by id)
// - quantity[] array aligned with ticket_id[]
$qty_map = [];

// 1) keyed quantities (quantity[ID] => value)
foreach ($quantities as $k => $v) {
    if (is_string($k) && ctype_digit((string)$k)) {
        $qty_map[intval($k)] = intval($v);
    }
}

// 2) if none keyed, try pair by length/order
if (empty($qty_map) && count($quantities) == count($ticket_ids) && count($ticket_ids) > 0) {
    foreach ($ticket_ids as $i => $tid) {
        $id = intval($tid);
        $qty_map[$id] = intval($quantities[$i] ?? 0);
    }
}

// 3) fallback: try pairing by index anyway (even if lengths differ)
if (empty($qty_map)) {
    foreach ($ticket_ids as $i => $tid) {
        $id = intval($tid);
        $qty_map[$id] = intval($quantities[$i] ?? 0);
    }
}

// Pastikan koneksi
if (!isset($conn) || !$conn) {
    die("Koneksi database error.");
}

// Ambil data tiket dari DB sesuai qty_map
foreach ($ticket_ids as $raw_id) {
    $id = intval($raw_id);
    if ($id <= 0) continue;
    $qty = $qty_map[$id] ?? 0;
    if ($qty <= 0) continue;

    $stmt = $conn->prepare("SELECT id, name, price FROM tickets WHERE id = ?");
    if (!$stmt) continue;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $ticket = $res->fetch_assoc();
    $stmt->close();

    if ($ticket) {
        $price = is_numeric($ticket['price']) ? (float)$ticket['price'] : 0;
        $subtotal = $price * $qty;
        $total_harga += $subtotal;
        $tickets_data[] = [
            'id' => $ticket['id'],
            'name' => $ticket['name'],
            'price' => $price,
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
    }
}

// Jika ada debug, tampilkan payload yang diterima & qty_map
if ($debug) {
    echo "<pre style='background:#111;color:#bdf;padding:12px;border-radius:8px;'>";
    echo "DEBUG INFORMASI\n\n";
    echo "=== \$_GET ===\n";
    print_r($_GET);
    echo "\n=== \$_POST ===\n";
    print_r($_POST);
    echo "\n=== \$_REQUEST ===\n";
    print_r($_REQUEST);
    echo "\n=== \$_FILES ===\n";
    print_r($_FILES);
    echo "\n=== ticket_ids (normalized) ===\n";
    print_r($ticket_ids);
    echo "\n=== quantities (normalized) ===\n";
    print_r($quantities);
    echo "\n=== qty_map ===\n";
    print_r($qty_map);
    echo "\n=== tickets_data ===\n";
    print_r($tickets_data);
    echo "\nTotal_harga computed: " . $total_harga . "\n";
    echo "</pre>";
    // don't exit: still render page so you see messages below
}

// Jika tidak ada ticket data, tampilkan pesan informatif
$no_data_message = "Data tidak lengkap atau tidak ada tiket yang dipilih.";

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f7f9fa; font-family: 'Segoe UI', sans-serif; }
    .container { max-width: 700px; background: #fff; border-radius: 15px; padding: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-top: 30px; }
    h3 { text-align: center; color: #00796b; font-weight: 700; margin-bottom: 20px; }
    .ticket-item { border-bottom: 1px solid #eee; padding: 10px 0; }
    .total { font-size: 18px; font-weight: bold; color: #00796b; text-align: right; }
  </style>
</head>
<body>
<div class="container">
  <h3>Konfirmasi Pembayaran</h3>
  <p class="text-center text-muted">Tanggal kunjungan: <?= htmlspecialchars(date('d M Y', strtotime($tanggal))) ?></p>
  <hr>

  <?php if (!empty($tickets_data)): ?>
    <?php foreach ($tickets_data as $t): ?>
      <div class="ticket-item">
        <strong><?= htmlspecialchars($t['name']) ?></strong><br>
        Harga: Rp <?= number_format($t['price'], 0, ',', '.') ?><br>
        Jumlah: <?= intval($t['qty']) ?><br>
        <span class="text-success">Subtotal: Rp <?= number_format($t['subtotal'], 0, ',', '.') ?></span>
      </div>
    <?php endforeach; ?>

    <hr>
    <p class="total">Total Pembayaran: Rp <?= number_format($total_harga, 0, ',', '.') ?></p>

   <form action="process_payment.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="tanggal_kunjungan" value="<?= htmlspecialchars($tanggal) ?>">
  <input type="hidden" name="total_harga" value="<?= htmlspecialchars($total_harga) ?>">
  <?php foreach ($tickets_data as $t): ?>
    <input type="hidden" name="ticket_id[]" value="<?= htmlspecialchars($t['id']) ?>">
    <input type="hidden" name="quantity[]" value="<?= htmlspecialchars($t['qty']) ?>">
  <?php endforeach; ?>

  <!-- Nama Pemesan -->
  <div class="mb-3">
    <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
    <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" required>
  </div>

  <!-- Nomor Telepon -->
  <div class="mb-3">
    <label for="no_telp" class="form-label">Nomor Telepon</label>
    <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="Contoh: 081234567890" required>
  </div>

  <!-- Metode Pembayaran -->
  <div class="mb-3">
    <label for="payment_method" class="form-label">Metode Pembayaran</label>
    <select class="form-select" id="payment_method" name="payment_method" required>
      <option value="">-- Pilih Metode --</option>
      <option value="Transfer Bank">Transfer Bank</option>
      <option value="E-Wallet">E-Wallet</option>
  
    </select>
  </div>

  <div class="mb-3">
    <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
    <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required>
  </div>

  <button type="submit" class="btn btn-success w-100">Kirim Konfirmasi</button>
</form>


  <?php else: ?>
    <p class="text-danger text-center"><?= htmlspecialchars($no_data_message) ?></p>
    <div class="text-center mt-3">
      <a href="index.php" class="btn btn-outline-secondary">Kembali Pilih Tiket</a>
      <a href="?debug=1" class="btn btn-link">Lihat Debug</a>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
