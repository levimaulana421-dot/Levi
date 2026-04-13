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

// ================= 1. PROSES PERSETUJUAN (BARU) =================
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status_baru = $_GET['status']; // 'Disetujui' atau 'Ditolak'
    $id_peminjam = $_GET['id'];

    // Jika ditolak, kita kembalikan stok alat yang tadi sudah dikurangi saat simpan awal
    if ($status_baru == 'Ditolak') {
        $cek_data = mysqli_query($conn, "SELECT Id_alat, Jumlah_pinjam FROM Peminjam WHERE Id_peminjam='$id_peminjam'");
        $data_p = mysqli_fetch_assoc($cek_data);
        $id_alat_kembali = $data_p['Id_alat'];
        $qty_kembali = $data_p['Jumlah_pinjam'];
        
        // Tambahkan kembali stoknya
        mysqli_query($conn, "UPDATE Alat SET Stok = Stok + $qty_kembali WHERE Id_alat='$id_alat_kembali'");
    }

    $update_status = mysqli_query($conn, "UPDATE Peminjam SET Status='$status_baru' WHERE Id_peminjam='$id_peminjam'");
    
    if ($update_status) {
        echo "<script>alert('Status pengajuan diperbarui!'); window.location='transaksi_peminjaman.php';</script>";
    }
}

// ================= EDIT DATA =================
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];

    $data = mysqli_query($conn, "SELECT * FROM Peminjam WHERE Id_peminjam='$id'");
    $rowEdit = mysqli_fetch_assoc($data);
}

// ================= DELETE DATA =================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM Peminjam WHERE Id_peminjam='$id'");
    echo "<script>alert('Data berhasil dihapus!');window.location='transaksi_peminjaman.php';</script>";
}

// ================= SIMPAN DATA =================
if (isset($_POST['simpan'])) {
    $id_peminjam      = $_POST['id_peminjam'];
    $nama_peminjam    = $_POST['nama_peminjam'];
    $id_alat          = $_POST['id_alat'];
    $tgl_pinjam       = $_POST['tgl_pinjam'];
    $tgl_kembali      = $_POST['tgl_kembali'];
    $jumlah           = $_POST['jumlah'];
    $nama_barang      = $_POST['nama_barang'];
    $status           = "Dipinjam";

    $query = "INSERT INTO Peminjam (Id_peminjam, Nama_peminjam, Id_alat, Tanggal_pinjam, Tanggal_kembali, Jumlah_pinjam, Nama_barang, Status)
              VALUES ('$id_peminjam','$nama_peminjam','$id_alat','$tgl_pinjam','$tgl_kembali','$jumlah','$nama_barang','$status')";

    if (mysqli_query($conn, $query)) {
        mysqli_query($conn, "UPDATE Alat SET Stok = Stok - $jumlah WHERE Id_alat='$id_alat'");
        echo "<script>alert('Transaksi berhasil!');window.location='transaksi_peminjaman.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// ================= UPDATE DATA =================
if (isset($_POST['update'])) {
    $id_peminjam   = $_POST['id_peminjam'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $id_alat       = $_POST['id_alat'];
    $tgl_pinjam    = $_POST['tgl_pinjam'];
    $tgl_kembali   = $_POST['tgl_kembali'];
    $jumlah        = $_POST['jumlah'];
    $nama_barang   = $_POST['nama_barang'];

    $update = "UPDATE Peminjam SET Nama_peminjam='$nama_peminjam', Id_alat='$id_alat', Tanggal_pinjam='$tgl_pinjam', Tanggal_kembali='$tgl_kembali', Jumlah_pinjam='$jumlah', Nama_barang='$nama_barang' WHERE Id_peminjam='$id_peminjam'";

    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Data berhasil diupdate!');window.location='transaksi_peminjaman.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Transaksi Peminjaman</title>
<style>
    body{ font-family: Arial; background:#f4f6f9; }
    h2{ text-align:center; color: #333; margin-top: 30px; }
    table{ width:95%; margin:20px auto; border-collapse:collapse; background:white; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    table, th, td{ border:1px solid #ddd; }
    th, td{ padding:12px; text-align:center; font-size: 14px; }
    th{ background:#28a745; color:white; }
    
    /* Tombol Style */
    a{ text-decoration:none; padding:6px 10px; border-radius:4px; color:white; font-size:12px; display: inline-block; margin: 2px; }
    .edit{ background:#007bff; }
    .hapus{ background:#dc3545; }
    .setuju{ background:#28a745; font-weight: bold; }
    .tolak{ background:#ffc107; color: #000; font-weight: bold; }
    
    /* Status Label */
    .status-badge { padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: bold; }
    .label-dipinjam { background: #e9ecef; color: #495057; }
    .label-disetujui { background: #d4edda; color: #155724; }
    .label-ditolak { background: #f8d7da; color: #721c24; }
    
    tr:hover { background-color: #f9f9f9; }
</style>
</head>
<body>

<h2>Data Peminjaman & Persetujuan</h2>

<table>
<tr>
    <th>ID</th>
    <th>Nama Peminjam</th>
    <th>Barang</th>
    <th>Jumlah</th>
    <th>Tgl Pinjam</th>
    <th>Status</th>
    <th>Konfirmasi Admin</th>
    <th>Aksi</th>
</tr>

<?php
$ambil = mysqli_query($conn, "SELECT * FROM Peminjam ORDER BY Id_peminjam DESC");
while ($row = mysqli_fetch_array($ambil)) {
    // Menentukan warna badge berdasarkan status
    $st = $row['Status'];
    $class_status = "label-dipinjam";
    if($st == 'Disetujui') $class_status = "label-disetujui";
    if($st == 'Ditolak') $class_status = "label-ditolak";
?>
<tr>
    <td><?php echo $row['Id_peminjam']; ?></td>
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
            <small style="color: #888;">Sudah diproses</small>
        <?php endif; ?>
    </td>

    <td>
        <a class="edit" href="?edit=<?php echo $row['Id_peminjam']; ?>">Edit</a>
        <a class="hapus" href="?hapus=<?php echo $row['Id_peminjam']; ?>" onclick="return confirm('Yakin ingin hapus data?')">Hapus</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
