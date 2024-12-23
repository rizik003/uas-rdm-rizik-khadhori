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

// Function to generate CSV
function downloadCSV($conn, $search_query) {
    $filename = "data_booking.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $output = fopen('php://output', 'w');
    
    // Add the header row
    fputcsv($output, ['ID Booking', 'Nama Pancake', 'Nama Pemesan', 'Telepon', 'Alamat', 'Jumlah', 'Tambahan', 'Lokasi Antar', 'Hari Antar', 'Foto']);
    
    // Fetch and output the data rows
    $query = "SELECT booking.*, pancake.nama_pancake FROM booking JOIN pancake ON booking.id_pancake = pancake.id_pancake $search_query";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Handle CSV download request
if (isset($_GET['download'])) {
    downloadCSV($conn, $search_query);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pimpinan - Laporan Data Booking</title>
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
        .action-buttons {
            margin-bottom: 20px;
        }
        .action-buttons button {
            padding: 8px 15px;
            margin-right: 10px;
            border-radius: 5px;
        }
        .print-button {
            background-color: #28a745;
            color: white;
            border: none;
        }
        .print-button:hover {
            background-color: #218838;
        }
        .download-button {
            background-color: #007BFF;
            color: white;
            border: none;
        }
        .download-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Laporan Data Booking</h1>

    <form class="search-form" method="get" action="">
        <input type="text" name="search" class="search-input" placeholder="Cari berdasarkan nama" value="<?= htmlspecialchars($search); ?>">
        <button type="submit" class="search-button">Cari</button>
    </form>

    <!-- Action Buttons for Download and Print -->
    <div class="action-buttons">
        <a href="?download=true" class="download-button">Download CSV</a>
        <button onclick="window.print();" class="print-button">Cetak Laporan</button>
    </div>

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
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a class="back-button" href="index.php">Kembali ke Halaman Utama</a>

    <?php
    // Bebaskan hasil query
    mysqli_free_result($result);

    // Tutup koneksi
    mysqli_close($conn);
    ?>
</body>
</html>