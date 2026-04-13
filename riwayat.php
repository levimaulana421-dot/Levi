<?php
$host = "127.0.0.1";
$user = "root";
$pass = "root";
$db   = "db_peminjaman";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$edit = false;

// ================= 1. PROSES PERSETUJUAN =================
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status_baru = $_GET['status'];
    $id_peminjam = $_GET['id'];

    if ($status_baru == 'Ditolak') {
        $cek_data = mysqli_query($conn, "SELECT Id_alat, Jumlah_pinjam FROM Peminjam WHERE Id_peminjam='$id_peminjam'");
        $data_p = mysqli_fetch_assoc($cek_data);
        $id_alat_kembali = $data_p['Id_alat'];
        $qty_kembali = $data_p['Jumlah_pinjam'];
        mysqli_query($conn, "UPDATE Alat SET Stok = Stok + $qty_kembali WHERE Id_alat='$id_alat_kembali'");
    }

    $update_status = mysqli_query($conn, "UPDATE Peminjam SET Status='$status_baru' WHERE Id_peminjam='$id_peminjam'");
    
    if ($update_status) {
        echo "<script>alert('Status pengajuan diperbarui!'); window.location='transaksi_peminjaman.php';</script>";
    }
}

// ================= DELETE DATA =================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM Peminjam WHERE Id_peminjam='$id'");
    echo "<script>alert('Data berhasil dihapus!');window.location='transaksi_peminjaman.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Peminjaman - Dark Edition</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background: #0f2027;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #fff;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 1100px;
            margin: auto;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 25px 45px rgba(0,0,0,0.4);
        }

        h2 {
            text-align: center;
            color: #00c6ff;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        th {
            background: transparent;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 400;
            font-size: 13px;
            padding: 15px;
            text-transform: uppercase;
        }

        tr {
            transition: 0.3s;
        }

        td {
            background: rgba(255, 255, 255, 0.03);
            padding: 15px;
            text-align: center;
            font-size: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        td:first-child { border-radius: 12px 0 0 12px; border-left: 1px solid rgba(255, 255, 255, 0.05); }
        td:last-child { border-radius: 0 12px 12px 0; border-right: 1px solid rgba(255, 255, 255, 0.05); }

        tr:hover td {
            background: rgba(255, 255, 255, 0.08);
            transform: scale(1.01);
        }

        /* Badge Status */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .label-dipinjam { background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid #ffc107; }
        .label-disetujui { background: rgba(46, 213, 115, 0.2); color: #2ed573; border: 1px solid #2ed573; }
        .label-ditolak { background: rgba(255, 71, 87, 0.2); color: #ff4757; border: 1px solid #ff4757; }

        /* Tombol */
        a {
            text-decoration: none;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            transition: 0.3s;
        }

        .setuju { background: #2ed573; color: #fff; margin-right: 5px; }
        .setuju:hover { background: #1f9d55; box-shadow: 0 0 10px rgba(46, 213, 115, 0.5); }

        .tolak { background: #ffa502; color: #fff; }
        .tolak:hover { background: #e19200; box-shadow: 0 0 10px rgba(255, 165, 2, 0.5); }

        .edit { background: rgba(0, 198, 255, 0.2); color: #00c6ff; border: 1px solid #00c6ff; }
        .edit:hover { background: #00c6ff; color: #fff; }

        .hapus { background: rgba(255, 71, 87, 0.2); color: #ff4757; border: 1px solid #ff4757; }
        .hapus:hover { background: #ff4757; color: #fff; }

        small { color: rgba(255, 255, 255, 0.3); font-style: italic; }
    </style>
</head>
<body>

<div class="container">
    <h2>Data Peminjaman & Persetujuan</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Peminjam</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Status</th>
                <th>Konfirmasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ambil = mysqli_query($conn, "SELECT * FROM Peminjam ORDER BY Id_peminjam DESC");
            while ($row = mysqli_fetch_array($ambil)) {
                $st = $row['Status'];
                $class_status = "label-dipinjam";
                if($st == 'Disetujui') $class_status = "label-disetujui";
                if($st == 'Ditolak') $class_status = "label-ditolak";
            ?>
            <tr>
                <td><strong><?php echo $row['Id_peminjam']; ?></strong></td>
                <td><?php echo $row['Nama_peminjam']; ?></td>
                <td><?php echo $row['Nama_barang']; ?></td>
                <td><?php echo $row['Jumlah_pinjam']; ?></td>
                <td><?php echo $row['Tanggal_pinjam']; ?></td>
                <td><span class="status-badge <?php echo $class_status; ?>"><?php echo $st; ?></span></td>
                
                <td>
                    <?php if ($st == 'Dipinjam') : ?>
                        <a class="setuju" href="?status=Disetujui&id=<?php echo $row['Id_peminjam']; ?>" onclick="return confirm('Setujui permintaan ini?')">Setuju</a>
                        <a class="tolak" href="?status=Ditolak&id=<?php echo $row['Id_peminjam']; ?>" onclick="return confirm('Tolak permintaan ini?')">Tolak</a>
                    <?php else : ?>
                        <small>Processed</small>
                    <?php endif; ?>
                </td>

                <td>
                    <a class="edit" href="edit_peminjaman.php?edit=<?php echo $row['Id_peminjam']; ?>">Edit</a>
                    <a class="hapus" href="?hapus=<?php echo $row['Id_peminjam']; ?>" onclick="return confirm('Yakin ingin hapus data?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
