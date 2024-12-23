<?php
// Sertakan file koneksi
include 'koneksi.php';

// Ambil ID pancake dari URL
$id_pancake = isset($_GET['id']) ? $_GET['id'] : null;

// Jika ID pancake tidak ada, kembali ke halaman utama
if (!$id_pancake) {
    header('Location: index.php');
    exit;
}

// Query untuk mendapatkan detail pancake berdasarkan ID
$query = "SELECT * FROM pancake WHERE id_pancake = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_pancake);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pancake = mysqli_fetch_assoc($result);

// Jika pancake tidak ditemukan, kembali ke halaman utama
if (!$pancake) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Booking Pancake</title>
    <style>
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Formulir Booking Pancake</h1>
    <p><strong>Pancake:</strong> <?= htmlspecialchars($pancake['nama_pancake']); ?> - <?= htmlspecialchars($pancake['rasa']); ?> - Rp <?= number_format($pancake['harga'], 2); ?></p>

    <form action="proses_booking.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_pancake" value="<?= $pancake['id_pancake']; ?>">

        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="telepon">Telepon:</label>
        <input type="text" id="telepon" name="telepon" required>

        <label for="alamat">Alamat:</label>
        <textarea id="alamat" name="alamat" rows="4" required></textarea>

        <label for="jumlah">Jumlah:</label>
        <input type="number" id="jumlah" name="jumlah" min="1" required>

        <label for="tambahan">Tambahan (Opsional):</label>
        <textarea id="tambahan" name="tambahan" rows="2"></textarea>

        <label for="lokasi_antar">Lokasi Antar:</label>
        <input type="text" id="lokasi_antar" name="lokasi_antar" required>

        <label for="hari_antar">Hari Antar:</label>
        <input type="date" id="hari_antar" name="hari_antar" required>

        <label for="foto">Unggah Foto (Opsional):</label>
        <input type="file" id="foto" name="foto" accept="image/*">

        <button type="submit">Pesan Sekarang</button>
    </form>
</body>
</html>