<?php
$page_title = "Ganti Password";
$show_back_link = true;
include __DIR__ . '/../includes/header-auth.php';
?>
        <section class="register-container">
            <h2>Ganti Password</h2>
            <form id="resetPasswordForm">
                <div class="form-group">
                    <label for="oldUsername">Username</label>
                    <input type="text" id="oldUsername" name="oldUsername" required placeholder="Masukkan username akun kamu">
                </div>
                <div class="form-group">
                    <label for="newPassword">Password Baru</label>
                    <input type="password" id="newPassword" name="newPassword" required placeholder="Masukkan password baru" minlength="6">
                </div>
                <button type="submit" class="btn-submit">Simpan Password</button>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer-auth.php'; ?>
    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const username = document.getElementById('oldUsername').value.trim();
            const newPassword = document.getElementById('newPassword').value;

            fetch('proses_reset_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, newPassword })
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