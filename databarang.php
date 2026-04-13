<?php
// 1. KONEKSI DATABASE
$host = "127.0.0.1";
$user = "root";
$pass = "root";
$db   = "db_peminjaman";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. LOGIKA TAMBAH DATA (PROSES SIMPAN)
if (isset($_POST['simpan'])) {
    $id_alat   = $_POST['id_alat'];
    $nama_alat = $_POST['nama_alat'];
    $stok      = $_POST['stok'];

    $query = "INSERT INTO Alat (Id_alat, nama_alat, Stok) VALUES ('$id_alat', '$nama_alat', '$stok')";
    mysqli_query($koneksi, $query);
    
    // Refresh halaman agar data terbaru muncul di tabel
    header("Location: " . $_SERVER['PHP_SELF']);
}

// 3. LOGIKA HAPUS DATA (OPSIONAL - AGAR TOMBOL BERFUNGSI)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM Alat WHERE Id_alat='$id'");
    header("Location: " . $_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang Baru</title>
    <style>
        /* CSS UNTUK MENYAMAKAN TAMPILAN DENGAN GAMBAR */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            padding-top: 50px;
        }
        .container {
            width: 90%;
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            border-left: 5px solid #007bff;
            padding-left: 15px;
            margin-bottom: 30px;
        }
        /* Style Form Input */
        .form-row {
            display: flex;
            gap: 10px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            align-items: flex-end;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .input-group label {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #666;
        }
        input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-simpan {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 9px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        /* Style Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #007bff;
            color: white;
            text-align: left;
            padding: 12px;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .btn-edit {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
        }
        .btn-hapus {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Barang Baru</h2>

    <form method="POST" action="">
        <div class="form-row">
            <div class="input-group">
                <label>ID Alat</label>
                <input type="text" name="id_alat" placeholder="ID" required>
            </div>
            <div class="input-group">
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" placeholder="Nama Barang" required>
            </div>
            <div class="input-group">
                <label>Stok</label>
                <input type="number" name="stok" placeholder="Jumlah" required>
            </div>
            <button type="submit" name="simpan" class="btn-simpan">Simpan</button>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Alat</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ambil_data = mysqli_query($koneksi, "SELECT * FROM Alat");
            while($row = mysqli_fetch_array($ambil_data)){
            ?>
            <tr>
                <td><?= $row['Id_alat']; ?></td>
                <td><?= $row['nama_alat']; ?></td>
                <td><?= $row['Stok']; ?></td>
                <td>
                    
                    <a href="edit.php?id=<?= $row['Id_alat']; ?>" class="btn-edit">Edit</a>

                    <a href="?hapus=<?= $row['Id_alat']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
