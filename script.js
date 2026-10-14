// Mengambil elemen HTML
const searchButton = document.getElementById("searchButton");
const catalogButton = document.getElementById("catalogButton");
const aboutLink = document.getElementById("aboutLink");
const toast = document.getElementById("toast");

// Variabel untuk mengatur waktu Toast
let toastTimer;

// Fungsi untuk menampilkan Toast / Snackbar
function showToast(message) {
    clearTimeout(toastTimer);

    toast.textContent = message;
    toast.classList.add("show");

    toastTimer = setTimeout(function () {
        toast.classList.remove("show");
    }, 3000);
}

// DOM 1: Interaksi tombol Cari Produk
searchButton.addEventListener("click", function () {
    showToast("Fitur pencarian produk akan segera tersedia.");
});

// DOM 2: Interaksi tombol Lihat Katalog
catalogButton.addEventListener("click", function () {
    showToast("Katalog produk DistribuMart sedang disiapkan.");
});

// DOM 3: Interaksi menu Tentang Kami
aboutLink.addEventListener("click", function (event) {
    event.preventDefault();

    showToast(
        "DistribuMart membantu warung dan UMKM berbelanja produk grosir."
    );
});
