<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login_form.php");
    exit;
}

include "db.php";
$db = new Database();
$conn = $db->getConnection();

// ✅ PROSES APPROVE / REJECT
if (isset($_POST['aksi']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $aksi = $_POST['aksi'];

    if ($aksi == 'approve') {
        $stmt = $conn->prepare("UPDATE orders SET status = 'approved' WHERE id = ?");
    } elseif ($aksi == 'reject') {
        $stmt = $conn->prepare("UPDATE orders SET status = 'rejected' WHERE id = ?");
    }

    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ✅ QUERY DATA ORDER (tambah no_telp)
$sql = "SELECT o.id, u.username, o.nama_pemesan, o.no_telp, t.name AS tiket, t.price, 
               o.quantity, o.total_harga, o.tanggal_kunjungan, 
               o.bukti_pembayaran, o.status
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN tickets t ON o.ticket_id = t.id
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Order</title>

  <link rel="stylesheet" href="../bootstrap-4.6.2-dist/css/bootstrap.min.css">
  <style>
    body { background-color: #f3f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .page-header { background: linear-gradient(90deg, #007bff, #5f9ea0); color: white; padding: 25px 0; text-align: center; border-radius: 0 0 15px 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); margin-bottom: 40px; }
    .page-header h2 { font-weight: 700; margin-bottom: 8px; }
    .table-container { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
    .badge { padding: 6px 10px; border-radius: 8px; font-size: 13px; }
    .btn-sm { padding: 4px 10px; font-size: 0.8rem; border-radius: 8px; margin: 2px; }
    .top-buttons { text-align: center; margin-bottom: 25px; }
  </style>
</head>
<body>

  <div class="page-header">
    <h2>📊 Laporan Order Tiket</h2>
    <p>Daftar seluruh pesanan tiket TMII</p>
  </div>

  <div class="container mb-5">
    <div class="top-buttons">
      <a href="admin_dashboard.php" class="btn btn-secondary">⬅️ Kembali ke Dashboard</a>
      <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
    </div>

    <div class="table-container">
      <table class="table table-bordered table-hover text-center">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nama Pemesan</th>
            <th>No. Telp</th>
            <th>Nama Tiket</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Tanggal Kunjungan</th>
            <th>Bukti Pembayaran</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['nama_pemesan'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['no_telp'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['tiket'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td>Rp <?= number_format($row['price'] ?? 0, 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['quantity'] ?? 0, ENT_QUOTES, 'UTF-8') ?></td>
                <td>Rp <?= number_format($row['total_harga'] ?? 0, 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['tanggal_kunjungan'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <?php if(!empty($row['bukti_pembayaran'])): ?>
                    <a href="uploads/<?= htmlspecialchars($row['bukti_pembayaran'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn-info btn-sm">🔍 Lihat</a>
                  <?php else: ?>
                    <span class="text-muted">Belum upload</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (($row['status'] ?? '') == 'approved'): ?>
                    <span class="badge badge-success">Disetujui</span>
                  <?php elseif (($row['status'] ?? '') == 'pending'): ?>
                    <span class="badge badge-warning">Menunggu</span>
                  <?php elseif (($row['status'] ?? '') == 'rejected'): ?>
                    <span class="badge badge-danger">Ditolak</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">Tidak diketahui</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (($row['status'] ?? '') == 'pending'): ?>
                    <form method="POST" style="display:inline;">
                      <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                      <button name="aksi" value="approve" class="btn btn-success btn-sm">✅ ACC</button>
                      <button name="aksi" value="reject" class="btn btn-danger btn-sm">❌ Tolak</button>
                    </form>
                  <?php else: ?>
                    <span class="text-muted">Selesai</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="12" class="text-muted">Belum ada data order.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="../bootstrap-4.6.2-dist/js/jquery.min.js"></script>
  <script src="../bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
