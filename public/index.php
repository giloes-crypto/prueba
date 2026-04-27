<?php

declare(strict_types=1);

require_once __DIR__ . '/../backend/i18n.php';
$lang = detectLanguage();
$translations = require __DIR__ . '/../lang/lang_' . $lang . '.php';
?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES) ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Salón de Belleza</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body>
<header class="topbar">
  <nav class="nav">
    <a href="#inicio" data-i18n="menu_inicio"><?= htmlspecialchars(t('menu_inicio', $translations)) ?></a>
    <a href="#servicios" data-i18n="menu_servicios"><?= htmlspecialchars(t('menu_servicios', $translations)) ?></a>
    <a href="#galeria" data-i18n="menu_galeria"><?= htmlspecialchars(t('menu_galeria', $translations)) ?></a>
    <a href="#ubicacion" data-i18n="menu_ubicacion"><?= htmlspecialchars(t('menu_ubicacion', $translations)) ?></a>
    <a href="#contacto" data-i18n="menu_contacto"><?= htmlspecialchars(t('menu_contacto', $translations)) ?></a>
  </nav>
  <div class="lang-switch">
    <a href="?lang=es">ES</a> | <a href="?lang=en">EN</a>
  </div>
</header>

<main>
  <section id="inicio" class="hero">
    <h1 data-i18n="hero_titulo"><?= htmlspecialchars(t('hero_titulo', $translations)) ?></h1>
    <p data-i18n="hero_subtitulo"><?= htmlspecialchars(t('hero_subtitulo', $translations)) ?></p>
    <button id="cta-agendar" data-i18n="cta_agendar"><?= htmlspecialchars(t('cta_agendar', $translations)) ?></button>
  </section>

  <section id="servicios">
    <h2><?= htmlspecialchars(t('menu_servicios', $translations)) ?></h2>
    <div id="services-grid" class="cards"></div>
  </section>

  <section id="galeria"><h2><?= htmlspecialchars(t('menu_galeria', $translations)) ?></h2></section>
  <section id="ubicacion"><h2><?= htmlspecialchars(t('menu_ubicacion', $translations)) ?></h2></section>
  <section id="contacto">
    <h2 data-i18n="contacto_titulo"><?= htmlspecialchars(t('contacto_titulo', $translations)) ?></h2>
    <form id="contact-form">
      <input type="text" placeholder="Nombre" required />
      <input type="email" placeholder="Email" required />
      <textarea placeholder="Mensaje" required></textarea>
      <button type="submit">Enviar</button>
    </form>
  </section>
</main>

<script>
  window.APP_LANG = <?= json_encode($lang) ?>;
</script>
<script src="assets/js/app.js"></script>
</body>
</html>
