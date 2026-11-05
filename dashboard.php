<?php
session_start();

// Memeriksa session login
if (!isset($_SESSION["nama_pengguna"])) {
    header("Location: login.php");
    exit;
}

// Mengambil data pengguna
$namaPengguna = $_SESSION["nama_pengguna"];
$namaToko = $_SESSION["nama_toko"] ?? "Member DistribuMart";

// Menyiapkan variabel
$errors = [];
$hasil = null;

$namaProduk = "";
$jumlahInput = "";
$hargaInput = "";

$jumlah = 0;
$harga = 0;


// FUNCTION MENGHITUNG TOTAL HARGA
function hitungTotal($jumlah, $harga) {

    $total = $jumlah * $harga;

    if ($total > 100000) {
        $diskon = $total * 0.10;
    } else {
        $diskon = 0;
    }

    $totalBayar = $total - $diskon;

    return [
        "total" => $total,
        "diskon" => $diskon,
        "totalBayar" => $totalBayar
    ];
}


// PROSES FORM PEMESANAN
if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    ($_POST["aksi"] ?? "") == "hitung_pesanan"
) {

    $namaProduk = trim($_POST["nama_produk"] ?? "");
    $jumlahInput = trim($_POST["jumlah"] ?? "");
    $hargaInput = trim($_POST["harga"] ?? "");


    // VALIDASI NAMA PRODUK
    if (empty($namaProduk)) {
        $errors[] = "Nama produk wajib diisi.";
    }


    // VALIDASI JUMLAH
    if ($jumlahInput === "") {

        $errors[] = "Jumlah barang wajib diisi.";

    } elseif (
        filter_var($jumlahInput, FILTER_VALIDATE_INT) === false ||
        (int)$jumlahInput <= 0
    ) {

        $errors[] = "Jumlah harus lebih dari nol.";
    }


    // VALIDASI HARGA
    if ($hargaInput === "") {

        $errors[] = "Harga barang wajib diisi.";

    } elseif (
        !is_numeric($hargaInput) ||
        !is_finite((float)$hargaInput) ||
        (float)$hargaInput <= 0
    ) {

        $errors[] = "Harga harus lebih dari nol.";
    }


    // JIKA SEMUA DATA VALID
    if (empty($errors)) {

        $jumlah = (int)$jumlahInput;
        $harga = (float)$hargaInput;

        // Memanggil function
        $hasil = hitungTotal($jumlah, $harga);

    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DistribuMart</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <!-- DASHBOARD LAYOUT -->
    <div class="dashboard-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <!-- LOGO DISTRIBUMART -->
            <div class="sidebar-brand">
                <div class="logo">
                    <img src="Bahan/Logo_Distribumart.png" alt="Logo DistribuMart">
                    <div class="logo-text">
                        <span>Distribu<span class="mart">Mart</span></span>
                        <small>Harga Grosir</small>
                    </div>
                </div>
            </div>

            <!-- MENU SIDEBAR -->
            <ul class="sidebar-menu">

                <!-- Dashboard -->
                <li>
                    <a href="#" class="active">
                        <img src="Bahan/Logo_Dashboard.png" alt="Dashboard">
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Katalog Produk -->
                <li>
                    <a href="#katalog-section">
                        <img src="Bahan/logo_katalog.png" alt="Katalog Produk">
                        <span>Katalog Produk</span>
                    </a>
                </li>

                <!-- Pesanan Saya -->
                <li>
                    <a href="#pesanan-section">
                        <img src="Bahan/logo_pesanan.png" alt="Pesanan Saya">
                        <span>Pesanan Saya</span>
                    </a>
                </li>

                <!-- Riwayat Transaksi -->
                <li>
                    <a href="#riwayat-section">
                        <img src="Bahan/Logo_riwayat.png" alt="Riwayat Transaksi">
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>

                <!-- Pengaturan -->
                <li>
                    <a href="#pengaturan-section">
                        <img src="Bahan/Logo_Pengaturan.png" alt="Pengaturan">
                        <span>Pengaturan</span>
                    </a>
                </li>

                <!-- Keluar -->
                <li class="logout-menu">
                    <a href="login.php">
                        <img src="Bahan/Logo_Keluar.png" alt="Keluar">
                        <span>Keluar</span>
                    </a>
                </li>

            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOP BAR -->
            <div class="top-bar">
                <div>
                    <h2>
                        Selamat datang kembali,
                        <?= htmlspecialchars($namaPengguna) ?>!
                    </h2>
                    <p class="dashboard-subtitle">
                        <?= htmlspecialchars($namaToko) ?>
                        • Member DistribuMart
                    </p>
                </div>
                <a href="form_pemesanan.php" class="btn btn-primary">
                Form Pemesanan Produk
                </a>
            </div>

            <!-- STATISTIK -->
            <div class="stats-grid">

                <!-- Total Pesanan -->
                <div class="stat-card">
                    <h4>Total Pesanan</h4>
                    <p>24 Pesanan</p>
                </div>

                <!-- Pesanan Diproses -->
                <div class="stat-card">
                    <h4>Pesanan Diproses</h4>
                    <p>2 Pesanan</p>
                </div>

                <!-- Plafon Tempo -->
                <div class="stat-card">
                    <h4>Plafon Tempo Aktif</h4>
                    <p class="credit-limit">Rp 15.000.000</p>
                </div>

            </div>

            <!-- DATA 1 : KATALOG PRODUK -->
            <section id="katalog-section" class="dashboard-section">

                <h3 class="section-title">
                    <img src="Bahan/logo_katalog.png" alt="Katalog Produk">
                    <span>Data Katalog Produk</span>
                </h3>

                <!-- FORM PRODUK -->
                <div class="crud-box">
                    <h4>Form Input Produk Baru</h4>

                    <form action="#" method="POST">
                        <div class="form-grid">

                            <!-- Nama Produk -->
                            <div class="form-group">
                                <label for="prod_name">Nama Produk</label>
                                <input
                                    type="text"
                                    id="prod_name"
                                    class="form-control"
                                    placeholder="Misal: Beras Premium 5kg"
                                    required
                                >
                            </div>

                            <!-- Kategori -->
                            <div class="form-group">
                                <label for="prod_cat">Kategori Produk</label>
                                <select id="prod_cat" class="form-control">
                                    <option value="Sembako">Sembako</option>
                                    <option value="Minyak & Bumbu">Minyak & Bumbu</option>
                                    <option value="Makanan Instan">Makanan Instan</option>
                                    <option value="Minuman">Minuman</option>
                                </select>
                            </div>

                            <!-- Harga -->
                            <div class="form-group">
                                <label for="prod_price">Harga Grosir (Rp)</label>
                                <input
                                    type="number"
                                    id="prod_price"
                                    class="form-control"
                                    placeholder="68500"
                                    required
                                >
                            </div>

                            <!-- Stok -->
                            <div class="form-group">
                                <label for="prod_stock">Status Stok</label>
                                <input
                                    type="text"
                                    id="prod_stock"
                                    class="form-control"
                                    placeholder="Tersedia (>50)"
                                    required
                                >
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">
                            Simpan Produk
                        </button>
                    </form>
                </div>

                <!-- DATA PRODUK -->
                <div class="card-grid">

                    <!-- PRODUK 1 -->
                    <div class="product-card">
                        <span class="badge badge-success">Sembako</span>
                        <h4>Beras Premium Ramos 5 kg</h4>
                        <p class="product-stock">Tersedia (>50)</p>
                        <p class="product-price">Rp 68.500 / zak</p>

                        <div class="product-actions">
                            <a href="#" class="btn btn-outline btn-sm">Edit</a>
                            <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                        </div>
                    </div>

                    <!-- PRODUK 2 -->
                    <div class="product-card">
                        <span class="badge badge-success">Minyak & Bumbu</span>
                        <h4>Minyak Goreng Sania 2 L</h4>
                        <p class="product-stock">Tersedia (>100)</p>
                        <p class="product-price">Rp 32.000 / pouch</p>

                        <div class="product-actions">
                            <a href="#" class="btn btn-outline btn-sm">Edit</a>
                            <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                        </div>
                    </div>

                    <!-- PRODUK 3 -->
                    <div class="product-card">
                        <span class="badge badge-success">Sembako</span>
                        <h4>Gula Pasir Gulaku 1 kg</h4>
                        <p class="product-stock">Tersedia</p>
                        <p class="product-price">Rp 16.200 / bks</p>

                        <div class="product-actions">
                            <a href="#" class="btn btn-outline btn-sm">Edit</a>
                            <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                        </div>
                    </div>

                </div>
            </section>

            <!-- DATA 2 : PESANAN -->
            <section id="pesanan-section" class="dashboard-section">

                <h3 class="section-title">
                    <img src="Bahan/logo_pesanan.png" alt="Pesanan Saya">
                    <span>Data Pesanan Saya</span>
                </h3>

                <!-- FORM PESANAN BARU -->
<div class="crud-box">

    <h4>Form Input Pesanan Baru</h4>

    <p class="form-description">
        Silakan masukkan produk yang ingin dipesan.
    </p>

    <form action="dashboard.php#pesanan-section" method="POST">

        <input
            type="hidden"
            name="aksi"
            value="hitung_pesanan"
        >

        <div class="form-grid">

            <!-- Nama Pemesan -->
            <div class="form-group">
                <label for="nama_pemesan">
                    Nama Pemesan
                </label>

                <input
                    type="text"
                    id="nama_pemesan"
                    class="form-control"
                    value="<?= htmlspecialchars($namaPengguna) ?>"
                    readonly
                    required
                >
            </div>


            <!-- Nama Produk -->
            <div class="form-group">
                <label for="nama_produk">
                    Nama Produk
                </label>

                <input
                    type="text"
                    id="nama_produk"
                    name="nama_produk"
                    class="form-control"
                    value="<?= htmlspecialchars($namaProduk) ?>"
                    placeholder="Misal: Beras Premium 5kg"
                    required
                >
            </div>


            <!-- Jumlah -->
            <div class="form-group">
                <label for="jumlah">
                    Jumlah Barang
                </label>

                <input
                    type="number"
                    id="jumlah"
                    name="jumlah"
                    class="form-control"
                    value="<?= htmlspecialchars($jumlahInput) ?>"
                    placeholder="Masukkan jumlah"
                    min="1"
                    step="1"
                    required
                >
            </div>


            <!-- Harga -->
            <div class="form-group">
                <label for="harga">
                    Harga Satuan (Rp)
                </label>

                <input
                    type="number"
                    id="harga"
                    name="harga"
                    class="form-control"
                    value="<?= htmlspecialchars($hargaInput) ?>"
                    placeholder="Contoh: 50000"
                    min="0.01"
                    step="any"
                    required
                >
            </div>

        </div>

        <button type="submit" class="btn btn-primary">
            Hitung Total Harga
        </button>

    </form>

</div>

                            <!-- Nomor Pesanan -->
                            <div class="form-group">
                                <label for="order_code">Nomor Pesanan</label>
                                <input
                                    type="text"
                                    id="order_code"
                                    class="form-control"
                                    value="#ORD-2026-0090"
                                    readonly
                                >
                            </div>

                            <!-- Item Pesanan -->
                            <div class="form-group">
                                <label for="order_items">Item Pesanan</label>
                                <input
                                    type="text"
                                    id="order_items"
                                    class="form-control"
                                    placeholder="Misal: Minyak Goreng 2L (2 ktn)"
                                    required
                                >
                            </div>

                            <!-- Total Harga -->
                            <div class="form-group">
                                <label for="order_total">Total Harga (Rp)</label>
                                <input
                                    type="number"
                                    id="order_total"
                                    class="form-control"
                                    placeholder="406500"
                                    required
                                >
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="order_status">Status Pesanan</label>
                                <select id="order_status" class="form-control">
                                    <option value="Diproses">Diproses</option>
                                    <option value="Dikirim">Dikirim</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">
                            Tambah Pesanan
                        </button>
                    </form>
                </div>

                <!-- TABEL PESANAN -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nomor Pesanan</th>
                                <th>Tanggal</th>
                                <th>Item Pesanan</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <!-- PESANAN 1 -->
                            <tr>
                                <td><strong>#ORD-2026-0089</strong></td>
                                <td>24 Okt 2026, 09:30</td>
                                <td>Minyak Goreng 2L (2 ktn), Beras 5kg (5 zak)</td>
                                <td>Rp 406.500</td>
                                <td>
                                    <span class="badge badge-warning">Diproses</span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-outline btn-sm">Edit</a>
                                    <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>

                            <!-- PESANAN 2 -->
                            <tr>
                                <td><strong>#ORD-2026-0088</strong></td>
                                <td>22 Okt 2026, 14:15</td>
                                <td>Indomie Goreng (3 dus), Gula 1kg (20 bks)</td>
                                <td>Rp 660.000</td>
                                <td>
                                    <span class="badge badge-warning">Dikirim</span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-outline btn-sm">Edit</a>
                                    <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>

                            <!-- PESANAN 3 -->
                            <tr>
                                <td><strong>#ORD-2026-0085</strong></td>
                                <td>18 Okt 2026, 11:00</td>
                                <td>Kopi Sachet (5 renceng), Teh Celup (10 box)</td>
                                <td>Rp 185.000</td>
                                <td>
                                    <span class="badge badge-success">Selesai</span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-outline btn-sm">Edit</a>
                                    <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </section>

            <!-- RIWAYAT TRANSAKSI -->
            <section id="riwayat-section" class="dashboard-section">

                <h3 class="section-title">
                    <img src="Bahan/Logo_riwayat.png" alt="Riwayat Transaksi">
                    <span>Riwayat Transaksi</span>
                </h3>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Tanggal</th>
                                <th>Jenis Transaksi</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td><strong>#TRX-2026-0089</strong></td>
                                <td>24 Okt 2026</td>
                                <td>Pembelian Produk Grosir</td>
                                <td>Rp 406.500</td>
                                <td>
                                    <span class="badge badge-warning">Diproses</span>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>#TRX-2026-0088</strong></td>
                                <td>22 Okt 2026</td>
                                <td>Pembelian Produk Grosir</td>
                                <td>Rp 660.000</td>
                                <td>
                                    <span class="badge badge-warning">Dikirim</span>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>#TRX-2026-0085</strong></td>
                                <td>18 Okt 2026</td>
                                <td>Pembelian Produk Grosir</td>
                                <td>Rp 185.000</td>
                                <td>
                                    <span class="badge badge-success">Selesai</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </section>

            <!-- PENGATURAN -->
            <section id="pengaturan-section" class="dashboard-section">

                <h3 class="section-title">
                    <img src="Bahan/Logo_Pengaturan.png" alt="Pengaturan">
                    <span>Pengaturan Akun</span>
                </h3>

                <div class="crud-box">
                    <h4>Informasi Toko</h4>

                    <div class="form-grid">

                        <!-- Nama Toko -->
                        <div class="form-group">
                            <label for="store_name">Nama Toko</label>
                            <input
                                type="text"
                                id="store_name"
                                class="form-control"
                                value="Toko Berkah Jaya"
                            >
                        </div>

                        <!-- Nama Pemilik -->
                        <div class="form-group">
                            <label for="owner_name">Nama Pemilik</label>
                            <input
                                type="text"
                                id="owner_name"
                                class="form-control"
                                value="Panji"
                            >
                        </div>

                        <!-- WhatsApp -->
                        <div class="form-group">
                            <label for="store_phone">Nomor WhatsApp</label>
                            <input
                                type="tel"
                                id="store_phone"
                                class="form-control"
                                value="0838-3484-0870"
                            >
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="store_email">Email</label>
                            <input
                                type="email"
                                id="store_email"
                                class="form-control"
                                value="pusat@distribumart.id"
                            >
                        </div>

                    </div>

                    <button type="button" class="btn btn-primary btn-sm">
                        Simpan Pengaturan
                    </button>
                </div>

            </section>

        </main>
    </div>

</body>
</html>