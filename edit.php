<?php
// --- 1. KONEKSI DATABASE ---
$host = "127.0.0.1";
$user = "root";
$pass = "root";
$db   = "db_peminjaman";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// --- 2. AMBIL DATA LAMA ---
$id_alat = $_GET['id'];
$query   = mysqli_query($koneksi, "SELECT * FROM Alat WHERE Id_alat = '$id_alat'");
$data    = mysqli_fetch_array($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

// --- 3. PROSES UPDATE DATA ---
if (isset($_POST['update'])) {
    $nama_alat = $_POST['nama_alat'];
    $stok      = $_POST['stok'];

    $update = mysqli_query($koneksi, "UPDATE Alat SET 
              nama_alat = '$nama_alat', 
              Stok = '$stok' 
              WHERE Id_alat = '$id_alat'");

    if ($update) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "Gagal memperbarui: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Alat</title>
    <style>
        /* Menggunakan style yang mirip dengan index.php */
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; padding-top: 50px; }
        .card { width: 400px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        h2 { border-left: 5px solid #007bff; padding-left: 15px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn-update { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; width: 100%; font-weight: bold; margin-top: 10px; }
        .btn-batal { display: block; text-align: center; margin-top: 10px; color: #666; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Edit Barang</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label>ID Alat (Tidak bisa diubah)</label>
            <input type="text" value="<?= $data['Id_alat']; ?>" disabled>
        </div>
        <div class="form-group">
            <label>Nama Alat</label>
            <input type="text" name="nama_alat" value="<?= $data['nama_alat']; ?>" required>
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" value="<?= $data['Stok']; ?>" required>
        </div>
        <button type="submit" name="update" class="btn-update">Simpan Perubahan</button>
        <a href="databarang.php" class="btn-batal">Batal</a>
    </form>
</div>

</body>
</html>
