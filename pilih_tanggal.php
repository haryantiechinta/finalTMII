<?php
$category = $_GET['category'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pilih Tanggal - <?= htmlspecialchars($category) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .date-card {
      max-width: 450px;
      margin: 100px auto;
      background: white;
      border-radius: 12px;
      padding: 40px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .btn-next {
      background: #198754;
      color: white;
      font-weight: 600;
      border-radius: 10px;
      border: none;
      transition: background 0.2s;
    }
    .btn-next:hover { background: #157347; }
  </style>
</head>
<body>

<div class="container">
  <div class="date-card text-center">
    <h4 class="text-success fw-bold mb-4">Pilih Tanggal Kunjungan</h4>
    <form action="tiket_kategori.php" method="get">
      <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
      <input type="date" name="date" class="form-control mb-3" required>
      <button type="submit" class="btn btn-next w-100 py-2">Lihat Tiket</button>
    </form>
  </div>
</div>

</body>
</html>
