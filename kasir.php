<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $index = $_POST['index'];
  $file = 'pesanan.json';
  if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
    array_splice($data, $index, 1);
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
  }
}
header("Location: kasir.php");
exit;
?>
