    </main>

    <footer>
        <p>&copy; 2026 Digipus &mdash; Moch Galih Putra Pratama</p>
    </footer>

    <?php if (!empty($extra_scripts)): foreach ($extra_scripts as $src): ?>
    <script src="<?php echo $base . $src; ?>"></script>
    <?php endforeach; endif; ?>
