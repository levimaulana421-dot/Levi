<?php
session_start();
include 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {
    $role     = $_POST['role'];
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // ================= LOGIN ADMIN =================
    if ($role == "admin") {
        $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE nama='$username' AND password='$password'");
        if ($query && mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            $_SESSION['id_user'] = $data['id_admin'];
            $_SESSION['nama']    = $data['nama'];
            $_SESSION['role']    = "admin";
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Login Admin gagal!";
        }

    // ================= LOGIN PETUGAS (TERHUBUNG KE TABEL PETUGAS) =================
    } elseif ($role == "petugas") {
        // Mengambil data dari tabel Petugas sesuai gambar database Anda
        $query = mysqli_query($koneksi, "SELECT * FROM Petugas WHERE username='$username' AND password='$password'");
        
        if ($query && mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            // Pastikan kolom ID di tabel petugas Anda namanya sesuai, di sini saya asumsikan id_petugas
            $_SESSION['id_user'] = isset($data['id_petugas']) ? $data['id_petugas'] : 0; 
            $_SESSION['nama']    = $data['username'];
            $_SESSION['role']    = "petugas";
            header("Location: petugas.php"); 
            exit;
        } else {
            $error = "Akun Petugas tidak ditemukan!";
        }

    // ================= LOGIN USER =================
    } elseif ($role == "user") {
        $query = mysqli_query($koneksi, "SELECT * FROM User1 WHERE username='$username' AND password='$password'");
        if ($query && mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            $_SESSION['id_user'] = isset($data['id']) ? $data['id'] : 0; 
            $_SESSION['nama']    = $data['username'];
            $_SESSION['role']    = "user";
            header("Location: duser.php");
            exit;
        } else {
            $error = "Username atau Password salah!";
        }
    } else {
        $error = "Pilih role terlebih dahulu!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Modern - Peminjaman Alat</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; height: 100vh; display: flex; justify-content: center; align-items: center; background: #0f2027; overflow: hidden; }
        #particles-js { position: absolute; width: 100%; height: 100%; background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%); z-index: -1; }
        .login-box { width: 380px; padding: 40px; background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 24px; box-shadow: 0 25px 45px rgba(0, 0, 0, 0.3); transition: transform 0.3s ease; }
        .login-box:hover { transform: translateY(-8px); }
        .login-box h2 { color: #fff; text-align: center; font-size: 28px; font-weight: 600; letter-spacing: 1.5px; margin-bottom: 30px; text-transform: uppercase; }
        select, input { width: 100%; padding: 14px 18px; margin-bottom: 18px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; color: #fff; font-size: 14px; outline: none; transition: all 0.3s ease; }
        select option { background: #1c313a; color: #fff; }
        input::placeholder { color: rgba(255, 255, 255, 0.5); }
        select:focus, input:focus { border-color: #00c6ff; box-shadow: 0 0 15px rgba(0, 198, 255, 0.4); }
        button { width: 100%; padding: 14px; margin-top: 10px; border: none; border-radius: 12px; background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.4s ease; box-shadow: 0 10px 20px rgba(0, 114, 255, 0.3); }
        button:hover { background: linear-gradient(135deg, #0072ff, #00c6ff); transform: scale(1.03); }
        .register-text { text-align: center; margin-top: 25px; font-size: 13px; color: rgba(255, 255, 255, 0.7); }
        .register-text a { text-decoration: none; color: #00c6ff; font-weight: 600; }
        .error { margin-top: 20px; background: rgba(255, 71, 87, 0.2); border-left: 4px solid #ff4757; color: #ffda79; padding: 12px; border-radius: 8px; font-size: 13px; text-align: center; animation: shake 0.4s ease-in-out; }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-6px); } 75% { transform: translateX(6px); } }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <div class="login-box">
        <h2>Login</h2>
        <form method="POST">
            <select name="role" required>
                <option value="">-- Login Sebagai --</option>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option> <option value="user">User</option>
            </select>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Sign In</button>
            <p class="register-text">Belum punya akun? <a href="daftar.html">Daftar Sekarang</a></p>
        </form>
        <?php if ($error != ""): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", { 
            particles: { 
                number: { value: 90 }, 
                color: { value: "#ffffff" }, 
                shape: { type: "circle" }, 
                opacity: { value: 0.3 }, 
                size: { value: 2 }, 
                line_linked: { enable: true, distance: 150, color: "#ffffff", opacity: 0.2, width: 1 }, 
                move: { enable: true, speed: 2.5 } 
            }, 
            retina_detect: true 
        });
    </script>
</body>
</html>
