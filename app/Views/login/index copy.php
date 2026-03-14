<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
    Bejelentkezés
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php if (session()->getFlashdata('message')): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('message')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif ?>

    <?php $errors = session()->getFlashdata('errors'); if (! empty($errors)): ?>
        <div class="container mt-3">
            <ul class="alert alert-danger list-unstyled mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <div id="app" class="container mt-5">
        <login-component
            form-action="<?= esc(url_to('Login::attempt')) ?>"
            csrf-name="<?= esc(csrf_token()) ?>"
            csrf-value="<?= esc(csrf_hash()) ?>"
        ></login-component>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const { createApp } = Vue;

    const LoginComponent = {
        name: 'login-component',
        props: {
            formAction: { type: String, required: true },
            csrfName:  { type: String, required: true },
            csrfValue: { type: String, required: true }
        },
        template: `
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5 col-xl-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-primary text-white text-center py-3">
                            <h4 class="mb-0 fw-bold">Bejelentkezés</h4>
                        </div>
                        <div class="card-body p-4">
                            <form :action="formAction" method="post" accept-charset="utf-8">
                                <input type="hidden" :name="csrfName" :value="csrfValue">
                                <div class="mb-3">
                                    <label for="username" class="form-label text-secondary fw-medium">Felhasználónév</label>
                                    <input 
                                        type="text" 
                                        id="username" 
                                        name="username" 
                                        v-model="usernameValue"
                                        class="form-control" 
                                        required 
                                        autofocus
                                    >
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label text-secondary fw-medium">Jelszó</label>
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        class="form-control" 
                                        required
                                    >
                                </div>
                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        Bejelentkezés
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `,
        data() {
            return {
                usernameValue: '<?= esc(old('username')) ?>'
            };
        }
    };

    const app = createApp({
        components: {
            'login-component': LoginComponent
        }
    });

    app.mount('#app');
</script>
<?= $this->endSection() ?>
