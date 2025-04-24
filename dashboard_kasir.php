<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Kasir Waroeng Makan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="stylesheet.css">
  
  <style>
    body {
      background-color: #FFFFFF; 
      color: #333333; 
    }

    .btn-confirm, .btn-pay, .btn-print {
      background-color: #4CAF50; 
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .btn-confirm:hover, .btn-pay:hover, .btn-print:hover {
      background-color: #388E3C; 
    }

    .customer-tabs button.active {
      background-color: #388E3C; 
    }

    .order-section {
      background-color: #F1F8E9; 
      color: black; 
    }

    table th, table td {
      border: 1px solid #4CAF50;
    }

    header {
      background-color: #66BB6A; 
      color: white;
      padding: 20px;
      text-align: center;
      border-radius: 5px;
    }
    .customer-tabs button {
      background-color: #81C784; 
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .customer-tabs button:hover {
      background-color: #66BB6A;
    }

    .customer-tabs button.active {
      background-color: #388E3C;
    }
  </style>
</head>
<body>
  <header class="mb-6">
    <h2 class="text-xl font-bold">Dashboard Kasir - Waroeng Makan Kartika Sari (Surabaya)</h2>
  </header>
  
  <div class="container mx-auto px-4">
    <div class="customer-tabs mb-6">
      <?php
      $file = "pesanan.json";
      $tables = [];
      if (file_exists($file)) {
          $data = json_decode(file_get_contents($file), true);
          foreach ($data as $pesanan) {
              if (!in_array($pesanan['meja'], $tables)) {
                  $tables[] = $pesanan['meja'];
              }
          }
      }
      
      
      sort($tables);
      foreach ($tables as $index => $table) {
          $active = $index === 0 ? 'active' : '';
          echo "<button class='{$active}' onclick='switchCustomer(\"{$table}\")'>Meja {$table}</button>";
      }
      if (empty($tables)) {
          echo "<button class='active' onclick='switchCustomer(\"1\")'>Meja 1</button>";
          echo "<button onclick='switchCustomer(\"2\")'>Meja 2</button>";
      }
      ?>
    </div>

    <?php
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        
        
        $ordersByTable = [];
        foreach ($data as $pesanan) {
            $table = $pesanan['meja'];
            if (!isset($ordersByTable[$table])) {
                $ordersByTable[$table] = [];
            }
            $ordersByTable[$table][] = $pesanan;
        }
        
        foreach ($ordersByTable as $table => $orders) {
            $display = $table === ($tables[0] ?? 1) ? 'block' : 'none';
            echo "<div id='meja-{$table}' class='order-section p-6 rounded shadow mb-6' style='display: {$display}'>";
            echo "<h3 class='text-lg font-bold mb-4'>Pesanan - Meja {$table}</h3>";
            
            echo "<div class='overflow-x-auto'>";
            echo "<table>";
            echo "<thead class='bg-gray-100'>";
            echo "<tr><th>No</th><th>Nama Makanan</th><th>Harga</th><th>Waktu</th><th>Status</th></tr>";
            echo "</thead>";
            echo "<tbody>";
            
            $total = 0;
            $no = 1;
            foreach ($orders as $pesanan) {
                foreach ($pesanan['pesanan'] as $item) {
                    echo "<tr>";
                    echo "<td>{$no}</td>";
                    echo "<td>{$item['nama']}</td>";
                    echo "<td>Rp" . number_format($item['harga'], 0, ',', '.') . "</td>";
                    echo "<td>{$pesanan['waktu']}</td>";
                    echo "<td>{$pesanan['status']}</td>";
                    echo "</tr>";
                    
                    $total += $item['harga'];
                    $no++;
                }
            }
            
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            
            echo "<h3 class='text-lg font-bold mt-4'>Total Belanja: Rp " . number_format($total, 0, ',', '.') . "</h3>";
            
            // Hidden div for printing
            echo "<div id='print-content-{$table}' class='print-section hidden'>";
            echo "<h2 class='text-center font-bold text-xl mb-4'>Waroeng Makan Kartika Sari</h2>";
            echo "<h3 class='text-center mb-4'>Struk Pembayaran - Meja {$table}</h3>";
            echo "<p class='text-center mb-2'>Tanggal: " . date('d/m/Y H:i') . "</p>";
            echo "<table class='w-full mb-4'>";
            echo "<thead><tr><th class='border-b'>Item</th><th class='border-b'>Harga</th></tr></thead>";
            echo "<tbody>";
            
            foreach ($orders as $pesanan) {
                foreach ($pesanan['pesanan'] as $item) {
                    echo "<tr>";
                    echo "<td class='py-2'>{$item['nama']}</td>";
                    echo "<td class='py-2'>Rp" . number_format($item['harga'], 0, ',', '.') . "</td>";
                    echo "</tr>";
                }
            }
            
            echo "<tr><td class='border-t font-bold'>Total</td><td class='border-t font-bold'>Rp" . number_format($total, 0, ',', '.') . "</td></tr>";
            echo "</tbody></table>";
            echo "<p class='text-center mt-8'>Terima kasih telah berkunjung</p>";
            echo "</div>";
            
            echo "</div>";
        }
    } else {
        // Default view when no orders exist
        echo "<div id='meja-1' class='order-section p-6 rounded shadow mb-6'>";
        echo "<h3 class='text-lg font-bold mb-4'>Pesanan - Meja 1</h3>";
        echo "<p>Belum ada pesanan</p>";
        echo "</div>";
        
        echo "<div id='meja-2' class='order-section p-6 rounded shadow mb-6' style='display:none'>";
        echo "<h3 class='text-lg font-bold mb-4'>Pesanan - Meja 2</h3>";
        echo "<p>Belum ada pesanan</p>";
        echo "</div>";
    }
    ?>

    <div class="actions mt-6">
      <button class="btn-confirm" onclick="confirmOrder()">Konfirmasi Pesanan</button>
      <button class="btn-pay" onclick="processPayment()">Proses Pembayaran</button>
      <button class="btn-print" onclick="printReceipt()">Cetak Struk</button>
    </div>
  </div>

  <script>
    function switchCustomer(meja) {
      // Hide all order sections
      document.querySelectorAll('.order-section').forEach(el => {
        el.style.display = 'none';
      });
      
      // Show selected table
      const selectedSection = document.getElementById(`meja-${meja}`);
      if (selectedSection) {
        selectedSection.style.display = 'block';
      }
      
      // Update active tab
      document.querySelectorAll('.customer-tabs button').forEach(btn => {
        btn.classList.remove('active');
        if (btn.textContent === `Meja ${meja}`) {
          btn.classList.add('active');
        }
      });
    }

    function confirmOrder() {
      const activeButton = document.querySelector('.customer-tabs button.active');
      if (activeButton) {
        const meja = activeButton.textContent.replace('Meja ', '');
        alert(`Pesanan untuk Meja ${meja} dikonfirmasi!`);
      }
    }

    function processPayment() {
      const activeButton = document.querySelector('.customer-tabs button.active');
      if (activeButton) {
        const meja = activeButton.textContent.replace('Meja ', '');
        alert(`Pembayaran untuk Meja ${meja} berhasil diproses!`);
      }
    }

    function printReceipt() {
      const activeButton = document.querySelector('.customer-tabs button.active');
      if (activeButton) {
        const meja = activeButton.textContent.replace('Meja ', '');
        const printContent = document.getElementById(`print-content-${meja}`);
        
        if (printContent) {
          const printWindow = window.open('', '_blank');
          printWindow.document.write(`
            <html>
              <head>
                <title>Struk Meja ${meja}</title>
                <style>
                  body { font-family: Arial; padding: 20px; }
                  table { width: 100%; border-collapse: collapse; }
                  th, td { padding: 8px; text-align: left; }
                  .border-b { border-bottom: 1px solid #000; }
                  .border-t { border-top: 1px solid #000; }
                  .text-center { text-align: center; }
                  .font-bold { font-weight: bold; }
                </style>
              </head>
              <body>
                ${printContent.innerHTML}
              </body>
            </html>
          `);
          printWindow.document.close();
          printWindow.focus();
          setTimeout(() => {
            printWindow.print();
            printWindow.close();
          }, 500);
        }
      }
    }
    const firstTab = document.querySelector('.customer-tabs button');
    if (firstTab) {
        const firstMeja = firstTab.textContent.replace('Meja ', '');
        switchCustomer(firstMeja);
    }
  </script>
</body>
</html>
