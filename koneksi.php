<?php
// Konfigurasi database
$host = "localhost";       // Host database (biasanya localhost)
$username = "root";        // Username database (default: root)
$password = "";            // Password database (default: kosong)
$database = "bookingpancake"; // Nama database Anda

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Pesan sukses (opsional, bisa dihapus)
echo " ";
?>