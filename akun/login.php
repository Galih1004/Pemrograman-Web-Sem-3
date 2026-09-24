<?php
$page_title = "Login";
$body_class = "login-page";
$extra_head = ['<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">'];
$extra_scripts = ['assets/js/auth.js'];
include __DIR__ . '/../includes/header-auth.php';
?>
        <section class="role-select" id="roleSelect">
            <h2>Masuk ke SIMPUS-Mini</h2>
            <div class="role-cards">
                <div class="role-card" id="btnTamu" tabindex="0" role="button">
                    <div class="role-icon" aria-hidden="true"><i class="bi bi-person-fill"></i></div>
                    <h3>Tamu</h3>
                    <p>login sebagai tamu untuk melihat katalog buku.</p>
                </div>
                <div class="role-card" id="btnPetugas" tabindex="0" role="button">
                    <div class="role-icon" aria-hidden="true"><i class="bi bi-person-badge-fill"></i></div>
                    <h3>Petugas</h3>
                    <p>Login sebagai petugas.</p>
                </div>
            </div>
        </section>

        <section class="login-container" id="petugasLoginForm">
            <h2>Login Petugas</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Masukkan username(admin)">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password(admin123)">
                </div>
                <button type="submit" class="btn-submit">Masuk</button>
                <div class="form-links">
                    <a href="#" id="backToRole">← Kembali</a>
                    <a href="register.php">Buat Akun</a>
                    <a href="forgot-password.php">Lupa Password?</a>
                </div>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer-auth.php'; ?>
    <script>
        const roleSelect = document.getElementById('roleSelect');
        const petugasForm = document.getElementById('petugasLoginForm');

        function pilihTamu() {
            window.SIMPUS.setRole('tamu');
            localStorage.removeItem('simpusNama');
            window.location.href = '../index.php';
        }

        function tampilkanFormPetugas() {
            roleSelect.style.display = 'none';
            petugasForm.style.display = 'block';
        }

        document.getElementById('btnTamu').addEventListener('click', pilihTamu);
        document.getElementById('btnTamu').addEventListener('keyup', function (e) {
            if (e.key === 'Enter') pilihTamu();
        });

        document.getElementById('btnPetugas').addEventListener('click', tampilkanFormPetugas);
        document.getElementById('btnPetugas').addEventListener('keyup', function (e) {
            if (e.key === 'Enter') tampilkanFormPetugas();
        });

        document.getElementById('backToRole').addEventListener('click', function (e) {
            e.preventDefault();
            petugasForm.style.display = 'none';
            roleSelect.style.display = 'block';
        });

        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            if (username !== 'admin' || password !== 'admin123') {
                alert('Login gagal. Username harus admin dan password harus admin123.');
                document.getElementById('password').value = '';
                document.getElementById('username').focus();
                return;
            }

            window.SIMPUS.setRole('petugas');
            window.SIMPUS.setNama(username);

            alert('Login berhasil! Selamat datang, ' + username + '.');
            window.location.href = '../index.php';
        });
    </script>
</body>
</html>
