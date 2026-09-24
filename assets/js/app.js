// ===== 1. Hamburger menu =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return; // aman kalau elemennya belum ada di halaman ini

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== 2. Konfirmasi hapus (event delegation) dan Edit  =====
function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-delete");
        if (!btn) return;

        const row = btn.closest("tr");
        const id = btn.dataset.id;
        const namaKolom = row ? row.children[1]?.textContent.trim() : "data ini";
        const yakin = confirm('Yakin ingin menghapus "' + namaKolom + '"?');

        if (yakin && id) {
            window.location.href = "proses_hapus.php?id=" + encodeURIComponent(id);
        }
    });
}

function initEditRedirect() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-edit");
        if (!btn) return;

        const id = btn.dataset.id;
        if (id) {
            window.location.href = "tambah.php?id=" + encodeURIComponent(id);
        }
    });
}
// ===== 3. Filter/pencarian tabel real-time =====
function initTableFilter() {
    const input = document.getElementById("searchInput");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("input", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            const teks = row.textContent.toLowerCase();
            row.style.display = teks.includes(keyword) ? "" : "none";
        });
    });
}

// ===== 4. Highlight baris yang dipilih  =====
function initSelectRow() {
    const table = document.querySelector(".table-responsive table");
    if (!table) return;

    table.addEventListener("click", function (e) {
        if (e.target.closest("button")) return; // jangan pilih baris saat klik tombol aksi
        const row = e.target.closest("tr");
        if (!row || !row.closest("tbody")) return;

        table.querySelectorAll("tbody tr").forEach(function (r) {
            r.classList.remove("selected");
        });
        row.classList.add("selected");
    });
}

// ===== 5. Validasi form tambah  =====
function tampilkanError(input, pesan) {
    hapusError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.style.color = "red";
    span.style.fontSize = "0.85em";
    span.style.display = "block";
    span.textContent = pesan;
    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;

        // Field wajib: judul (buku) ATAU nama (anggota)
        const wajib = form.querySelector("[name='judul'], [name='nama'], [name='pengarang'], [name='no_anggota']");
        form.querySelectorAll("[required]").forEach(function (field) {
            if (field.value.trim() === "") {
                tampilkanError(field, "Field ini wajib diisi.");
                valid = false;
            } else {
                hapusError(field);
            }
        });

        // Khusus form buku: validasi tahun terbit
        const tahun = form.querySelector("[name='tahun']");
        if (tahun && tahun.value !== "") {
            const nilai = parseInt(tahun.value, 10);
            if (isNaN(nilai) || nilai < 1900 || nilai > 2026) {
                tampilkanError(tahun, "Tahun harus di antara 1900-2026.");
                valid = false;
            }
        }

        // Khusus form buku: validasi stok tidak boleh negatif
        const stok = form.querySelector("[name='stok']");
        if (stok && stok.value !== "") {
            const nilai = parseInt(stok.value, 10);
            if (isNaN(nilai) || nilai < 0) {
                tampilkanError(stok, "Stok tidak boleh negatif.");
                valid = false;
            }
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

// ===== 6. Logout =====
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initEditRedirect();
    initTableFilter();
    initSelectRow();
    initValidasiForm();
});