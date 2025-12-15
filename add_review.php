<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: login_form.php");
    exit;
}

include "db.php";
$db = new Database();
$conn = $db->getConnection();

$ticket_id = $_GET['ticket_id'] ?? null;
$user_id = $_SESSION['user']['id'];

// Cek apakah user sudah pernah beli tiket ini dan status sukses
$cek = $conn->query("SELECT * FROM orders 
                     WHERE user_id='$user_id' 
                     AND ticket_id='$ticket_id' 
                     AND status='success' 
                     LIMIT 1");

if ($cek->num_rows == 0) {
    die("❌ Kamu belum membeli tiket ini atau belum diverifikasi admin.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Cek apakah user sudah pernah review tiket ini
    $cekReview = $conn->query("SELECT * FROM reviews 
                               WHERE user_id='$user_id' 
                               AND ticket_id='$ticket_id'");
    if ($cekReview->num_rows > 0) {
        die("⚠️ Kamu sudah pernah memberi review untuk tiket ini.");
    }

    $sql = "INSERT INTO reviews (user_id, ticket_id, rating, comment) 
            VALUES ('$user_id', '$ticket_id', '$rating', '$comment')";
    if ($conn->query($sql)) {
        echo "<p style='color:green'>✅ Review berhasil ditambahkan</p>";
        echo "<a href='ticket_detail.php?id=$ticket_id'>⬅️ Kembali ke detail tiket</a>";
        exit;
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<h2>Tambah Review</h2>
<form method="POST">
    <label>Rating:</label><br>
    <?php for($i=1;$i<=5;$i++): ?>
        <input type="radio" name="rating" value="<?= $i ?>" required> <?= $i ?> ⭐
    <?php endfor; ?><br><br>

    <label>Komentar:</label><br>
    <textarea name="comment" rows="4" cols="40" required></textarea><br><br>

    <button type="submit">Kirim Review</button>
</form>
