<?php
// Buat folder sementara untuk memproses tampilan Blade karena Vercel read-only
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0777, true);
}

require __DIR__ . '/../public/index.php';