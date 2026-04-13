<?php
session_start();
// Pastikan file koneksi.php sudah benar (host, user, pass, db_peminjaman)
include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
    header("Location: login.php");
    exit;
}

// 1. Ambil Statistik dari Database sesuai gambar kamu
// Menghitung total alat dari tabel 'Alat'
$q_alat = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM Alat");
$total_alat = ($q_alat) ? mysqli_fetch_assoc($q_alat)['total'] : 0;

// Menghitung total transaksi dari tabel 'Peminjam'
$q_pinjam = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM Peminjam");
$total_transaksi = ($q_pinjam) ? mysqli_fetch_assoc($q_pinjam)['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Peminjaman</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #0f2027; background: linear-gradient(135deg, #0f2027, #2c5364); color: #fff; min-height: 100vh; display: flex; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-right: 1px solid rgba(255, 255, 255, 0.1); padding: 30px 20px; }
        .sidebar h2 { color: #00c6ff; text-align: center; margin-bottom: 30px; font-size: 18px; letter-spacing: 1px; }
        .sidebar a { text-decoration: none; color: rgba(255, 255, 255, 0.7); padding: 13px; margin: 8px 0; display: block; border-radius: 10px; transition: 0.3s; }
        .sidebar a:hover { background: rgba(0, 198, 255, 0.2); color: #00c6ff; }
        .logout { color: #ff4757 !important; margin-top: 50px !important; }

        /* Main Content */
        .main { flex: 1; padding: 40px; }
        .header { margin-bottom: 30px; }
        .stats { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .card { background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.1); }
        .card h3 { font-size: 32px; color: #00c6ff; }
        
        /* Table */
        .box-table { background: rgba(255, 255, 255, 0.05); padding: 20px; border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; padding: 12px; border-bottom: 2px solid rgba(255, 255, 255, 0.1); color: #00c6ff; }
        td { padding: 12px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: 14px; }
        .badge { padding: 4px 10px; border-radius: 5px; font-size: 12px; background: #2ed573; color: #fff; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>PETUGAS PANEL</h2>
        <a href="#"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="databarang.php"><i class="fas fa-tools"></i> Data Alat</a>
        <a href="riwayat.php"><i class="fas fa-list"></i> Peminjaman</a>
        <a href="login.php" class="logout"><i class="fas fa-sign-out-alt"></i> Keluar</a>
    </div>

    <div class="main">
        <div class="header">
            <h1>Halo, <?php echo $_SESSION['nama']; ?></h1>
            <p>Sistem Peminjaman Barang - Database Connected</p>
        </div>

        <div class="stats">
            <div class="card">
                <p>Total Stok Alat</p>
                <h3><?php echo $total_alat; ?></h3>
            </div>
            <div class="card">
                <p>Total Transaksi</p>
                <h3><?php echo $total_transaksi; ?></h3>
            </div>
        </div>

        <div class="box-table">
            <h3>Daftar Peminjaman Terbaru</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Peminjam</th>
                        <th>Barang</th>
                        <th>Tgl Pinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mengambil data dari tabel Peminjam sesuai gambar
                    $query = mysqli_query($koneksi, "SELECT * FROM Peminjam ORDER BY Tanggal_pinjam DESC LIMIT 5");
                    while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                    <tr>
                        <td><?php echo $data['Id_peminjan']; ?></td> <td><?php echo $data['Nama_peminjam']; ?></td>
                        <td><?php echo $data['Nama_barang']; ?></td>
                        <td><?php echo $data['Tanggal_pinjam']; ?></td>
                        <td><span class="badge"><?php echo $data['Status']; ?></span></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
