<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'en'], true)) {
    $_SESSION['locale'] = $_GET['lang'];
    header('Location: index.php');
    exit;
}

$locale = currentLocale();
?>
<!doctype html>
<html lang="<?= htmlspecialchars($locale); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty Salon</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<div class="language-modal">
    <div class="overlay"></div>
    <div class="modal-card">
        <h1><?= t('welcome'); ?></h1>
        <p><?= t('choose_language'); ?></p>
        <div class="btn-group">
            <a class="btn" href="?lang=es"><?= t('enter_spanish'); ?></a>
            <a class="btn" href="?lang=en"><?= t('enter_english'); ?></a>
        </div>
    </div>
</div>

<header class="topbar">
    <nav>
        <a href="../admin/login.php"><?= t('admin_login'); ?></a>
        <a href="../client/login.php"><?= t('client_login'); ?></a>
    </nav>
</header>

<main class="hero">
    <section>
        <h2><?= t('book_appointment'); ?></h2>
        <p><?= t('hero_text'); ?></p>
    </section>
</main>
</body>
</html>
