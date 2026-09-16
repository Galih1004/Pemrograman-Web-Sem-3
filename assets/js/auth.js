// assets/js/auth.js — SIMPUS-Mini (Peran Tamu & Petugas)
// Mengelola status peran (tamu/petugas) di localStorage, melindungi
// halaman khusus petugas, dan menyesuaikan tampilan navbar/dashboard
// sesuai peran yang sedang login.
(function () {
    // Deteksi otomatis prefix path "../" jika halaman ada di dalam
    // subfolder (akun/, buku/, anggota/, transaksi/), supaya redirect
    // tetap benar dari folder manapun.
    function getRoot() {
        const path = window.location.pathname;
        if (/\/(akun|buku|anggota|transaksi)\//.test(path)) {
            return '../';
        }
        return '';
    }

    const SIMPUS = {
        root: getRoot(),

        getRole: function () {
            return localStorage.getItem('simpusRole'); // 'tamu' | 'petugas' | null
        },

        setRole: function (role) {
            localStorage.setItem('simpusRole', role);
        },

        getNama: function () {
            return localStorage.getItem('simpusNama') || 'Petugas';
        },

        setNama: function (nama) {
            localStorage.setItem('simpusNama', nama);
        },

        logout: function () {
            localStorage.removeItem('simpusRole');
            localStorage.removeItem('simpusNama');
            window.location.href = SIMPUS.root + 'akun/login.html';
        },

        // Panggil di halaman yang butuh peran apa pun (tamu ATAU petugas).
        // jika belum login, langsung masuk ke halaman login
        requireLogin: function () {
            if (!SIMPUS.getRole()) {
                window.location.href = SIMPUS.root + 'akun/login.html';
                return false;
            }
            return true;
        },

        // Panggil di halaman khusus petugas (Tambah Buku, Daftar Anggota, Peminjaman, Pengembalian, dsb).
        requirePetugas: function () {
            const role = SIMPUS.getRole();
            if (role !== 'petugas') {
                window.location.href = SIMPUS.root + (role === 'tamu' ? 'index.html' : 'akun/login.html');
                return false;
            }
            return true;
        },

        // Sembunyikan/tampilkan elemen navbar & dashboard sesuai peran, lalu pasang aksi tombol Logout.
        renderNav: function () {
            const role = SIMPUS.getRole();

            document.querySelectorAll('[data-role="petugas-only"]').forEach(function (el) {
                el.style.display = (role === 'petugas') ? '' : 'none';
            });
            document.querySelectorAll('[data-role="tamu-only"]').forEach(function (el) {
                el.style.display = (role === 'tamu') ? '' : 'none';
            });
            // Tombol aksi (edit/hapus) di tabel: hanya untuk petugas.
            document.querySelectorAll('.aksi-petugas').forEach(function (el) {
                el.style.display = (role === 'petugas') ? '' : 'none';
            });

            const badge = document.querySelector('.nav-user-badge');
            if (badge) {
                badge.textContent = (role === 'petugas') ? ('👤 ' + SIMPUS.getNama()) : (role === 'tamu' ? '👤 Tamu' : '');
            }

            document.querySelectorAll('.logout').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    SIMPUS.logout();
                });
            });
        }
    };

    window.SIMPUS = SIMPUS;

    document.addEventListener('DOMContentLoaded', function () {
        SIMPUS.renderNav();
    });
})();
