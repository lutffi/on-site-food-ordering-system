<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meja = $_POST['meja'] ?? '';

    if (empty($meja)) {
        echo json_encode(['status' => 'error', 'message' => 'Meja tidak ditemukan']);
        exit;
    }

    $file = 'pesanan.json';

    if (!file_exists($file)) {
        echo json_encode(['status' => 'error', 'message' => 'Data pesanan tidak ditemukan']);
        exit;
    }

    $data = json_decode(file_get_contents($file), true);

    if (!is_array($data)) {
        echo json_encode(['status' => 'error', 'message' => 'Format data salah']);
        exit;
    }

    $data = array_filter($data, function($pesanan) use ($meja) {
      return trim((string)$pesanan['meja']) !== trim((string)$meja);
  });
  

    file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT));

    echo json_encode(['status' => 'success']);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak valid']);
    exit;
}
?>
