<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login_form.php");
    exit;
}

include "db.php";
$db = new Database();
$conn = $db->getConnection();

// Query rekap penjualan
$sql = "SELECT 
            t.name, 
            COUNT(o.id) AS total_terjual, 
            SUM(t.price) AS total_pendapatan
        FROM tickets t
        LEFT JOIN orders o ON t.id = o.ticket_id
        GROUP BY t.id
        ORDER BY total_pendapatan DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rekap Penjualan Tiket</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="bootstrap-4.6.2-dist/css/bootstrap.min.css">

  <style>
    body {
      background: #f5f7fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    h2 {
      margin: 30px 0 20px;
      text-align: center;
      font-weight: 600;
      color: #333;
    }
    .table-container {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      margin-bottom: 40px;
    }
    .table thead th {
      background: #e9eefb;
      color: #333;
      font-weight: 600;
      text-align: center;
    }
    .table td, .table th {
      text-align: center;
      vertical-align: middle;
      border: 1px solid #bbb !important;
    }
    .btn {
      border-radius: 6px;
    }
    .back-links {
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>💰 Rekap Penjualan Tiket</h2>

  <div class="back-links">
    <a href="admin_dashboard.php" class="btn btn-secondary">⬅️ Kembali ke Dashboard</a>
    <a href="logout.php" class="btn btn-danger ml-2">Logout</a>
  </div>

  <div class="table-container">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama Tiket</th>
          <th>Total Terjual</th>
          <th>Total Pendapatan (Rp)</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= $row['total_terjual'] ?? 0 ?></td>
          <td>Rp <?= number_format($row['total_pendapatan'] ?? 0, 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="bootstrap-4.6.2-dist/js/jquery.min.js"></script>
<script src="bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
