<?php
header("Content-Type: application/json");

try {
    // Pastikan tidak ada output sebelum ini
    $json = file_get_contents("php://input");
    if ($json === false) {
        throw new Exception("Gagal membaca input");
    }

    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON invalid: " . json_last_error_msg());
    }

    if (!$data || !isset($data['meja']) || !isset($data['pesanan'])) {
        throw new Exception("Data tidak valid");
    }
    
    date_default_timezone_set('Asia/Jakarta');
    $data['waktu'] = date("Y-m-d H:i:s");
    $data['status'] = "Menunggu";

    $filename = "pesanan.json";
    $existing = [];
    
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        if ($content === false) {
            throw new Exception("Gagal membaca file pesanan");
        }
        $existing = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $existing = []; // Reset jika file corrupt
        }
    }

    $existing[] = $data;

    $result = file_put_contents(
        $filename, 
        json_encode($existing, JSON_PRETTY_PRINT), 
        LOCK_EX // Lock file saat menulis
    );

    if ($result === false) {
        throw new Exception("Gagal menyimpan data");
    }

    echo json_encode(["status" => "success"]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

exit;