<?php
// --- 1. KONEKSI DATABASE ---
$host = "127.0.0.1";
$user = "root";
$pass = "root";
$db   = "db_peminjaman";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Alat & Barang</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #0f2027;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: #fff;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        .header h1 {
            font-size: 32px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #00c6ff;
        }

        .header p {
            color: rgba(255, 255, 255, 0.6);
            margin-top: 10px;
        }

        /* Search Box Sederhana */
        .search-container {
            margin-bottom: 40px;
            text-align: center;
        }

        .search-container input {
            width: 100%;
            max-width: 400px;
            padding: 12px 20px;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            outline: none;
            backdrop-filter: blur(10px);
        }

        /* Grid Katalog */
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        /* Card Style */
        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.08);
            border-color: #00c6ff;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 5px;
            background: linear-gradient(90deg, #00c6ff, #0072ff);
        }

        .item-id {
            font-size: 12px;
            color: #00c6ff;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .item-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #fff;
        }

        .item-stock {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            padding: 10px 15px;
            border-radius: 12px;
        }

        .stock-label {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
        }

        .stock-value {
            font-size: 18px;
            font-weight: 600;
            color: #2ed573;
        }

        .stock-out {
            color: #ff4757;
        }

        .btn-pinjam {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-pinjam:hover {
            filter: brightness(1.1);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .catalog-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Katalog Alat & Barang</h1>
        <p>Daftar inventaris yang tersedia untuk dipinjam</p>
    </div>

    <div class="search-container">
        <form action="" method="GET">
            <input type="text" name="cari" placeholder="Cari nama alat..." value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>">
        </form>
    </div>

    <div class="catalog-grid">
        <?php
        // Logika Pencarian
        $keyword = "";
        if (isset($_GET['cari'])) {
            $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
            $query = "SELECT * FROM Alat WHERE nama_alat LIKE '%$keyword%' ORDER BY Id_alat ASC";
        } else {
            $query = "SELECT * FROM Alat ORDER BY Id_alat ASC";
        }

        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_array($result)) {
                $stok = $data['Stok'];
        ?>
            <div class="card">
                <div class="item-id">ID: <?= $data['Id_alat'] ?></div>
                <div class="item-name"><?= $data['nama_alat'] ?></div>
                <div class="item-stock">
                    <span class="stock-label">Tersedia</span>
                    <span class="stock-value <?= ($stok <= 0) ? 'stock-out' : '' ?>">
                        <?= $stok ?> Unit
                    </span>
                </div>
                
                <?php if ($stok > 0): ?>
                    <a href="laporan.php?id=<?= $data['Id_alat'] ?>" class="btn-pinjam">Pinjam Sekarang</a>
                <?php else: ?>
                    <a href="#" class="btn-pinjam" style="background: #444; cursor: not-allowed;">Stok Habis</a>
                <?php endif; ?>
            </div>
        <?php 
            }
        } else {
            echo "<p style='text-align:center; width:100%; color:rgba(255,255,255,0.5);'>Barang tidak ditemukan.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
