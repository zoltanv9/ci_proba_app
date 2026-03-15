<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
    Menü szerkesztő
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .menu-tree ul { margin-left: 1.25rem; padding-left: 1rem; border-left: 1px solid #dee2e6; list-style: none; }
    .menu-tree li { position: relative; padding: 0.5rem 0; list-style: none; }
    .menu-tree li > .menu-label { display: flex; align-items: center; gap: 0.5rem; }
    .menu-tree li > .menu-label i { color: #0d6efd; }
    .menu-tree ul li::before { content: ''; position: absolute; left: -1rem; top: 1rem; width: 1rem; height: 1px; background: #dee2e6; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div id="app">
        <dashboard-component
            logout-url="<?= esc(base_url('sessions')) ?>"
            csrf-token="<?= esc(csrf_hash()) ?>"
            username="<?= esc(session()->get('username')) ?>"
            redirect-url="<?= esc(base_url('login')) ?>"
            menu-api-url="<?= esc(base_url('menu')) ?>"
            flash-success="<?= esc(session()->getFlashdata('success')) ?>"
        ></dashboard-component>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/menu.js') ?>"></script>
<?= $this->endSection() ?>
