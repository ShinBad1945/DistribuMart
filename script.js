
/* ==================================================
   DISTRIBUMART - JAVASCRIPT
   Popup, Event, Web Storage, Promise, Async, Fetch
   ================================================== */

// Mengambil elemen HTML yang digunakan.
const searchButton = document.getElementById("searchButton");
const catalogButton = document.getElementById("catalogButton");
const aboutLink = document.getElementById("aboutLink");
const searchInput = document.getElementById("productSearch");
const productResults = document.getElementById("productResults");
const toast = document.getElementById("toast");

// Variabel untuk mengatur waktu Toast.
let toastTimer;

// Memeriksa apakah elemen utama tersedia.
if (
    searchButton &&
    catalogButton &&
    aboutLink &&
    searchInput &&
    productResults &&
    toast
) {
    // Menampilkan Toast / Snackbar selama 3 detik.
    function showToast(message) {
        clearTimeout(toastTimer);

        toast.textContent = message;
        toast.classList.add("show");

        toastTimer = setTimeout(function () {
            toast.classList.remove("show");
        }, 3000);
    }

    // ==============================================
    // 1. WEB STORAGE: menyimpan kata kunci pencarian.
    // ==============================================

    // Mengambil kata kunci terakhir yang tersimpan.
    searchInput.value = localStorage.getItem("kataKunciDistribuMart") || "";

    // Event INPUT: menyimpan kata kunci setiap kali berubah.
    searchInput.addEventListener("input", function () {
        localStorage.setItem(
            "kataKunciDistribuMart",
            searchInput.value
        );
    });

    // ==============================================
    // 2. PROMISE DAN FETCH: mengambil data produk.
    // ==============================================

    // Fetch mengembalikan Promise yang diproses berurutan.
    function fetchProducts(keyword = "") {
    return fetch("./produk.json")
        .then(function (response) {
            if (!response.ok) {
                throw new Error("Gagal membaca data produk.");
            }

            return response.json();
        })
        .then(function (products) {
            // Menyaring produk sesuai kata kunci pencarian.
            const searchTerm = keyword.trim().toLowerCase();

            if (searchTerm === "") {
                return products;
            }

            return products.filter(function (product) {
                return product.title.toLowerCase().includes(searchTerm);
            });
        });
}
    // ==============================================
    // 3. ASYNCHRONOUS: mencari dan menampilkan produk.
    // ==============================================

    async function performSearch(keyword) {
        const searchTerm = keyword.trim();

        // POPUP BOX 1: alert jika pencarian kosong.
        if (searchTerm === "") {
            alert("Silakan masukkan nama produk terlebih dahulu!");
            searchInput.focus();
            return;
        }

        // Menyimpan kata kunci pencarian.
        localStorage.setItem("kataKunciDistribuMart", searchTerm);

        // Memberi tahu pengguna bahwa proses dimulai.
        productResults.textContent = "Sedang mengambil data produk...";
        showToast("Sedang mencari produk...");

        try {
            // ASYNC/AWAIT menunggu Promise dari Fetch.
            const products = await fetchProducts(searchTerm);

            // Menghapus hasil pencarian sebelumnya.
            productResults.replaceChildren();

            // Jika produk tidak ditemukan.
            if (products.length === 0) {
                productResults.textContent =
                    "Produk tidak ditemukan. Coba kata kunci lain.";
                showToast("Produk tidak ditemukan.");
                return;
            }

            // Membuat judul hasil pencarian.
            const heading = document.createElement("h2");
            heading.textContent = "Hasil Pencarian Produk";
            productResults.appendChild(heading);

            // Menampilkan setiap produk dengan DOM.
            products.forEach(function (product) {
                const card = document.createElement("article");
                card.className = "product-card";

                const name = document.createElement("h3");
                name.textContent = product.title;

                const price = document.createElement("p");
                price.textContent =
                    "Harga contoh: " +
                   new Intl.NumberFormat("id-ID", {
                    style: "currency",
                     currency: "IDR",
                      maximumFractionDigits: 0
                        }).format(product.price);
                card.append(name, price);
                productResults.appendChild(card);
            });

            showToast("Data produk berhasil dimuat.");
        } catch (error) {
            // Menangani kegagalan jaringan atau API.
            productResults.textContent =
                "Data produk gagal dimuat. Periksa koneksi internet.";
            showToast("Terjadi kesalahan saat mengambil data.");
            console.error("Kesalahan Fetch:", error);
        }
    }

    // ==============================================
    // 4. EVENT CLICK: tombol Cari Produk.
    // ==============================================

    searchButton.addEventListener("click", function () {
        performSearch(searchInput.value);
    });

    // ==============================================
    // 5. EVENT KEYDOWN: mencari dengan tombol Enter.
    // ==============================================

    searchInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            performSearch(searchInput.value);
        }
    });

    // ==============================================
    // 6. EVENT MOUSEOVER: interaksi tombol katalog.
    // ==============================================

    catalogButton.addEventListener("mouseover", function () {
        catalogButton.title = "Klik untuk melihat katalog produk";
    });

    // ==============================================
    // 7. EVENT CLICK + POPUP BOX 2: katalog produk.
    // ==============================================

    catalogButton.addEventListener("click", async function () {
        // POPUP BOX 2: meminta konfirmasi pengguna.
        const lanjut = confirm(
            "Apakah kamu ingin melihat katalog produk?"
        );

        if (!lanjut) {
            showToast("Kamu membatalkan pembukaan katalog.");
            return;
        }

        // Mengambil produk kategori groceries menggunakan Fetch.
        productResults.textContent = "Memuat katalog produk...";

        try {
            const products = await fetchProducts("");

            productResults.replaceChildren();

            if (products.length === 0) {
                productResults.textContent =
                    "Katalog belum memiliki produk.";
                return;
            }

            const heading = document.createElement("h2");
            heading.textContent = "Katalog Produk";
            productResults.appendChild(heading);

            products.forEach(function (product) {
                const card = document.createElement("article");
                card.className = "product-card";

                const name = document.createElement("h3");
                name.textContent = product.title;

                const price = document.createElement("p");
                price.textContent =
                    "Harga contoh: " +
                    new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "USD"
                    }).format(product.price);

                card.append(name, price);
                productResults.appendChild(card);
            });

            productResults.scrollIntoView({ behavior: "smooth" });
            showToast("Katalog berhasil dimuat.");
        } catch (error) {
            productResults.textContent =
                "Katalog gagal dimuat. Periksa koneksi internet.";
            showToast("Gagal memuat katalog.");
            console.error("Kesalahan Fetch:", error);
        }
    });

    // ==============================================
    // 8. EVENT CLICK: menu Tentang Kami.
    // ==============================================

    aboutLink.addEventListener("click", function (event) {
        event.preventDefault();

        showToast(
            "DistribuMart membantu warung dan UMKM berbelanja produk grosir."
        );
    });
}

/* ==========================================
   EVENT: FILTER KATEGORI PRODUK
   ========================================== */

const categoryButtons = document.querySelectorAll(".category-btn");
const productCards = document.querySelectorAll(".product-card");

// Event click untuk menyaring produk berdasarkan kategori.
categoryButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        const selectedCategory = button.dataset.category;

        // Mengubah tombol aktif.
        categoryButtons.forEach(function (item) {
            item.classList.remove("active");
        });

        button.classList.add("active");

        // Menampilkan produk yang sesuai kategori.
        productCards.forEach(function (card) {
            const productCategory = card.dataset.category;

            if (
                selectedCategory === "semua" ||
                selectedCategory === productCategory
            ) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });
});


/* ==========================================
   EVENT: TOMBOL LIHAT DETAIL PRODUK
   ========================================== */

// Event click untuk memberikan informasi produk.
const detailButtons = document.querySelectorAll(".detail-button");

detailButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        const productName = button.dataset.product;

        // Menggunakan Toast yang sudah tersedia.
        if (typeof showToast === "function") {
            showToast(
                "Produk " + productName +
                " dipilih. Silakan masuk untuk melanjutkan."
            );
        } else {
            alert(
                "Produk " + productName +
                " dipilih. Silakan masuk untuk melanjutkan."
            );
        }
    });
});
