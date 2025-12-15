<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: login_form.php");
    exit;
}

include "db.php";
$db = new Database();
$conn = $db->getConnection();

$user_id = $_SESSION['user']['id'];

$sql = "SELECT o.id, t.name AS ticket_name, t.price, o.quantity, o.total_harga,
               o.bukti_pembayaran, o.order_date, o.status
        FROM orders o 
        JOIN tickets t ON o.ticket_id = t.id 
        WHERE o.user_id = ?
        ORDER BY o.order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pembelian</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f3f5f9;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    :root {
      --main-color: #5f9ea0;
    }

    /* 🔹 Navbar putih */
    .navbar {
      background-color: #ffffff !important;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .navbar-brand {
      font-weight: bold;
      color: var(--main-color) !important;
    }
    .nav-link {
      color: #333 !important;
      transition: 0.2s;
    }
    .nav-link:hover {
      color: var(--main-color) !important;
    }
    .nav-link.active,
    .nav-link.fw-bold.text-warning {
      color: #ffc107 !important;
      font-weight: 600 !important;
    }

    /* 🔹 Judul Halaman */
    h2 {
      color: var(--main-color);
      font-weight: 700;
      text-align: center;
    }

    /* 🔹 Tabel */
    .table-container {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      margin-top: 30px;
    }

    thead.table-header {
      background-color: var(--main-color);
      color: white;
      text-transform: uppercase;
      font-size: 14px;
    }

    tbody tr:hover {
      background-color: #f1fdfd;
      transition: 0.3s;
    }

    .badge {
      padding: 6px 10px;
      border-radius: 8px;
      font-size: 13px;
    }

    /* 🔹 Footer */
    .footer {
      text-align: center;
      margin-top: 40px;
      color: #666;
      font-size: 14px;
    }

    /* 🔹 Link bukti */
    a.bukti-link {
      color: var(--main-color);
      text-decoration: none;
      font-weight: 500;
    }
    a.bukti-link:hover {
      color: #468b8f;
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <?php
    $current_page = basename($_SERVER['PHP_SELF']);
  ?>

 <!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm" style="background-color: #ffffff;">
  <div class="container">
    
    <!-- 🔹 Logo -->
    <a class="navbar-brand d-flex align-items-center fw-bold text-primary" href="dashboard_user.php">
      <img src="uploads/logo-tmii.png" alt="Logo TMII" height="42" class="me-2">
    </a>

    <!-- 🔹 Toggle button for mobile -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- 🔹 Navbar Menu -->
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-center fw-semibold">

        <?php if(isset($_SESSION['user'])): ?>
          <li class="nav-item me-3">
            <span class="nav-link text-dark">👋 Hi, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
          </li>
        <?php endif; ?>

        <li class="nav-item">
          <a href="dashboard_user.php"
             class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard_user.php' ? 'text-primary fw-bold' : 'text-dark' ?>">
             Home
          </a>
        </li>

        <li class="nav-item">
          <a href="dashboard_user.php?page=tiket"
             class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'tiket') ? 'text-primary fw-bold' : 'text-dark' ?>">
             Tiket
          </a>
        </li>

        <li class="nav-item">
          <a href="jam_operasional.php"
             class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'jam_operasional.php' ? 'text-primary fw-bold' : 'text-dark' ?>">
             Jam Operasional & Tiket
          </a>
        </li>

        <?php if(isset($_SESSION['user'])): ?>
          <li class="nav-item">
            <a href="purchase_history.php"
               class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'purchase_history.php' ? 'text-primary fw-bold' : 'text-dark' ?>">
               Riwayat
            </a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link text-danger">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a href="login_form.php" class="nav-link text-success">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

  <!-- 🔹 Konten -->
  <div class="container">
    <div class="table-container">
      <h2 class="mb-4">📜 Riwayat Pembelian Tiket</h2>

      <table class="table table-bordered table-hover">
        <thead class="table-header text-center">
          <tr>
            <th>ID Pesanan</th>
            <th>Nama Tiket</th>
            <th>Jumlah</th>
            <th>Total Harga</th>
            <th>Bukti Pembayaran</th>
            <th>Status</th>
            <th>Tanggal Pesan</th>
          </tr>
        </thead>
        <tbody>
          <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['ticket_name']) ?></td>
              <td><?= $row['quantity'] ?></td>
              <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
              <td class="text-center">
                <?php if(!empty($row['bukti_pembayaran'])): ?>
                  <a href="uploads/<?= htmlspecialchars($row['bukti_pembayaran']) ?>" target="_blank" class="bukti-link">🔍 Lihat Bukti</a>
                <?php else: ?>
                  <span class="text-muted">Belum diupload</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if($row['status'] == 'pending'): ?>
                  <span class="badge bg-warning text-dark">Pending</span>
                <?php elseif($row['status'] == 'success'): ?>
                  <span class="badge" style="background-color:#5f9ea0;">Sukses</span>
                <?php elseif($row['status'] == 'rejected'): ?>
                  <span class="badge bg-danger">Ditolak</span>
                <?php else: ?>
                  <span class="badge bg-secondary"><?= htmlspecialchars($row['status']) ?></span>
                <?php endif; ?>
              </td>
              <td><?= $row['order_date'] ?></td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted">Belum ada pembelian tiket.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 🔹 Footer -->
  <div class="footer">
    © <?= date('Y') ?> TMII Ticketing | Warna tema <span style="color:#5f9ea0;">#5f9ea0</span>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
