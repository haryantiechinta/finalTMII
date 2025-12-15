<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login_form.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>

  <!-- ✅ Bootstrap 4.6.2 lokal -->
  <link rel="stylesheet" href="bootstrap-4.6.2-dist/css/bootstrap.min.css">

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('uploads/tmmi.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #333;
    }

    /* ✅ Overlay biar tulisan tetap jelas */
    body::before {
      content: "";
      position: fixed;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.85);
      z-index: -1;
    }

    /* ✅ Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 230px;
      background: linear-gradient(180deg, #5f9ea0, #3b6b6d);
      padding-top: 20px;
      box-shadow: 2px 0 8px rgba(0,0,0,0.15);
    }
    .sidebar h4 {
      text-align: center;
      margin-bottom: 2rem;
      font-weight: 600;
      color: #ffffff;
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: #e0f2f1;
      text-decoration: none;
      transition: 0.3s;
      font-weight: 500;
    }
    .sidebar a:hover {
      background: rgba(255,255,255,0.25);
      border-radius: 8px;
      color: #fff;
    }
    .sidebar a.active {
      background: #76b4b8;
      border-radius: 8px;
      color: #fff;
      font-weight: 600;
    }
    .logout {
      position: absolute;
      bottom: 20px;
      width: 100%;
      text-align: center;
    }

    /* ✅ Konten utama */
    .content {
      margin-left: 230px;
      padding: 2rem;
      min-height: 100vh;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border: none;
      background: #ffffffc9;
    }
    .card h5 {
      color: #5f9ea0;
      font-weight: 600;
    }
    h2 {
      color: #3c5e60;
      font-weight: 700;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4>Admin Panel</h4>
  <a href="tickets/ticket_add.php">➕ Tambah Tiket</a>
  <a href="tickets/ticket_list.php">🎫 Lihat Semua Tiket</a>
  <a href="orders_admin.php">📦 Lihat Semua Order</a>
  <a href="sales_report.php">📈 Rekap Penjualan</a>
  <div class="logout">
    <a href="logout.php" class="btn btn-light btn-sm text-dark font-weight-bold">Logout</a>
  </div>
</div>

<!-- Konten -->
<div class="content">
  <h2 class="mb-4">📊 Dashboard Admin</h2>
  <p>
    Selamat datang di <b>Halaman Admin</b>.  
    Gunakan menu di sebelah kiri untuk mengelola tiket, pesanan, dan laporan penjualan.
  </p>

  <div class="card p-4 mt-4">
    <h5>Informasi:</h5>
    <p class="mb-0">
      Panel ini hanya bisa diakses oleh admin untuk keperluan CRUD data tiket dan melihat data transaksi pelanggan.
    </p>
  </div>
</div>

<!-- ✅ Bootstrap JS lokal -->
<script src="bootstrap-4.6.2-dist/js/jquery.min.js"></script>
<script src="bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
