<?php
/**
 * Cabeçalho do painel administrativo.
 * Espera $tituloPagina e $paginaAdminAtiva definidos antes do include.
 */
if (!isset($paginaAdminAtiva)) {
    $paginaAdminAtiva = '';
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= limpar($tituloPagina ?? 'Painel') ?> · Admin Bitline</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="../css/estilo.css?v=<?= @filemtime(__DIR__ . '/../../css/estilo.css') ?>">
</head>
<body class="admin-body">

<div class="admin-wrapper">
  <aside class="admin-sidebar">
    <div class="admin-logo">Bit<span>line</span> <small>Admin</small></div>
    <nav class="admin-nav">
      <a href="dashboard.php" class="<?= $paginaAdminAtiva === 'dashboard' ? 'ativo' : '' ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a>
      <a href="artigos.php" class="<?= $paginaAdminAtiva === 'artigos' ? 'ativo' : '' ?>"><i class="fa-solid fa-newspaper"></i> Artigos</a>
      <a href="novo-artigo.php" class="<?= $paginaAdminAtiva === 'novo-artigo' ? 'ativo' : '' ?>"><i class="fa-solid fa-plus"></i> Novo Artigo</a>
      <a href="contactos.php" class="<?= $paginaAdminAtiva === 'contactos' ? 'ativo' : '' ?>"><i class="fa-solid fa-envelope"></i> Contactos</a>
      <a href="portfolio.php" class="<?= $paginaAdminAtiva === 'portfolio' ? 'ativo' : '' ?>"><i class="fa-solid fa-diagram-project"></i> Portefólio</a>
      <a href="depoimentos.php" class="<?= $paginaAdminAtiva === 'depoimentos' ? 'ativo' : '' ?>"><i class="fa-solid fa-quote-left"></i> Depoimentos</a>
      <a href="faq.php" class="<?= $paginaAdminAtiva === 'faq' ? 'ativo' : '' ?>"><i class="fa-solid fa-circle-question"></i> FAQ</a>
      <a href="candidaturas.php" class="<?= $paginaAdminAtiva === 'candidaturas' ? 'ativo' : '' ?>"><i class="fa-solid fa-id-card"></i> Candidaturas</a>
      <a href="../index.php" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> Ver Site</a>
      <a href="logout.php" class="admin-logout"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
    </nav>
  </aside>

  <main class="admin-main">
    <header class="admin-topbar">
      <h1><?= limpar($tituloPagina ?? '') ?></h1>
      <span class="admin-user"><i class="fa-solid fa-user-circle"></i> <?= limpar($_SESSION['admin_nome'] ?? 'Administrador') ?></span>
    </header>

    <?php if (!empty($_SESSION['admin_sucesso'])): ?>
      <div class="admin-alert admin-alert-sucesso"><?= limpar($_SESSION['admin_sucesso']) ?></div>
      <?php unset($_SESSION['admin_sucesso']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['admin_erro'])): ?>
      <div class="admin-alert admin-alert-erro"><?= limpar($_SESSION['admin_erro']) ?></div>
      <?php unset($_SESSION['admin_erro']); ?>
    <?php endif; ?>
