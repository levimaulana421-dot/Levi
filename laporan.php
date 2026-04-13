<?php
// 1. KONEKSI DATABASE
$host = "127.0.0.1";
$user = "root";
$pass = "root";
$db   = "db_peminjaman";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. PROSES SIMPAN DATA (CREATE)
if (isset($_POST['simpan'])) {
    $id_peminjam   = mysqli_real_escape_string($conn, $_POST['id_peminjam']);
    $nama_peminjam = mysqli_real_escape_string($conn, $_POST['nama_peminjam']);
    $id_alat       = mysqli_real_escape_string($conn, $_POST['id_alat']);
    $tgl_pinjam    = mysqli_real_escape_string($conn, $_POST['tgl_pinjam']);
    $tgl_kembali   = mysqli_real_escape_string($conn, $_POST['tgl_kembali']);
    $jumlah        = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $nama_barang   = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $status        = "Dipinjam"; 

    $query = "INSERT INTO Peminjam (Id_peminjam, Nama_peminjam, Id_alat, Tanggal_pinjam, Tanggal_kembali, Jumlah_pinjam, Nama_barang, Status) 
              VALUES ('$id_peminjam', '$nama_peminjam', '$id_alat', '$tgl_pinjam', '$tgl_kembali', '$jumlah', '$nama_barang', '$status')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location='peminjam.php';</script>";
    } else {
        echo "<script>alert('Gagal simpan data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Barang Siswa</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif;
        }

        body {
            background: #0f2027;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: #fff;
            padding: 40px 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.3);
        }

        h2 {
            color: #00c6ff;
            font-size: 24px;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-left: 5px solid #00c6ff;
            padding-left: 15px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            background: rgba(255, 255, 255, 0.03);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 5px;
        }

        input {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 8px;
            color: #fff;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #00c6ff;
            background: rgba(255, 255, 255, 0.12);
        }

        .btn-simpan {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-simpan:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-top: 20px;
        }

        th {
            text-align: left;
            padding: 15px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
        }

        td {
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            font-size: 14px;
        }

        td:first-child { border-radius: 10px 0 0 10px; color: #00c6ff; font-weight: 600; }
        td:last-child { border-radius: 0 10px 10px 0; }

        .status-text {
            color: #2ed573;
            font-weight: bold;
            background: rgba(46, 213, 115, 0.1);
            padding: 4px 10px;
            border-radius: 5px;
        }

        .footer-action {
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
        }

        .btn-kembali {
            color: #00c6ff;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-kembali:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Peminjaman Baru</h2>
    
    <form method="POST" action="">
        <div class="form-row">
            <div class="form-group">
                <label>ID Peminjam</label>
                <input type="text" name="id_peminjam" placeholder="0819" required>
            </div>
            <div class="form-group">
                <label>Nama </label>
                <input type="text" name="nama_peminjam" placeholder="Nama Lengkap" required>
            </div>
            <div class="form-group">
                <label>ID Alat</label>
                <input type="text" name="id_alat" placeholder="ID Alat" required>
            </div>
            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" placeholder="Nama Barang" required>
            </div>
            <div class="form-group">
                <label>Tgl Pinjam</label>
                <input type="date" name="tgl_pinjam" required>
            </div>
            <div class="form-group">
                <label>Tgl Kembali</label>
                <input type="date" name="tgl_kembali" required>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" placeholder="0" required>
            </div>
            <button type="submit" name="simpan" class="btn-simpan">Simpan Data</button>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama </th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM Peminjam ORDER BY Tanggal_pinjam DESC";
            $result = mysqli_query($conn, $sql);
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['Id_peminjam']."</td>";
                echo "<td>".$row['Nama_peminjam']."</td>";
                echo "<td>".$row['Nama_barang']."</td>";
                echo "<td>".$row['Jumlah_pinjam']."</td>";
                echo "<td>".$row['Tanggal_pinjam']."</td>";
                echo "<td><span class='status-text'>".$row['Status']."</span></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="footer-action">
        <a href="user.php" class="btn-kembali">← Kembali ke Halaman Utama</a>
    </div>
</div>

</body>
</html>
