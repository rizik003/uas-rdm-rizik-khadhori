<?php
// Sertakan file koneksi
include 'koneksi.php';

// Query untuk mendapatkan data dari tabel pancake
$query = "SELECT * FROM pancake";
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
    <title>Daftar Pancake</title>
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
        .buttons {
            margin: 20px 0;
        }
        .buttons a {
            text-decoration: none;
            padding: 10px 15px;
            margin-right: 10px;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
        }
        .buttons a:hover {
            background-color: #0056b3;
        }
        .buy-button {
            text-decoration: none;
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border-radius: 3px;
        }
        .buy-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Daftar Pancake</h1>

    <div class="buttons">
        <a href="admin.php">Admin</a>
        <a href="pimpinan.php">Pimpinan</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pancake</th>
                <th>Rasa</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $row['id_pancake']; ?></td>
                    <td><?= htmlspecialchars($row['nama_pancake']); ?></td>
                    <td><?= htmlspecialchars($row['rasa']); ?></td>
                    <td><?= number_format($row['harga'], 2); ?></td>
                    <td><a class="buy-button" href="beli.php?id=<?= $row['id_pancake']; ?>">Beli</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php
    // Bebaskan hasil query
    mysqli_free_result($result);
    ?>

</body>
</html>