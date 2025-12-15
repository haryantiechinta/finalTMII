<?php
session_start();
include "db.php";

$db = new Database();
$conn = $db->getConnection();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard User - TMII</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { 
      font-family: 'Segoe UI', sans-serif; }

    .navbar { 
      background: rgba(255,255,255,0.9); 
      box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

    .navbar-brand { font-weight: bold; 
      color: #e67e22 !important; }

    .nav-link { color: #333 !important; 
      margin: 0 5px; }

    .nav-link:hover {
       color: #e67e22 !important; }

    .hero { background: url('uploads/tmmi.jpg') no-repeat center center/cover; 
      height:80vh; display:flex; 
      align-items:center; 
      justify-content:center; 
      text-align:center; 
      color:white; 
      position:relative; }

    .hero::after { content:""; 
      position:absolute; 
      top:0; 
      left:0; 
      right:0; 
      bottom:0; 
      background:rgba(0,0,0,0.4); }

    .hero-content { 
      position:relative; 
      z-index:2; }

    .hero h1 { 
      font-size:3rem; 
      font-weight:bold; }

    section { padding:60px 0; }
    
    .section-title { 
      text-align:center; 
      margin-bottom:40px; 
      font-weight:bold; 
      font-size:28px; }

    .ticket-card { border:none; 
      border-radius:12px; 
      overflow:hidden; 
      box-shadow:0 4px 12px rgba(0,0,0,0.08); 
      transition:0.3s; }

    .ticket-card:hover { 
      transform:translateY(-5px); 
      box-shadow:0 6px 20px rgba(0,0,0,0.15); }

    .ticket-card img { 
      height:200px; object-fit:cover; }

.ig-title h2 {
  color: #000;
  text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.ig-title:hover h2 {
  color: #d63384; 
  text-shadow: 2px 2px 10px rgba(214, 51, 132, 0.4);
  transform: scale(1.02);
}

.ig-photo {
  aspect-ratio: 16 / 9;
  overflow: hidden;
  border-radius: 16px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.ig-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.ig-photo:hover img {
  transform: scale(1.05);
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-size: 150%;
  filter: invert(1);
}

@media (max-width: 768px) {
  .ig-photo {
    border-radius: 12px;
  }
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


<?php if (!isset($_GET['page'])): ?>
  <!-- HOME -->
  <div class="hero">
    <div class="hero-content">
      <h1>Taman Mini Indonesia Indah</h1>
      <p>Jelajahi keindahan budaya, flora, dan fauna Nusantara di satu tempat</p>
      <a href="dashboard_user.php?page=tiket" class="btn btn-warning btn-lg mt-3">🎟️ Lihat Tiket</a>
    </div>
  </div>

  <section>
    <div class="container">
      <h2 class="section-title">Eksplor TMII</h2>
      <div class="row align-items-center text-center">
        <div class="col-md-6 mb-4 mb-md-0">
          <p style="font-size:1.3rem; line-height:1.8; max-width:500px; margin:auto;">
           Kaya akan pesona budaya, flora, dan fauna, Indonesia punya segudang cerita yang tak kunjung ada habisnya. Mari telusuri kisah-kisah menarik Nusantara dan beragam warna di baliknya dalam perjalanan eksplorasi yang seru ga ada habisnya di TMII.
          </p>
          <button type="button" class="btn btn-outline-warning mt-3" data-bs-toggle="modal" data-bs-target="#aboutModal">Selengkapnya</button>
        </div>
        <div class="col-md-6">
          <img src="uploads/explore_tmii.jpg" class="img-fluid rounded shadow" alt="Eksplor TMII">
        </div>
      </div>
    </div>
  </section>

  <!-- Modal -->
  <div class="modal fade" id="aboutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-bold">TMII Dulu dan Kini</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body" style="text-align:justify; line-height:1.8; font-size:1.1rem;">
          <p>Ribuan corak adat dan budaya melukis cerita di setiap sudut tanah air dari Sabang hingga Merauke, menghadirkan identitas sebagai citra setiap daerah di Nusantara. Ragamnya menginspirasi mantan Ibu Negara Siti Hartinah, atau yang akrab disapa Ibu Tien Soeharto, untuk menggagaskan pendirian Taman Mini Indonesia Indah atau TMII.
            Diawali dari impian seorang Ibu Negara yang ingin membawa rakyatnya menjelajah cerita Indonesia di satu taman terbuka, TMII lahir dan diresmikan pada April 1975 silam sebagai kawasan pelestarian dan pengembangan budaya bangsa. Keragaman 33 provinsi di Indonesia dikemas dalam bentuk miniatur kepulauan Nusantara, anjungan daerah, bangunan dan arsitektur tradisional, kesenian daerah, taman rekreasi, dan berbagai macam wahana. Lahan seluas 150 hektar disulap menjadi panggung seni, rekreasi, dan sarana edukasi bagi pengunjung dari berbagai rentang usia.
            Sekian dekade berlalu hingga akhirnya pada 1 September 2023, TMII mempersembahkan wajah baru TMII yang inovatif dan revolusioner. #WajahBaruTMII mengusung empat pilar, yaitu green (hijau), inclusive (inklusif), culture (budaya), dan smart (pintar). Pilar green pada TMII menghadirkan eco-park yang 70 persen areanya adalah taman hijau yang minim emisi. Pilar inclusive mengikutsertakan semua lapisan masyarakat untuk berbagi dalam persembahan kebudayaan, sedangkan pilar culture menghadirkan destinasi wisata yang merangkum corak budaya dan seni serta menjadikan pengunjung pemeran utama dalam setiap kegiatan di TMII. Terakhir, pilar smart melengkapi wajah baru TMII melalui implementasi platform digital yang mudah dan praktis untuk eksplorasi TMII.
            Mari jelajah cerita Indonesia yang seru ga ada habisnya di TMII.</p>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
      </div>
    </div>
  </div>

  <!-- Eksplor populer (cards) -->
  <section style="background-color:#5f9ea0 ; color:white; padding:60px 0;">
    <div class="container">
      <h2 class="section-title text-white mb-5">Eksplorasi Populer di TMII</h2>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card border-0 shadow-lg h-100" style="border-radius:12px; overflow:hidden;">
            <img src="uploads/contemporary.jpg" alt="Art" class="img-fluid" style="height:230px; object-fit:cover;">
            <div class="card-body bg-white text-dark">
              <h5 class="fw-bold">Contemporary Art Gallery</h5>
              <p>Rasakan getaran kreativitas anak muda Indonesia di Contemporary Art Gallery. Sebagai panggung yang menghidupkan ekspresi seni, galeri ini tak hanya menampilkan aneka karya seni tematik, tapi juga menghadirkan aktivitas interaktif seperti painting on mini canvas dan tote bag, pembuatan karya pop up 3D, hingga acara pembacaan puisi ‘Mendadak Puitis’ berkonsep open mic yang bisa diikuti oleh semua pengunjung.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card border-0 shadow-lg h-100" style="border-radius:12px; overflow:hidden;">
            <img src="uploads/airmancur.jpg" alt="Air Mancur" class="img-fluid" style="height:230px; object-fit:cover;">
            <div class="card-body bg-white text-dark">
              <h5 class="fw-bold">Air Mancur Tirta Cerita</h5>
              <p>Pesona masa lalu dan nafas masa kini berpadu menjadi satu dalam persembahan musikal modern bertajuk Tirta Cerita, yang merupakan kolaborasi antara seni dan teknologi yang menampilkan penggalan kisah cerita rakyat Indonesia. Menghadirkan aransemen musik yang memikat dan video mapping yang menggerakkan khayal, persembahan seni ini terbuka gratis untuk semua pengunjung TMII dan siap mengajak pengunjung melintasi zaman di area Archipelago tiap pukul 18.30 WIB.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card border-0 shadow-lg h-100" style="border-radius:12px; overflow:hidden;">
            <img src="uploads/keretagantung.jpg" alt="Kereta" class="img-fluid" style="height:230px; object-fit:cover;">
            <div class="card-body bg-white text-dark">
              <h5 class="fw-bold">Kereta Gantung</h5>
              <p>Menelusuri TMII belum lengkap jika belum menaiki Kereta Gantung, di mana tiap perhentian menjadi jendela menuju keberagaman dan keajaiban Nusantara. Dengan tiga stasiun dan total 81 kabin, wahana ini siap membawa pengunjung terbang di udara sambil menelusuri pesona kepulauan Indonesia dari Sabang sampai Merauke.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Instagram pink -->
<section class="bg-white py-5">
  <div class="container text-center text-dark">
    <a href="https://www.instagram.com/tmiiindonesia/" target="_blank" class="ig-title text-decoration-none">
      <h2 class="fw-bold mb-3">✨ Ikuti Kami di Instagram ✨</h2>
    </a>
    <p class="text-secondary mb-5">
      Lihat keseruan dan update terbaru dari <strong>Taman Mini Indonesia Indah</strong>
    </p>

    <!-- Carousel Instagram -->
    <div id="igCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">

        <!-- Slide 1 -->
        <div class="carousel-item active">
          <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
              <div class="ig-photo">
                <img src="uploads/ig1.jpg" alt="IG1">
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="ig-photo">
                <img src="uploads/ig2.jpg" alt="IG2">
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
          <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
              <div class="ig-photo">
                <img src="uploads/ig3.jpg" alt="IG3">
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="ig-photo">
                <img src="uploads/ig4.jpg" alt="IG4">
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
          <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
              <div class="ig-photo">
                <img src="uploads/ig5.jpg" alt="IG5">
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="ig-photo">
                <img src="uploads/ig6.jpg" alt="IG6">
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Tombol panah -->
      <button class="carousel-control-prev" type="button" data-bs-target="#igCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#igCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>
  </div>
</section>

<!-- Footer -->
<footer style="background-color: #5f9ea0; color: white; padding: 40px 0; text-align: center;">
  <h5 class="fw-bold mb-2">Jelajah Cerita Indonesia</h5>
  <p class="mb-3">Powered by TMII & InJourney Destination Management</p>

  <!-- Media Sosial -->
  <div class="mb-3">
    <a href="https://www.instagram.com/tmiiindonesia/" target="_blank" class="btn btn-warning rounded-circle mx-1">
      <i class="bi bi-instagram"></i>
    </a>
    <a href="https://www.facebook.com/tmiiindonesia/" target="_blank" class="btn btn-warning rounded-circle mx-1">
      <i class="bi bi-facebook"></i>
    </a>
    <a href="https://www.tiktok.com/@tmiiindonesia" target="_blank" class="btn btn-warning rounded-circle mx-1">
      <i class="bi bi-tiktok"></i>
    </a>
    <a href="https://www.youtube.com/@tmiiindonesia" target="_blank" class="btn btn-warning rounded-circle mx-1">
      <i class="bi bi-youtube"></i>
    </a>
  </div>

  <!-- Kontak -->
  <p class="small mb-1">📍 Jl. Raya Taman Mini, Jakarta Timur, DKI Jakarta</p>
  <p class="small mb-1">📞 (+62) 811-8882-0220 | ✉️ cs@tamanmini.com</p>
  <p class="small mt-3 mb-0">&copy; <?= date("Y") ?> Taman Mini Indonesia Indah. All rights reserved.</p>
</footer>

<?php elseif ($_GET['page'] == 'tiket'): ?>
<!-- HALAMAN PILIH KATEGORI TIKET -->
<section class="py-5" 
  style="
    background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), 
                url('uploads/tmmi.jpg') no-repeat center center/cover;
    color: white;
  ">
  <div class="container">
    <h2 class="section-title mb-5 text-center" style="color: #fff; text-shadow: 0 2px 5px rgba(0,0,0,0.5);">
      🎟️ Pilih Jenis Tiket TMII
    </h2>

    <div class="row g-4 justify-content-center">

      <!-- Piknik Nusantara -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card ticket-card h-100 shadow-sm border-0 rounded-4 bg-white">
          <img src="uploads/pikniknusantara.jpg" class="card-img-top rounded-top" alt="Piknik Nusantara">
          <div class="card-body p-4 text-start">
            <h5 class="card-title fw-bold text-primary">Piknik Nusantara</h5>
            <p class="card-text text-muted">Dapatkan tiket Piknik Nusantara di sini</p>
            <?php if (isset($_SESSION['user'])): ?>
              <a href="pilih_tanggal.php?category=Piknik%20Nusantara" class="btn btn-warning btn-block rounded-pill font-weight-bold">Beli Tiket</a>
            <?php else: ?>
              <a href="login_form.php" class="btn btn-outline-warning btn-block rounded-pill font-weight-bold">Login untuk Beli Tiket</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Tiket Masuk -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card ticket-card h-100 shadow-sm border-0 rounded-4 bg-white">
          <img src="uploads/tiket_masuk.jpg" class="card-img-top rounded-top" alt="Tiket Masuk">
          <div class="card-body p-4 text-start">
            <h5 class="card-title fw-bold text-primary">Tiket Masuk</h5>
            <p class="card-text text-muted">Dapatkan tiket masuk TMII di sini</p>
            <?php if (isset($_SESSION['user'])): ?>
              <a href="pilih_tanggal.php?category=Tiket%20Masuk%20TMII" class="btn btn-warning btn-block rounded-pill font-weight-bold">Beli Tiket</a>
            <?php else: ?>
              <a href="login_form.php" class="btn btn-outline-warning btn-block rounded-pill font-weight-bold">Login untuk Beli Tiket</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Tiket Kereta Gantung -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card ticket-card h-100 shadow-sm border-0 rounded-4 bg-white">
          <img src="uploads/keretagantung.jpg" class="card-img-top rounded-top" alt="Tiket Kereta Gantung">
          <div class="card-body p-4 text-start">
            <h5 class="card-title fw-bold text-primary">Tiket Kereta Gantung</h5>
            <p class="card-text text-muted">Dapatkan tiket kereta gantung di sini</p>
            <?php if (isset($_SESSION['user'])): ?>
              <a href="pilih_tanggal.php?category=Tiket%20Kereta%20Gantung" class="btn btn-warning btn-block rounded-pill font-weight-bold">Beli Tiket</a>
            <?php else: ?>
              <a href="login_form.php" class="btn btn-outline-warning btn-block rounded-pill font-weight-bold">Login untuk Beli Tiket</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Tiket Masuk & Jagat Satwa Nusantara -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card ticket-card h-100 shadow-sm border-0 rounded-4 bg-white">
          <img src="uploads/jagasatwa.jpg" class="card-img-top rounded-top" alt="Tiket Masuk & Jagat Satwa Nusantara">
          <div class="card-body p-4 text-start">
            <h5 class="card-title fw-bold text-primary">Tiket Masuk & Jagat Satwa Nusantara</h5>
            <p class="card-text text-muted">Taman Burung, Dunia Air Tawar, Dunia Serangga, dan Museum Komodo</p>
            <?php if (isset($_SESSION['user'])): ?>
              <a href="pilih_tanggal.php?category=Tiket%20Masuk%20%26%20Jagat%20Satwa%20Nusantara" class="btn btn-warning btn-block rounded-pill font-weight-bold">Beli Tiket</a>
            <?php else: ?>
              <a href="login_form.php" class="btn btn-outline-warning btn-block rounded-pill font-weight-bold">Login untuk Beli Tiket</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Annual Pass -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card ticket-card h-100 shadow-sm border-0 rounded-4 bg-white">
          <img src="uploads/anuallpass.jpg" class="card-img-top rounded-top" alt="Annual Pass">
          <div class="card-body p-4 text-start">
            <h5 class="card-title fw-bold text-primary">Annual Pass</h5>
            <p class="card-text text-muted">Beli tiket masuk Annual Pass (Individu / Komunitas)</p>
            <?php if (isset($_SESSION['user'])): ?>
              <a href="pilih_tanggal.php?category=Tiket%20Anual%20Pass" class="btn btn-warning btn-block rounded-pill font-weight-bold">Beli Tiket</a>
            <?php else: ?>
              <a href="login_form.php" class="btn btn-outline-warning btn-block rounded-pill font-weight-bold">Login untuk Beli Tiket</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
