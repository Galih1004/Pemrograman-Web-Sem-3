<?php
session_start();

$__projectRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__projectRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digipus<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
    <?php if (!empty($extra_head)): foreach ($extra_head as $tag): ?>
    <?php echo $tag; ?>
    <?php endforeach; endif; ?>
</head>
<body<?php echo isset($body_class) ? ' class="' . $body_class . '"' : ''; ?>>
    <header>
        <h1>📚 Digipus</h1>
        <?php if (!empty($show_back_link)): ?>
        <a href="login.php" class="back-to-login">← Kembali ke Login</a>
        <?php endif; ?>
    </header>

    <main>
