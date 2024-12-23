<?php
// Sertakan file koneksi
include 'koneksi.php';

// Tangani pencarian jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_query = "";
if ($search) {
    $search_query = "WHERE booking.nama LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}

// Query untuk mendapatkan semua data dari tabel booking
$query = "SELECT booking.*, pancake.nama_pancake FROM booking JOIN pancake ON booking.id_pancake = pancake.id_pancake $search_query";
$result = mysqli_query($conn, $query);

// Cek apakah data tersedia
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Data Booking</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .delete-button {
            text-decoration: none;
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border-radius: 3px;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
        .edit-button {
            text-decoration: none;
            padding: 5px 10px;
            background-color: #ffc107;
            color: black;
            border-radius: 3px;
        }
        .edit-button:hover {
            background-color: #e0a800;
        }
        .search-form {
            margin-bottom: 20px;
        }
        .search-input {
            padding: 8px;
            width: 300px;
            margin-right: 10px;
        }
        .search-button {
            padding: 8px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .search-button:hover {
            background-color: #0056b3;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Data Booking</h1>

    <form class="search-form" method="get" action="">
        <input type="text" name="search" class="search-input" placeholder="Cari berdasarkan nama" value="<?= htmlspecialchars($search); ?>">
        <button type="submit" class="search-button">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID Booking</th>
                <th>Nama Pancake</th>
                <th>Nama Pemesan</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Jumlah</th>
                <th>Tambahan</th>
                <th>Lokasi Antar</th>
                <th>Hari Antar</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $row['id_booking']; ?></td>
                    <td><?= htmlspecialchars($row['nama_pancake']); ?></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['telepon']); ?></td>
                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                    <td><?= $row['jumlah']; ?></td>
                    <td><?= htmlspecialchars($row['tambahan']); ?></td>
                    <td><?= htmlspecialchars($row['lokasi_antar']); ?></td>
                    <td><?= htmlspecialchars($row['hari_antar']); ?></td>
                    <td>
                        <?php if ($row['foto']) : ?>
                            <a href="<?= $row['foto']; ?>" target="_blank">Lihat Foto</a>
                        <?php else : ?>
                            Tidak ada
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="edit-button" href="?edit=<?= $row['id_booking']; ?>">Edit</a>
                        <a class="delete-button" href="hapus_booking.php?id=<?= $row['id_booking']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a class="back-button" href="index.php">Kembali ke Halaman Utama</a>

    <?php
    // Jika mode edit diaktifkan
    if (isset($_GET['edit'])) {
        $id_edit = $_GET['edit'];
        $edit_query = "SELECT * FROM booking WHERE id_booking = ?";
        $stmt = mysqli_prepare($conn, $edit_query);
        mysqli_stmt_bind_param($stmt, 'i', $id_edit);
        mysqli_stmt_execute($stmt);
        $edit_result = mysqli_stmt_get_result($stmt);
        $edit_data = mysqli_fetch_assoc($edit_result);
        if ($edit_data) {
    ?>
        <h2>Edit Booking</h2>
        <form method="post" action="">
            <input type="hidden" name="id_booking" value="<?= $edit_data['id_booking']; ?>">

            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($edit_data['nama']); ?>" required>

            <label for="telepon">Telepon:</label>
            <input type="text" id="telepon" name="telepon" value="<?= htmlspecialchars($edit_data['telepon']); ?>" required>

            <label for="alamat">Alamat:</label>
            <textarea id="alamat" name="alamat" rows="4" required><?= htmlspecialchars($edit_data['alamat']); ?></textarea>

            <label for="jumlah">Jumlah:</label>
            <input type="number" id="jumlah" name="jumlah" value="<?= $edit_data['jumlah']; ?>" min="1" required>

            <label for="tambahan">Tambahan:</label>
            <textarea id="tambahan" name="tambahan" rows="2"><?= htmlspecialchars($edit_data['tambahan']); ?></textarea>

            <label for="lokasi_antar">Lokasi Antar:</label>
            <input type="text" id="lokasi_antar" name="lokasi_antar" value="<?= htmlspecialchars($edit_data['lokasi_antar']); ?>" required>

            <label for="hari_antar">Hari Antar:</label>
            <input type="date" id="hari_antar" name="hari_antar" value="<?= htmlspecialchars($edit_data['hari_antar']); ?>" required>

            <button type="submit" name="update">Update</button>
        </form>
    <?php
        }
    }

    // Proses update data jika form edit disubmit
    if (isset($_POST['update'])) {
        $id_booking = $_POST['id_booking'];
        $nama = $_POST['nama'];
        $telepon = $_POST['telepon'];
        $alamat = $_POST['alamat'];
        $jumlah = $_POST['jumlah'];
        $tambahan = $_POST['tambahan'];
        $lokasi_antar = $_POST['lokasi_antar'];
        $hari_antar = $_POST['hari_antar'];

        $update_query = "UPDATE booking SET nama = ?, telepon = ?, alamat = ?, jumlah = ?, tambahan = ?, lokasi_antar = ?, hari_antar = ? WHERE id_booking = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, 'sssisssi', $nama, $telepon, $alamat, $jumlah, $tambahan, $lokasi_antar, $hari_antar, $id_booking);

        if (mysqli_stmt_execute($stmt)) {
            echo "<p>Data berhasil diperbarui! <a href='admin.php'>Muat ulang</a></p>";
        } else {
            echo "<p>Terjadi kesalahan: " . mysqli_error($conn) . "</p>";
        }

        mysqli_stmt_close($stmt);
    }

    // Bebaskan hasil query
    mysqli_free_result($result);

    // Tutup koneksi
    mysqli_close($conn);
    ?>
</body>
</html>