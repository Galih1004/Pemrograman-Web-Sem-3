<?php
$page_title = "Register";
$show_back_link = true;
include __DIR__ . '/../includes/header-auth.php';
?>
        <section class="register-container">
            <h2>Daftar Akun Petugas</h2>
            <form id="registerForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Masukkan username">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Masukkan email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password">
                </div>
                <button type="submit" class="btn-submit">Daftar</button>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer-auth.php'; ?>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            fetch('proses_register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, email, password })
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                alert(data.pesan);
                if (data.success) {
                    window.location.href = 'login.php';
                }
            })
            .catch(function () { alert('Terjadi kesalahan, coba lagi.'); });
        });
    </script>
</body>
</html>