<?php
// 1. KONEKSI DATABASE
$host = "127.0.0.1";
$user = "root"; 
$pass = "root"; 
$db   = "db_peminjaman";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// 2. QUERY DATA UNTUK RINGKASAN (Cards)
// Menghitung total alat dari tabel 'Alat'
$q_alat = mysqli_query($conn, "SELECT COUNT(*) as total FROM Alat");
$total_alat = mysqli_fetch_assoc($q_alat)['total'];

// Menghitung total pengembalian dari tabel 'Pengembalian'
$q_kembali = mysqli_query($conn, "SELECT COUNT(*) as total FROM Pengembalian");
$total_kembali = mysqli_fetch_assoc($q_kembali)['total'];

// 3. QUERY UNTUK TABEL (Menampilkan isi tabel Alat)
$tabel_alat = mysqli_query($conn, "SELECT * FROM Alat ORDER BY Id_alat DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiJARU - Dashboard Peminjaman</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* CSS DISATUKAN DI SINI */
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        
        /* Sidebar */
        .sidebar { width: 250px; height: 100vh; background: #fff; position: fixed; border-right: 1px solid #dee2e6; }
        .user-panel { padding: 20px; text-align: center; border-bottom: 1px solid #eee; }
        .nav-link { color: #333; padding: 12px 20px; border-left: 3px solid transparent; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #f8f9fa; color: #3c8dbc; border-left: 3px solid #3c8dbc; }
        
        /* Main Content */
        .main-content { margin-left: 250px; min-height: 100vh; }
        .top-navbar { background-color: #3c8dbc; color: white; padding: 10px 25px; display: flex; justify-content: space-between; align-items: center; }
        
        /* Dashboard Cards */
        .card-stat { border: none; color: white; border-radius: 4px; position: relative; overflow: hidden; height: 100px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; }
        .card-stat i { font-size: 3rem; opacity: 0.3; }
        .bg-orange { background-color: #f39c12; }
        .bg-green { background-color: #00a65a; }
        .bg-red { background-color: #dd4b39; }
        
        .table-container { background: #fff; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-top: 25px; }
        th { background-color: #fcfcfc !important; text-align: center; vertical-align: middle; }
        .footer { margin-top: 50px; font-size: 12px; color: #888; text-align: center; padding-bottom: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="user-panel">
        <img src="https://ui-avatars.com/api/?name=Admin+TI&background=3c8dbc&color=fff" class="rounded-circle mb-2" width="60">
        <h6 class="mb-0">PEMINJAMAN LEVI</h6>
        <small class="text-success"><i class="fa fa-circle"></i> Online</small>
    </div>
    <div class="mt-3">
        <div class="px-3 small text-muted mb-2">MENU UTAMA</div>
        <nav class="nav flex-column">
            <a href="#" class="nav-link active"><i class="fa fa-gauge-high me-2"></i> Dashboard</a>
            <a href="databarang.php" class="nav-link"><i class="fa fa-edit me-2"></i> data barang</a>
            <a href="ajuan.php" class="nav-link"><i class="fa fa-list me-2"></i> Daftar Ajuan</a>
            <a href="login.php" class="nav-link mt-4 text-danger"><i class="fa fa-sign-out-alt me-2"></i> Keluar</a>
        </nav>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <h5 class="m-0">Levi</h5>
        <small>Halo, Administrator Database</small>
    </div>

    <div class="p-4">
        <h3>Dashboard <small class="text-muted fs-6">Resume Peminjaman 2026</small></h3>
        
        <div class="row g-3 mt-2">
            <div class="col-md-4">
                <div class="card card-stat bg-orange">
                    <i class="fa fa-pencil-alt"></i>
                    <div class="text-end">
                        <small>TOTAL JENIS ALAT</small>
                        <h2 class="m-0"><?= $total_alat ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat bg-green">
                    <i class="fa fa-check-circle"></i>
                    <div class="text-end">
                        <small>PENGEMBALIAN SELESAI</small>
                        <h2 class="m-0"><?= $total_kembali ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat bg-red">
                    <i class="fa fa-times-circle"></i>
                    <div class="text-end">
                        <small>DITOLAK / RUSAK</small>
                        <h2 class="m-0">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container p-0 overflow-hidden">
            <div class="p-3 border-bottom bg-white d-flex justify-content-between">
                <strong>Data Alat di Database</strong>
                <span class="badge bg-primary">Live Data</span>
            </div>
            <table class="table table-bordered table-hover mb-0 text-center">
                <thead>
                    <tr>
                        <th>ID Alat</th>
                        <th>Nama Alat / Barang</th>
                        <th>Stok Tersedia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($tabel_alat)): ?>
                    <tr>
                        <td><?= $row['Id_alat'] ?></td>
                        <td class="text-start ps-4"><?= $row['nama_alat'] ?></td>
                        <td><span class="badge bg-secondary"><?= $row['Stok'] ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-info text-white" onclick="alert('Detail ID: <?= $row['Id_alat'] ?>')">Detail</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if(mysqli_num_rows($tabel_alat) == 0): ?>
                        <tr><td colspan="4" class="py-4 text-muted">Belum ada data di tabel Alat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
