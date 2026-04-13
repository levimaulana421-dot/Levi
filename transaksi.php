<?php
$host = "127.0.0.1";
$user = "root";
$pass = "root"; 
$db   = "db_peminjaman";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Logika untuk tombol kembali menggunakan PHP
// Jika referer tidak terdeteksi, default akan kembali ke index.php
$url_kembali = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

// Bagian SIMPAN DATA tetap sama
if (isset($_POST['simpan'])) {
    $id_peminjam      = $_POST['id_peminjam'];
    $nama_peminjam    = $_POST['nama_peminjam'];
    $id_alat          = $_POST['id_alat'];
    $tgl_pinjam       = $_POST['tgl_pinjam'];
    $tgl_kembali      = $_POST['tgl_kembali'];
    $jumlah           = $_POST['jumlah'];
    $nama_barang      = $_POST['nama_barang'];
    $status           = "Dipinjam";

    $query = "INSERT INTO Peminjam 
    (Id_peminjam, Nama_peminjam, Id_alat, Tanggal_pinjam, Tanggal_kembali, Jumlah_pinjam, Nama_barang, Status)
    VALUES 
    ('$id_peminjam','$nama_peminjam','$id_alat','$tgl_pinjam','$tgl_kembali','$jumlah','$nama_barang','$status')";

    if (mysqli_query($conn, $query)) {
        mysqli_query($conn, "UPDATE Alat SET Stok = Stok - $jumlah WHERE Id_alat='$id_alat'");
        echo "<script>alert('Transaksi berhasil!');window.location='transaksi_peminjaman.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Peminjaman</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
        }

        /* Styling Tombol Kembali */
        .btn-kembali {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #475569;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-kembali:hover {
            background-color: #1e293b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background-color: #1e293b;
            color: #f8fafc;
            padding: 15px;
            text-align: left;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background-color: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h2>Riwayat Peminjaman Alat</h2>
    </header>


    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Peminjam</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ambil = mysqli_query($conn, "SELECT * FROM Peminjam ORDER BY Tanggal_pinjam DESC");
            
            if (mysqli_num_rows($ambil) > 0) {
                while ($row = mysqli_fetch_array($ambil)) {
                    ?>
                    <tr>
                        <td><strong>#<?php echo $row['Id_peminjam']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['Nama_peminjam']); ?></td>
                        <td><?php echo htmlspecialchars($row['Nama_barang']); ?></td>
                        <td><?php echo $row['Jumlah_pinjam']; ?> unit</td>
                        <td><?php echo date('d M Y', strtotime($row['Tanggal_pinjam'])); ?></td>
                        <td>
                            <?php 
                                echo ($row['Tanggal_kembali'] && $row['Tanggal_kembali'] != '0000-00-00') 
                                ? date('d M Y', strtotime($row['Tanggal_kembali'])) 
                                : '<span style="color:#94a3b8; font-style: italic;">Belum Kembali</span>'; 
                            ?>
                        </td>
                        <td>
                            <span class="status-badge">
                                <?php echo $row['Status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php 
                } 
            } else {
                echo "<tr><td colspan='7' style='text-align:center; padding:40px; color:#94a3b8;'>Belum ada data peminjaman terdaftar.</td></tr>";
            }
            ?>
        </tbody>
    </table>
        <div class="footer-action">
        <a href="duser.php" class="btn-kembali">← Kembali ke Halaman Utama</a>
    </div>
</div>

</body>
</html>
