<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title') ?></title>
    
    <link rel="stylesheet" href="<?= base_url('css/lib/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/lib/bootstrap-icons.min.css') ?>">
    <style>
        .bg-custom-light { background-color:rgb(241, 241, 241); }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-custom-light min-vh-100">

    <?= $this->renderSection('content') ?>

    <script src="<?= base_url('js/lib/vue.global.js') ?>"></script>
    <script src="<?= base_url('js/lib/bootstrap.bundle.min.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>