<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meja = $_POST['meja'] ?? '';

    if (empty($meja)) {
        echo json_encode(['status' => 'error', 'message' => 'Meja tidak ditemukan']);
        exit;
    }

    $file = 'pesanan.json';

    if (!file_exists($file)) {
        echo json_encode(['status' => 'error', 'message' => 'File pesanan.json tidak ditemukan']);
        exit;
    }

    $data = json_decode(file_get_contents($file), true);

    if (!is_array($data)) {
        echo json_encode(['status' => 'error', 'message' => 'Format data salah']);
        exit;
    }

    foreach ($data as &$pesanan) {
        if (trim((string)$pesanan['meja']) === trim((string)$meja)) {
            $pesanan['status'] = 'dikonfirmasi';
        }
    }
    unset($pesanan); // Penting supaya tidak ada bug reference!

    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

    echo json_encode(['status' => 'success']);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak valid']);
    exit;
}
?>
