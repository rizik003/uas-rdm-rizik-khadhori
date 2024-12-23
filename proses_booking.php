<?php
// Sertakan file koneksi
include 'koneksi.php';

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_pancake = $_POST['id_pancake'];
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];
    $jumlah = $_POST['jumlah'];
    $tambahan = $_POST['tambahan'];
    $lokasi_antar = $_POST['lokasi_antar'];
    $hari_antar = $_POST['hari_antar'];

    // Proses file upload jika ada
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['foto']['name']);
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $foto = $target_file;
        } else {
            die("Gagal mengunggah foto.");
        }
    }

    // Query untuk menyimpan data ke tabel booking
    $query = "INSERT INTO booking (id_pancake, nama, telepon, alamat, jumlah, tambahan, lokasi_antar, hari_antar, foto) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'isssissss', $id_pancake, $nama, $telepon, $alamat, $jumlah, $tambahan, $lokasi_antar, $hari_antar, $foto);

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        echo "<p>Pesanan berhasil dibuat! <a href='index.php'>Kembali ke daftar pancake</a></p>";
    } else {
        echo "<p>Terjadi kesalahan: " . mysqli_error($conn) . "</p>";
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
}

// Tutup koneksi
mysqli_close($conn);
?>