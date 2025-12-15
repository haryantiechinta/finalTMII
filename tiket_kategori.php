<?php
include 'koneksi.php';

$category = urldecode($_GET['category'] ?? '');
$tanggal = $_GET['date'] ?? date('Y-m-d');

$category = str_replace(
    [
        'Tiket Anual Pass',
        'Tiket Annual Pass',
        'Tiket Masuk & Jagat Satwa Nusantara',
        'Tiket Jagat Satwa Nusantara'
    ],
    [
        'Annual Pass',
        'Annual Pass',
        'Jagat Satwa Nusantara',
        'Jagat Satwa Nusantara'
    ],
    $category
);

$stmt = $conn->prepare("SELECT * FROM tickets WHERE category = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($category) ?> - TMII</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="campus.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    .ticket-box {
      background-color: #fff;
      border: 1px solid #dee2e6;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      transition: all 0.2s ease-in-out;
    }
    .ticket-box:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transform: translateY(-2px);
    }
    .ticket-title {
      color: #007bff;
      font-weight: 600;
      font-size: 1.1rem;
    }
    .ticket-time {
      color: #007bff;
      font-size: 0.9rem;
      margin-bottom: 6px;
    }
    .ticket-desc {
      color: #555;
      font-size: 0.9rem;
      margin-bottom: 8px;
    }
    .ticket-price {
      font-weight: 700;
      font-size: 1rem;
      color: #000;
    }
    .btn-tambah {
      background-color: #007bff;
      border: none;
      color: white;
      font-weight: 600;
      padding: 6px 18px;
      border-radius: 6px;
    }
    .btn-tambah:hover {
      background-color: #0069d9;
    }
    .qty-box {
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .qty-btn {
      background-color: #007bff;
      color: white;
      border: none;
      width: 32px;
      height: 32px;
      font-weight: bold;
      border-radius: 6px;
    }
    .qty-btn:disabled {
      background-color: #b8daff;
      cursor: not-allowed;
    }
    .qty-input {
      width: 38px;
      text-align: center;
      border: none;
      background: transparent;
      font-weight: 600;
    }
    .subtotal-box {
      border-top: 1px solid #dee2e6;
      padding-top: 15px;
      margin-top: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .btn-beli-final {
      background-color: #28a745;
      border: none;
      color: #fff;
      font-weight: 600;
      border-radius: 6px;
      padding: 10px 24px;
    }
    .btn-beli-final:hover {
      background-color: #218838;
    }
    .max-text {
      font-size: 0.8rem;
      color: #6c757d;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <h4 class="font-weight-bold text-primary mb-4">
    Tiket untuk <span class="text-dark"><?= htmlspecialchars($category) ?></span><br>
    <small class="text-muted">Tanggal: <?= date('d M Y', strtotime($tanggal)) ?></small>
  </h4>

  <form id="ticketForm" action="confirm_payment.php" method="get">
    <input type="hidden" name="date" value="<?= htmlspecialchars($tanggal) ?>">

    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="ticket-box" data-price="<?= $row['price'] ?>">
          <input type="hidden" name="ticket_id[]" value="<?= $row['id'] ?>">

          <div class="ticket-title"><?= htmlspecialchars($row['name']) ?></div>
          <div class="ticket-time">
            <i class="far fa-clock"></i> <?= date('d M Y', strtotime($tanggal)) ?>, <?= htmlspecialchars($row['time'] ?? '06.00–19.00 WIB') ?>
          </div>
          <div class="ticket-desc"><?= htmlspecialchars($row['description']) ?></div>

          <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="ticket-price">IDR <?= number_format($row['price'], 0, ',', '.') ?></div>
            <div class="qty-area">
              <button type="button" class="btn btn-tambah">Tambah</button>
              <div class="qty-box d-none">
                <button type="button" class="qty-btn minus">−</button>
                <input type="text" class="qty-input" name="quantity[]" value="1" readonly>
                <button type="button" class="qty-btn plus">+</button>
              </div>
            </div>
          </div>
          <p class="max-text mt-1 mb-0 text-right">max 10 tix/user</p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center text-muted mt-5">Belum ada tiket untuk kategori ini.</p>
    <?php endif; ?>

    <div class="subtotal-box">
      <h5 class="font-weight-bold mb-0">Subtotal: <span id="subtotal">IDR 0</span></h5>
      <button type="submit" class="btn btn-beli-final">Beli Tiket</button>
    </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
$(document).ready(function(){
  function updateSubtotal() {
    let total = 0;
    $('.ticket-box').each(function() {
      let price = parseInt($(this).data('price'));
      let qtyInput = $(this).find('.qty-input');
      if (qtyInput.length) {
        let qty = parseInt(qtyInput.val()) || 0;
        total += price * qty;
      }
    });
    $('#subtotal').text('IDR ' + total.toLocaleString('id-ID'));
  }

  // Awal: semua kosong
  $('.qty-box').each(function(){
    $(this).find('.qty-input').val(0);
  });

  // Tombol tambah ditekan
  $('.btn-tambah').click(function(){
    $(this).addClass('d-none');
    let box = $(this).siblings('.qty-box');
    box.removeClass('d-none');
    box.find('.qty-input').val(1);
    updateSubtotal();
  });

  // plus/minus
  $(document).on('click', '.plus', function(){
    let input = $(this).siblings('.qty-input');
    let val = parseInt(input.val());
    if(val < 10){
      input.val(val + 1);
      updateSubtotal();
    }
  });

  $(document).on('click', '.minus', function(){
    let input = $(this).siblings('.qty-input');
    let val = parseInt(input.val());
    if(val > 1){
      input.val(val - 1);
      updateSubtotal();
    } else {
      // balik ke tombol tambah
      let box = $(this).closest('.qty-box');
      box.addClass('d-none');
      box.siblings('.btn-tambah').removeClass('d-none');
      input.val(0);
      updateSubtotal();
    }
  });
});
</script>

</body>
</html>
