<?php
$host = "127.0.0.1";
$user = "root";
$pass = "root";
$db   = "db_peminjaman";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
<?php
include 'koneksi.php';
// Simulasi nama user dari session
$nama_user = "Levi"; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Sarana Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        body { display: flex; height: 100vh; background-color: #f4f4f4; }

        /* Sidebar Style */
        .sidebar { width: 250px; background-color: #2c3e50; color: white; padding-top: 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 1.2rem; }
        .sidebar ul { list-style: none; }
        .sidebar ul li { padding: 15px 20px; cursor: pointer; transition: 0.3s; }
        .sidebar ul li:hover, .active { background-color: #3498db; }
        .sidebar ul li a { color: white; text-decoration: none; display: block; }
        .logout { margin-top: 50px; color: #e74c3c !important; }

        /* Main Content Style */
        .main-content { flex: 1; padding: 40px; }
        .welcome-text h1 { font-size: 24px; color: #333; }
        .welcome-text p { color: #666; margin-top: 5px; }

        /* Card Style */
        .card {
            background: white;
            margin-top: 30px;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        .icon-box {
            background-color: #3498db;
            color: white;
            padding: 15px;
            border-radius: 50%;
            margin-right: 20px;
            font-size: 24px;
        }
        .card-info h3 { font-size: 18px; color: #333; }
        .card-info p { font-size: 14px; color: #777; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>E-Sarana</h2>
        <ul>
            <li class="active"><a href="#">🏠 Dashboard</a></li>
            <li><a href="user.php">📝 Catalog baramg</a></li>
            <li><a href="transaksi.php">📜 Riwayat peminjam</a></li>
            <li class="logout"><a href="login.php" style="color: #e74c3c;">🚪 Keluar</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-text">
            <h1>Selamat Datang,Levi</h1>
            <p>Gunakan sistem ini untuk meminjam barang</p>
        </div>

        <div class="card">
            <div class="icon-box">📢</div>
            <div class="card-info">
                <h3>Ada Yang mau di pinjam?</h3>
                
            </div>
        </div>
    </div>

</body>
</html>
