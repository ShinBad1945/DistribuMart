<?php

session_start();

// Menghapus seluruh data session
session_unset();

// Menghancurkan session
session_destroy();

// Kembali ke halaman utama
header("Location: index.html");
exit;

?>