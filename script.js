// At the very beginning of your script.js
window.pesanan = [];
window.total = 0;

const urlParams = new URLSearchParams(window.location.search);
const meja = urlParams.get('meja') || '?';
document.getElementById('nomorMeja').innerText = meja;

function kirimPesanan() {
  if (pesanan.length === 0) {
    alert("Belum ada pesanan.");
    return;
  }

  const data = {
    meja: meja || "1", // Default value jika meja undefined
    pesanan,
    total
  };

  fetch("submit_order.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(data)
  })
    .then(async (response) => {
      if (!response.ok) {
        const err = await response.text();
        throw new Error(err);
      }
      return response.json();
    })
    .then(res => {
      if (res.status === "success") {
        alert(`Pesanan meja ${meja} berhasil dikirim!`);
        pesanan = [];
        total = 0;
        renderPesanan();
      } else {
        alert(res.message || "Gagal mengirim pesanan!");
      }
    })
    .catch(err => {
      console.error("Error:", err);
      alert("Terjadi kesalahan: " + err.message);
    });
    pesanan = [];
    cartItemCount = 0;
    updateCartDisplay();
    renderPesanan();
  
    // Close modal if open
    const modal = document.getElementById('cartModal');
    if (modal && modal.classList.contains('open')) {
      toggleCartModal();
    }

}

let cartItemCount = 0;

// Function to add item to order
function tambah(nama, harga) {
  // Check if item already exists in pesanan
  let itemExists = false;

  for (let i = 0; i < pesanan.length; i++) {
    if (pesanan[i].nama === nama) {
      // Item exists, increment quantity
      pesanan[i].jumlah++;
      itemExists = true;
      break;
    }
  }

  // If item doesn't exist, add it with quantity 1
  if (!itemExists) {
    pesanan.push({
      nama: nama,
      harga: harga,
      jumlah: 1
    });
  }

  // Update cart item count
  cartItemCount++;

  // Update cart displays
  updateCartDisplay();
  renderPesanan();
}

// Function to remove item from order
function kurangi(nama) {
  for (let i = 0; i < pesanan.length; i++) {
    if (pesanan[i].nama === nama) {
      // Decrease quantity
      pesanan[i].jumlah--;
      // Update cart item count
      cartItemCount--;

      // If quantity is 0, remove item from array
      if (pesanan[i].jumlah <= 0) {
        pesanan.splice(i, 1);
      }
      break;
    }
  }

  // Update cart displays
  updateCartDisplay();
  renderPesanan();
}

// Function to render order items
function renderPesanan() {
  const pesananList = document.getElementById('pesananList');
  const pesananListMobile = document.getElementById('pesananListMobile');
  const totalHarga = document.getElementById('totalHarga');
  const totalHargaMobile = document.getElementById('totalHargaMobile');

  // Clear existing lists
  pesananList.innerHTML = '';
  if (pesananListMobile) {
    pesananListMobile.innerHTML = '';
  }

  let total = 0;

  // Add each item to the list
  pesanan.forEach(item => {
    const itemTotal = item.harga * item.jumlah;
    total += itemTotal;

    // Create list item for desktop
    const li = document.createElement('li');
    li.className = 'flex justify-between items-center py-1 border-b border-gray-200';
    li.innerHTML = `
                    <div class="flex items-center">
                        <span>${item.nama}</span>
                        <div class="ml-2 flex items-center">
                            <button onclick="kurangi('${item.nama}')" class="bg-gray-200 text-gray-700 px-2 py-0 rounded">-</button>
                            <span class="mx-2">${item.jumlah}</span>
                            <button onclick="tambah('${item.nama}', ${item.harga})" class="bg-gray-200 text-gray-700 px-2 py-0 rounded">+</button>
                        </div>
                    </div>
                    <div>Rp ${itemTotal.toLocaleString()}</div>
                `;
    pesananList.appendChild(li);

    // Create list item for mobile if mobile list exists
    if (pesananListMobile) {
      const liMobile = li.cloneNode(true);
      pesananListMobile.appendChild(liMobile);
    }
  });

  // Update total price
  totalHarga.textContent = total.toLocaleString();
  if (totalHargaMobile) {
    totalHargaMobile.textContent = total.toLocaleString();
  }
}

// Function to update cart item count display
function updateCartDisplay() {
  const cartCountDesktop = document.getElementById('cartCountDesktop');
  const cartCountMobile = document.getElementById('cartCountMobile');

  cartCountDesktop.textContent = cartItemCount;
  if (cartCountMobile) {
    cartCountMobile.textContent = cartItemCount;
  }
}

// Function to toggle mobile cart modal
function toggleCartModal() {
  const modal = document.getElementById('cartModal');
  if (modal.classList.contains('open')) {
    modal.classList.remove('open');
    setTimeout(() => {
      modal.style.display = 'none';
    }, 300);
  } else {
    modal.style.display = 'block';
    setTimeout(() => {
      modal.classList.add('open');
    }, 10);
  }
}
function deleteOrder() {
  const activeButton = document.querySelector('.customer-tabs button.active');
  if (activeButton) {
    const meja = activeButton.textContent.replace('Meja ', '');

    if (confirm(`Yakin ingin menghapus pesanan Meja ${meja}?`)) {
      fetch('hapus_pesanan.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `meja=${encodeURIComponent(meja)}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          alert(`Pesanan Meja ${meja} berhasil dihapus!`);
          location.reload();
        } else {
          alert("Gagal menghapus: " + (data.message || 'Unknown Error'));
        }
      })
      .catch(err => {
        alert("Gagal menghapus: " + err.message);
      });
    }
  }
}





