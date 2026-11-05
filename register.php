<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $namaPemilik = trim($_POST["owner_name"] ?? "");
    $namaToko = trim($_POST["store_name"] ?? "");
    $email = trim($_POST["reg_email"] ?? "");
    $whatsapp = trim($_POST["whatsapp"] ?? "");
    $password = $_POST["reg_password"] ?? "";
    $konfirmasi = $_POST["confirm_password"] ?? "";

    if (
        empty($namaPemilik) ||
        empty($namaToko) ||
        empty($email) ||
        empty($whatsapp) ||
        empty($password) ||
        empty($konfirmasi)
    ) {

        $error = "Semua field wajib diisi!";

    } elseif ($password != $konfirmasi) {

        $error = "Password dan konfirmasi password tidak sama!";

    } elseif (strlen($password) < 8) {

        $error = "Password minimal 8 karakter!";

    } else {

        $_SESSION["nama_pengguna"] = $namaPemilik;
        $_SESSION["nama_toko"] = $namaToko;
        $_SESSION["email"] = $email;
        $_SESSION["whatsapp"] = $whatsapp;

        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - DistribuMart</title>
    <link rel="stylesheet" href="register.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo">
            <img src="Bahan/Logo_Distribumart.png" alt="Logo DistribuMart">
            <div class="logo-text">
                <span>Distribu<span class="mart">Mart</span></span>
                <small>Harga Grosir</small>
            </div>
        </div>

        <div class="auth-buttons">
            <a href="login.php" class="btn btn-outline">Masuk</a>
        </div>
    </nav>

    <!-- REGISTER FORM -->
    <main class="auth-container register-container">

        <!-- HEADER REGISTER -->
        <div class="auth-header">
            <h2>Buat Akun DistribuMart</h2>
            <p class="register-description">
                Pendaftaran 100% gratis & dapatkan akses ke harga distributor
            </p>
        </div>

        <!-- FORM REGISTER -->
        <form action="register.php" method="POST">
            <?php if (!empty($error)) : ?>
            <p><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <!-- NAMA PEMILIK & NAMA TOKO -->
            <div class="register-form-grid">
                <div class="form-group">
                    <label for="owner_name">Nama Lengkap Pemilik *</label>
                    <input
                        type="text"
                        id="owner_name"
                        name="owner_name"
                        class="form-control"
                        placeholder="contoh: Budi Santoso"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="store_name">Nama Toko / Warung *</label>
                    <input
                        type="text"
                        id="store_name"
                        name="store_name"
                        class="form-control"
                        placeholder="contoh: Toko Berkah Jaya"
                        required
                    >
                </div>
            </div>

            <!-- EMAIL & WHATSAPP -->
            <div class="register-form-grid">
                <div class="form-group">
                    <label for="reg_email">Alamat Email *</label>
                    <input
                        type="email"
                        id="reg_email"
                        name="reg_email"
                        class="form-control"
                        placeholder="budi@warungberkah.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="whatsapp">Nomor WhatsApp *</label>
                    <input
                        type="tel"
                        id="whatsapp"
                        name="whatsapp"
                        class="form-control"
                        placeholder="08123456789"
                        required
                    >
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="register-form-grid">
                <div class="form-group">
                    <label for="reg_password">Password *</label>
                    <input
                        type="password"
                        id="reg_password"
                        name="reg_password"
                        class="form-control"
                        placeholder="Minimal 8 karakter"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password *</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="form-control"
                        placeholder="Ulangi password"
                        required
                    >
                </div>
            </div>

            <!-- TOMBOL REGISTER -->
            <button type="submit" class="btn btn-primary register-button">
                Daftar Sekarang - Gratis →
            </button>
        </form>

        <!-- LINK LOGIN -->
        <p class="login-link">
            Sudah punya akun warung?
            <a href="login.html">Masuk di sini</a>
        </p>

    </main>

</body>
</html>
