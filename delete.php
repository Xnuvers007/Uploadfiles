<?php
$targetDir = "uploads/";
$file = isset($_GET['file']) ? $_GET['file'] : null;
$filePath = $targetDir . $file;

// 1. Validasi Input
if ($file === null || !is_string($file) || strpos($file, '/') !== false || strpos($file, '\\') !== false) {
    echo "Invalid file name.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($file)) {
    // 2. Otorisasi
    // Implement your authorization mechanism here
    // Check if the user is authorized to delete the file

    // 3. Pembatasan Akses
    // Implement your authentication mechanism here
    // Check if the user is authenticated and has the necessary permissions

    if (file_exists($filePath)) {
        // 4. Pemrosesan Kesalahan
        if (unlink($filePath)) {
            echo "File $file has been deleted.";
        } else {
            echo "Failed to delete file.";
        }
    } else {
        echo "File not found.";
    }
}
?>
