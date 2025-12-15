<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jam Operasional & Tiket - TMII</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f9f9f9; }
    h2 { color: #0056b3; font-weight: bold; }
    .ticket-section {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      padding: 2rem;
      margin-top: 2rem;
    }
    .hidden { display: none; }
    .museum-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }
    .museum-card:hover { transform: translateY(-5px); }
    .museum-img {
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
      height: 180px;
      object-fit: cover;
      width: 100%;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm" style="background-color: #f8f9fa;">
  <div class="container">
    <!-- 🔹 Logo TMII -->
    <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="dashboard_user.php">
      <img src="uploads/logo-tmii.png" alt="Logo TMII" height="40" class="me-2">
    </a>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-center fw-semibold">
        <?php if(isset($_SESSION['user'])): ?>
          <li class="nav-item me-3">
            <span class="nav-link text-dark">Hi, <?= htmlspecialchars($_SESSION['user']['username']) ?> 👋</span>
          </li>
        <?php endif; ?>

        <li class="nav-item">
          <a href="dashboard_user.php" class="nav-link text-dark <?= basename($_SERVER['PHP_SELF']) == 'dashboard_user.php' ? 'active text-primary fw-bold' : '' ?>">Home</a>
        </li>
        <li class="nav-item">
          <a href="dashboard_user.php?page=tiket" class="nav-link text-dark <?= (isset($_GET['page']) && $_GET['page'] == 'tiket') ? 'active text-primary fw-bold' : '' ?>">Tiket</a>
        </li>
        <li class="nav-item">
          <a href="jam_operasional.php" class="nav-link text-dark <?= basename($_SERVER['PHP_SELF']) == 'jam_operasional.php' ? 'active text-primary fw-bold' : '' ?>">Jam Operasional & Tiket</a>
        </li>

        <?php if(isset($_SESSION['user'])): ?>
          <li class="nav-item">
            <a href="purchase_history.php" class="nav-link text-dark <?= basename($_SERVER['PHP_SELF']) == 'purchase_history.php' ? 'active text-primary fw-bold' : '' ?>">Riwayat</a>
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


<!-- Konten -->
<div class="container my-5">
  <div class="ticket-section">
    <h2 class="text-center mb-4">Informasi TMII</h2>

    <!-- Tombol Kategori -->
    <div class="text-center mb-4">
      <button class="btn btn-outline-primary me-2 px-4 py-2 fw-semibold" onclick="showSection('tiket')">🎟️ Tiket Masuk</button>
      <button class="btn btn-outline-success me-2 px-4 py-2 fw-semibold" onclick="showSection('museum')">🏛️ Museum</button>
      <button class="btn btn-outline-warning px-4 py-2 fw-semibold" onclick="showSection('wahana')">🎢 Wahana & Rekreasi</button>
    </div>

    <!-- Bagian 1: Tiket Masuk -->
    <div id="tiket" class="content-section">
      <h3 class="text-center mb-3">Jam Operasional TMII & Harga Tiket</h3>
      <div class="text-center">
        <p><strong>Gate 1</strong><br>Setiap hari<br>06.00 - 20.00 WIB</p>
        <p><strong>Gate 3</strong><br>Setiap hari<br>05.00 - 20.00 WIB</p>
        <p><strong>Gate 4 *</strong><br>Khusus Sabtu - Minggu & Libur Nasional<br>06.00 - 20.00 WIB</p>
        <p class="mt-4"><small>*Untuk pengunjung dengan tujuan ke:<br>
          Taman Burung | Museum Komodo | Indonesia Science Center (PPIPTEK) | Museum Listrik dan Energi Baru</small></p>
        <p class="text-muted">Taman Mini Indonesia Indah (TMII) pada Hari Minggu, 12 Oktober 2025, baru dapat dikunjungi pada pukul 13:00 WIB.</p>
        <p><em>Kami mohon maaf atas ketidaknyamanan ini dan mengucapkan terima kasih atas pengertian Anda.</em></p>
        <p class="fw-bold">Manajemen TMII</p>

        <a href="dashboard_user.php?page=tiket" class="btn btn-primary my-3 px-4 py-2">🎟️ Beli Tiket Disini</a>
      </div>

      <hr class="my-4">

      <h4 class="text-center mb-3">Daftar Harga Tiket</h4>
      <table class="table table-bordered text-center">
        <thead class="table-light">
          <tr><th>Kategori</th><th>Harga</th></tr>
        </thead>
        <tbody>
          <tr><td>Pintu Masuk (Hari Kerja)</td><td>Rp25.000</td></tr>
          <tr><td>Pintu Masuk (Akhir Pekan)</td><td>Rp35.000</td></tr>
          <tr><td>Mobil</td><td>Rp35.000</td></tr>
          <tr><td>Motor</td><td>Rp15.000</td></tr>
          <tr><td>Sepeda</td><td>Rp10.000</td></tr>
          <tr><td>Bus</td><td>Rp60.000</td></tr>
          <tr><td>Truk</td><td>Rp60.000</td></tr>
        </tbody>
      </table>
    </div>

 <!-- Bagian 2: Museum -->
<div id="museum" class="content-section hidden">
  <h3 class="text-center mb-3">Harga Tiket</h3>
  <div class="row g-4">
    <?php
    $museums = [
      ["nama" => "Museum Pusaka", "hari" => "Setiap Hari", "jam" => "08.00 - 17.00 WIB", "tiket" => "Gratis", "foto" => "pusaka.jpg"],
      ["nama" => "Museum Indonesia", "hari" => "Setiap Hari", "jam" => "08.00 - 17.00 WIB", "tiket" => "Gratis", "foto" => "museum_indo.jpg"],
      ["nama" => "Contemporary Art Gallery", "hari" => "Setiap Hari", "jam" => "08.00 - 17.00 WIB", "tiket" => "Rp25.000", "foto" => "contemporary.jpg"],
      ["nama" => "Museum Penerangan", "hari" => "Setiap Hari", "jam" => "09.00 - 15.00 WIB", "tiket" => "Gratis", "foto" => "penerangan.jpg"],
      ["nama" => "Museum Hakka", "hari" => "Selasa - Minggu", "jam" => "09.00 - 16.00 WIB", "tiket" => "Gratis", "foto" => "haka.jpg"],
      // ["nama" => "Museum Chengho", "hari" => "Selasa - Minggu", "jam" => "09.00 - 16.00 WIB", "tiket" => "Gratis", "foto" => "chengho.jpg"],
      ["nama" => "Museum Batik", "hari" => "Selasa - Minggu", "jam" => "09.00 - 15.00 WIB", "tiket" => "Gratis (Tutup tanggal merah & cuti bersama)", "foto" => "batik.jpg"],
      ["nama" => "Museum Pemadam Kebakaran", "hari" => "Rabu - Sabtu", "jam" => "09.00 - 15.00 WIB", "tiket" => "Gratis (Tutup tanggal merah & cuti bersama)", "foto" => "pemadam.jpg"],
      ["nama" => "Bayt Al-Qur’an & Museum Istiqlal", "hari" => "Sabtu - Kamis", "jam" => "09.00 - 15.00 WIB", "tiket" => "Rp5.000 (domestik) / Rp10.000 (mancanegara)", "foto" => "istiqlal.jpg"],
      ["nama" => "Museum Prangko", "hari" => "Setiap Hari", "jam" => "08.00 - 16.00 WIB", "tiket" => "Rp5.000", "foto" => "prangko.jpg"],
      ["nama" => "Museum Keprajuritan", "hari" => "Setiap Hari", "jam" => "09.00 - 16.00 WIB", "tiket" => "Rp5.000", "foto" => "keprajuritan.jpg"],
      ["nama" => "Museum Transportasi", "hari" => "Selasa - Minggu", "jam" => "08.00 - 16.00 WIB", "tiket" => "Rp10.000", "foto" => "transportasi.jpg"],
      ["nama" => "Museum Listrik & Energi Baru", "hari" => "Setiap Hari", "jam" => "08.30 - 15.30 WIB", "tiket" => "Rp20.000 / Rp25.000", "foto" => "listrik.jpg"],
      ["nama" => "Indonesia Science Center - PPIPTEK", "hari" => "Senin - Minggu", "jam" => "08.30 - 16.30 WIB", "tiket" => "Rp27.500", "foto" => "ppiptek.jpg"]
    ];

  foreach ($museums as $m) {
      $fotoPath = "asset/musseum/" . $m["foto"];
      if (!file_exists($fotoPath)) {
        $fotoPath = "asset/musseum/default.jpg"; 
      }

      echo '
      <div class="col-md-4 col-sm-6">
        <div class="card museum-card h-100 shadow-sm">
          <img src="' . $fotoPath . '" class="museum-img" alt="' . htmlspecialchars($m["nama"]) . '">
          <div class="card-body">
            <h5 class="card-title">' . htmlspecialchars($m["nama"]) . '</h5>
            <p class="card-text mb-1"><strong>Hari:</strong> ' . htmlspecialchars($m["hari"]) . '</p>
            <p class="card-text mb-1"><strong>Jam:</strong> ' . htmlspecialchars($m["jam"]) . '</p>
            <p class="card-text"><strong>Tiket:</strong> ' . htmlspecialchars($m["tiket"]) . '</p>
          </div>
        </div>
      </div>';
    }
    ?>
  </div>
</div>

    <!-- Bagian 3: Placeholder Wahana -->
   <!-- Bagian Wahana & Rekreasi -->
<div id="wahana" class="mb-5">
  <h3 class="text-center text-warning mb-4">🎢 Wahana & Rekreasi</h3>

  <div class="container">
    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Tirta Menari</h5>
      <p>Senin - Kamis: 13.00 WIB<br>
      Jumat: 14.00 WIB<br>
      Sabtu, Minggu, Libur Nasional: 10.00, 13.00, & 16.00 WIB<br>
      <strong>Gratis</strong></p>
    </div>

    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Tirta Cerita</h5>
      <p>Setiap hari: 18.30 WIB<br>
      <strong>Gratis</strong><br>
      *Drone show setiap Sabtu, Minggu, & Libur Nasional</p>
    </div>

    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Kereta Gantung</h5>
      <p>Stasiun A: dekat Teater Keong Emas<br>
      Stasiun B: dekat Anjungan Papua<br>
      Stasiun C: dekat Gedung Parkir<br>
      Senin - Jumat: 09.00 - 16.30 WIB — Rp50.000/orang<br>
      Sabtu - Minggu & Libur Nasional: 09.00 - 17.30 WIB — Rp60.000/orang</p>
    </div>

    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Anjungan Daerah</h5>
      <p>Setiap hari: 08.00 - 16.00 WIB<br>
      <strong>Gratis</strong></p>
    </div>

    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Jagat Satwa Nusantara</h5>
      <p>Setiap Hari: 09.00 - 17.00 WIB</p>
      <ul>
        <li>Taman Burung — Senin–Jumat: Rp60.000 | Sabtu–Minggu & Libur Nasional: Rp70.000</li>
        <li>Museum Komodo — Senin–Jumat: Rp45.000 | Sabtu–Minggu & Libur Nasional: Rp55.000</li>
        <li>Dunia Air Tawar — Senin–Jumat: Rp60.000 | Sabtu–Minggu & Libur Nasional: Rp70.000</li>
        <li>Dunia Serangga — Senin–Jumat: Rp25.000 | Sabtu–Minggu & Libur Nasional: Rp30.000</li>
      </ul>
    </div>

    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Teater Keong Emas</h5>
      <p>Setiap hari<br>
      Umum/Reguler: Rp50.000<br>
      VIP/Balkon: Rp75.000<br>
      VVIP: Rp100.000<br>
      *Jam tayang dapat berubah sewaktu-waktu</p>
    </div>

    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Skyworld Indonesia</h5>
      <p>Sabtu - Kamis: 09.00 - 17.00 WIB<br>
      Tiket Masuk: Rp90.000</p>
    </div>

    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Desa Seni Ganara Art</h5>
      <p>Setiap hari: 09.00 - 16.00 WIB<br>
      Mulai dari Rp25.000</p>
    </div>

    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Merchandise Store</h5>
      <p>Setiap hari: 08.00 - 20.00 WIB</p>
    </div>

    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Taman Budaya Tionghoa Indonesia</h5>
      <p>Selasa - Minggu: 09.00 - 16.00 WIB<br>
      <strong>Gratis</strong></p>
    </div>

    <div class="card shadow-sm mb-3 p-3">
      <h5 class="fw-bold">Istana Anak Anak Indonesia</h5>
      <p><strong>TUTUP</strong><br>
      *Dapat berswafoto di area luar istana<br>
      *Dapat piknik di area hijau sekitar Istana Anak Anak Indonesia<br>
      <strong>Gratis</strong></p>
    </div>
  </div>
</div>

<script>
function showSection(id) {
  document.querySelectorAll('.content-section').forEach(sec => sec.classList.add('hidden'));
  document.getElementById(id).classList.remove('hidden');
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
