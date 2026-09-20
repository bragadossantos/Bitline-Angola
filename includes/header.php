<?php
/**
 * Cabeçalho partilhado por todas as páginas públicas.
 * Variáveis esperadas (definidas antes do include):
 *   $tituloPagina      (string) obrigatório
 *   $descricaoPagina   (string) opcional
 *   $paginaAtiva       (string) 'inicio' | 'blog' | 'contacto'
 */

if (!isset($paginaAtiva)) {
    $paginaAtiva = '';
}
if (!isset($descricaoPagina)) {
    $descricaoPagina = 'Bitline Angola — Soluções inteligentes em Infraestrutura de Redes, Cibersegurança, Cloud, Desenvolvimento de Software e Telecomunicações.';
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#071426">

    <title><?= limpar($tituloPagina) ?> · Bitline Angola</title>
    <meta name="description" content="<?= limpar($descricaoPagina) ?>">

    <!-- Open Graph / Redes Sociais -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Bitline Angola">
    <meta property="og:title" content="<?= limpar($tituloPagina) ?> · Bitline Angola">
    <meta property="og:description" content="<?= limpar($descricaoPagina) ?>">
    <meta property="og:image" content="<?= SITE_URL ?>/assets/images/og-image.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="<?= SITE_URL ?><?= limpar($_SERVER['REQUEST_URI'] ?? '') ?>">
    <meta property="og:locale" content="pt_PT">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= limpar($tituloPagina) ?> · Bitline Angola">
    <meta name="twitter:description" content="<?= limpar($descricaoPagina) ?>">
    <meta name="twitter:image" content="<?= SITE_URL ?>/assets/images/og-image.jpg">

    <link rel="icon" type="image/png" href="favicon.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/estilo.css?v=<?= @filemtime(__DIR__ . '/../css/estilo.css') ?>">

</head>

<body>

<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursorRing"></div>

<div class="bg-grid"></div>
<div class="bg-glow bg-glow-1"></div>
<div class="bg-glow bg-glow-2"></div>

<header>

    <div class="container">

        <a href="index.php" class="logo">
            <img src="assets/images/logo-icon.png" alt="Bitline Angola" class="logo-icon-img">
            <span class="logo-text">
                <span class="logo-name">Bit<span class="accent">line</span></span>
                <small>Angola</small>
            </span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Abrir menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav>

            <ul id="navLinks">

                <li><a href="index.php" class="<?= $paginaAtiva === 'inicio' ? 'ativo' : '' ?>">Início</a></li>

                <li><a href="index.php#sobre">Sobre</a></li>

                <li><a href="index.php#servicos">Serviços</a></li>

                <li><a href="blog.php" class="<?= $paginaAtiva === 'blog' ? 'ativo' : '' ?>">Blog</a></li>

                <li><a href="portfolio.php" class="<?= $paginaAtiva === 'portfolio' ? 'ativo' : '' ?>">Portefólio</a></li>

                <li><a href="faq.php" class="<?= $paginaAtiva === 'faq' ? 'ativo' : '' ?>">FAQ</a></li>

                <li><a href="trabalha-connosco.php" class="<?= $paginaAtiva === 'trabalha-connosco' ? 'ativo' : '' ?>">Trabalha Connosco</a></li>

                <li><a href="contacto.php" class="<?= $paginaAtiva === 'contacto' ? 'ativo' : '' ?>">Contacto</a></li>

            </ul>

        </nav>

        <a href="https://wa.me/244<?= CONTACTO_UNITEL ?>" class="btn-whatsapp" target="_blank">
            <i class="fa-brands fa-whatsapp"></i>
            WhatsApp
        </a>

    </div>

</header>

<main>