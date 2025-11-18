<?php
/**
 * Base Template Structure
 * Following instructor's diagram: TEMPLATE → HEADER → NAV → BODY
 * 
 * Usage in views:
 * <?= $this->extend('templates/base_template') ?>
 * <?= $this->section('header') ?>...<?= $this->endSection() ?>
 * <?= $this->section('nav') ?>...<?= $this->endSection() ?>
 * <?= $this->section('body') ?>...<?= $this->endSection() ?>
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'ChakaNoks SCMS') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('public/css/styles.css') ?>">
    
    <?= $this->renderSection('head') ?>
</head>
<body>
    <!-- TEMPLATE Container -->
    <div class="template-container">
        
        <!-- HEADER Section -->
        <header class="template-header">
            <?= $this->renderSection('header') ?>
        </header>

        <!-- NAV Section (Navigation) -->
        <nav class="template-nav">
            <?= $this->renderSection('nav') ?>
        </nav>

        <!-- BODY Section (Main Content) -->
        <main class="template-body">
            <?= $this->renderSection('body') ?>
        </main>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>

