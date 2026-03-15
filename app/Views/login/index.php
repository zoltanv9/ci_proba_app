<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
    Bejelentkezés
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div id="app" class="container mt-5 pt-5">
        <login-component
            login-api-url="<?= esc(base_url('sessions')) ?>"
            csrf-token="<?= esc(csrf_hash()) ?>"
        ></login-component>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/login.js') ?>"></script>
<?= $this->endSection() ?>