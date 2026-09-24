<?php
$page_title = "Ganti Password";
$show_back_link = true;
include __DIR__ . '/../includes/header-auth.php';
?>
        <section class="register-container">
            <h2>Ganti Password</h2>
            <form id="resetPasswordForm">
                <div class="form-group">
                    <label for="oldUsername">Username Lama</label>
                    <input type="text" id="oldUsername" name="oldUsername" required placeholder="Masukkan username lama">
                </div>
                <div class="form-group">
                    <label for="newPassword">Password Baru</label>
                    <input type="password" id="newPassword" name="newPassword" required placeholder="Masukkan password baru">
                </div>
                <button type="submit" class="btn-submit">Simpan Password</button>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer-auth.php'; ?>
    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const oldUsername = document.getElementById('oldUsername').value;
            const newPassword = document.getElementById('newPassword').value;
            localStorage.setItem('resetPassword', JSON.stringify({ oldUsername, newPassword }));
            alert('Password berhasil diubah.');
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>
