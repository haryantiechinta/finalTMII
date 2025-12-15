<?php
// Ambil data review
$reviews = $conn->query("SELECT r.rating, r.comment, u.username, r.created_at 
                         FROM reviews r 
                         JOIN users u ON r.user_id = u.id 
                         WHERE r.ticket_id='$ticket_id' 
                         ORDER BY r.created_at DESC");
?>

<h3>Review Pengguna</h3>
<?php if ($reviews->num_rows > 0): ?>
    <?php while($rev = $reviews->fetch_assoc()): ?>
        <div style="border:1px solid #ccc; margin:5px; padding:10px;">
            <b><?= $rev['username'] ?></b> - 
            <?= str_repeat("⭐", $rev['rating']) ?><br>
            <i><?= $rev['comment'] ?></i><br>
            <small><?= $rev['created_at'] ?></small>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Belum ada review.</p>
<?php endif; ?>

<a href="add_review.php?ticket_id=<?= $ticket_id ?>">✍️ Tambah Review</a>
