<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title') ?></title>
    
    <link rel="stylesheet" href="<?= base_url('css/vendor/bootstrap.min.css') ?>">
    <?= $this->renderSection('styles') ?>
</head>
<body>

    <?= $this->renderSection('content') ?>

    <script src="<?= base_url('js/vendor/vue.global.js') ?>"></script>
    <script src="<?= base_url('js/vendor/bootstrap.bundle.min.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>