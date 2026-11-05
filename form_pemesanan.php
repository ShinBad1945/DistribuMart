<?php

// FUNCTION UNTUK MENGHITUNG TOTAL HARGA
function hitungTotal($jumlah, $harga) {

    $total = $jumlah * $harga;
    $diskon = 0;

    // Diskon 10% jika total di atas Rp100.000
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


// MENYIAPKAN VARIABEL
$errors = [];
$hasil = null;

$namaPemesan = "";
$namaProduk = "";
$jumlahInput = "";
$hargaInput = "";


// PROSES FORM KETIKA DIKIRIM
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $namaPemesan = trim($_POST["nama_pemesan"] ?? "");
    $namaProduk = trim($_POST["nama_produk"] ?? "");
    $jumlahInput = $_POST["jumlah"] ?? "";
    $hargaInput = $_POST["harga"] ?? "";


    // VALIDASI NAMA PEMESAN
    if (empty($namaPemesan)) {

        $errors[] = "Nama pemesan wajib diisi.";

    }


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

        $errors[] = "Jumlah barang harus berupa bilangan bulat lebih dari nol.";

    }


    // VALIDASI HARGA
    if ($hargaInput === "") {

        $errors[] = "Harga barang wajib diisi.";

    } elseif (
        !is_numeric($hargaInput) ||
        (float)$hargaInput <= 0
    ) {

        $errors[] = "Harga barang tidak boleh nol atau negatif.";

    }


    // JIKA SEMUA VALIDASI BERHASIL
    if (empty($errors)) {

        $jumlah = (int)$jumlahInput;
        $harga = (float)$hargaInput;

        // Memanggil function hitungTotal()
        $hasil = hitungTotal($jumlah, $harga);

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Form Pemesanan Produk - DistribuMart</title>

    <link rel="stylesheet" href="pemesanan.css">
</head>

<body>

    <main class="order-container">

        <div class="order-card">

            <h2>Form Pemesanan Produk</h2>

            <p class="description">
                Silakan masukkan data pemesanan produk DistribuMart.
            </p>

            <!-- FORM INPUT -->
            <form action="form_pemesanan.php" method="POST">

                <div class="form-group">

                    <label for="nama_pemesan">
                        Nama Pemesan
                    </label>

                    <input
                        type="text"
                        id="nama_pemesan"
                        name="nama_pemesan"
                        value="<?= htmlspecialchars($namaPemesan) ?>"
                        placeholder="Masukkan nama pemesan"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="nama_produk">
                        Nama Produk
                    </label>

                    <input
                        type="text"
                        id="nama_produk"
                        name="nama_produk"
                        value="<?= htmlspecialchars($namaProduk) ?>"
                        placeholder="Contoh: Beras Premium 5 Kg"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="jumlah">
                        Jumlah Barang
                    </label>

                    <input
                        type="number"
                        id="jumlah"
                        name="jumlah"
                        value="<?= htmlspecialchars($jumlahInput) ?>"
                        placeholder="Masukkan jumlah barang"
                        min="1"
                        step="1"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="harga">
                        Harga Satuan (Rp)
                    </label>

                    <input
                        type="number"
                        id="harga"
                        name="harga"
                        value="<?= htmlspecialchars($hargaInput) ?>"
                        placeholder="Contoh: 50000"
                        min="1"
                        step="any"
                        required
                    >

                </div>


                <button type="submit" class="btn-submit">
                    Hitung Total Harga
                </button>

            </form>


            <!-- MENAMPILKAN ERROR -->
            <?php if (!empty($errors)) : ?>

                <div class="error-message">

                    <strong>Terjadi kesalahan:</strong>

                    <ul>
                        <?php foreach ($errors as $error) : ?>

                            <li><?= htmlspecialchars($error) ?></li>

                        <?php endforeach; ?>
                    </ul>

                </div>

            <?php endif; ?>


            <!-- MENAMPILKAN HASIL -->
            <?php if ($hasil !== null) : ?>

                <div class="result-container">

                    <h3>Hasil Perhitungan Pemesanan</h3>

                    <table>

                        <tr>
                            <th>Nama Pemesan</th>
                            <td>
                                <?= htmlspecialchars($namaPemesan) ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Nama Produk</th>
                            <td>
                                <?= htmlspecialchars($namaProduk) ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Jumlah Barang</th>
                            <td>
                                <?= number_format($jumlah) ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Harga Satuan</th>
                            <td>
                                Rp <?= number_format($harga, 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Total Harga</th>
                            <td>
                                Rp <?= number_format($hasil["total"], 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Diskon (10%)</th>
                            <td>
                                Rp <?= number_format($hasil["diskon"], 0, ',', '.') ?>
                            </td>
                        </tr>

                        <tr class="total-row">
                            <th>Total Pembayaran</th>
                            <td>
                                Rp <?= number_format($hasil["totalBayar"], 0, ',', '.') ?>
                            </td>
                        </tr>

                    </table>

                </div>

            <?php endif; ?>

            <a href="dashboard.php" class="back-link">
                ← Kembali ke Dashboard
            </a>

        </div>

    </main>

</body>
</html>