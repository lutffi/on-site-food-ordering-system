// At the very beginning of your script.js
window.pesanan = [];
window.total = 0;

const urlParams = new URLSearchParams(window.location.search);
const meja = urlParams.get('meja') || '?';
document.getElementById('nomorMeja').innerText = meja;

function tambah(nama, harga) {
  pesanan.push({ nama, harga });
  total += harga;
  updateUI();
}

function updateUI() {
  const list = document.getElementById('pesananList');
  list.innerHTML = '';
  pesanan.forEach(p => {
    const li = document.createElement('li');
    li.textContent = `${p.nama} - Rp${p.harga}`;
    list.appendChild(li);
  });
  document.getElementById('totalHarga').textContent = total;
}

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
      updateUI();
    } else {
      alert(res.message || "Gagal mengirim pesanan!");
    }
  })
  .catch(err => {
    console.error("Error:", err);
    alert("Terjadi kesalahan: " + err.message);
  });
}