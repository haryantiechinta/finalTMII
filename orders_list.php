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
$sql = "SELECT o.id, t.name, t.price, o.order_date, o.status, o.proof
        FROM orders o 
        JOIN tickets t ON o.ticket_id = t.id 
        WHERE o.user_id = $user_id 
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
?>

<h2>Riwayat Pembelian Tiket</h2>
<a href="user_dashboard.php">⬅️ Kembali ke Dashboard</a> | 
<a href="logout.php">Logout</a>

<table border="1" cellpadding="10">
<tr>
    <th>ID Pesanan</th>
    <th>Nama Tiket</th>
    <th>Harga</th>
    <th>Tanggal Pesan</th>
    <th>Status</th>
    <th>Bukti Transfer</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['price'] ?></td>
    <td><?= $row['order_date'] ?></td>
    <td>
        <?php if($row['status'] == 'pending'): ?>
            ⏳ Pending
        <?php elseif($row['status'] == 'success'): ?>
            ✅ Disetujui
        <?php else: ?>
            ❌ Ditolak
        <?php endif; ?>
    </td>
    <td>
        <?php if($row['proof']): ?>
            <a href="uploads/<?= $row['proof'] ?>" target="_blank">Lihat Bukti</a>
        <?php elseif($row['status'] == 'pending'): ?>
            <a href="upload_proof.php?order_id=<?= $row['id'] ?>">Upload Bukti</a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</table>
