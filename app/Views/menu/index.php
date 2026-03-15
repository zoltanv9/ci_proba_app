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
<script>
    const { createApp } = Vue;

    const MenuItem = {
        name: 'menu-item',
        props: {
            item: { type: Object, required: true }
        },
        template: `
        <li>
            <div class="menu-label">
                <i v-if="item.icon" :class="item.icon"></i>
                <span>{{ item.label }}</span>
            </div>
            <ul v-if="item.children && item.children.length">
                <menu-item
                    v-for="child in item.children"
                    :key="child.id"
                    :item="child"
                />
            </ul>
        </li>
        `,
    };

    const DashboardComponent = {
        name: 'dashboard-component',
        components: {
            'menu-item': MenuItem
        },
        props: {
            logoutUrl:   { type: String, required: true },
            csrfToken:   { type: String, required: true },
            username:    { type: String, default: '' },
            redirectUrl: { type: String, required: true },
            menuApiUrl:  { type: String, required: true },
            flashSuccess: { type: String, default: '' }
        },
        template: `
        <div>
            <nav class="navbar border-bottom px-4" style="background-color: #5c9eed;">
                <span class="navbar-brand fs-5 text-white">Codeigniter próba applikáció</span>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <span class="me-2 text-white">{{ username }}</span>
                    <button
                        class="btn btn-danger d-flex align-items-center gap-1"
                        @click="handleLogout"
                        :disabled="isLoading"
                        title="Kijelentkezés"
                    >
                        <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <i v-else class="bi bi-sign-turn-right-fill"></i>
                        <span class="d-none d-sm-inline">Kijelentkezés</span>
                    </button>
                </div>
            </nav>

            <div class="container-fluid px-5 mt-5">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white" style="height: 50px;">
                                <h5 class="card-title">Menü lista</h5>
                            </div>
                            <div class="card-body bg-light py-3">
                                <ul class="menu-tree list-unstyled mb-0">
                                    <menu-item v-for="item in menu" :key="item.id" :item="item"></menu-item>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white" style="height: 50px;">
                                <h5 class="card-title mb-0">Új menüpont</h5>
                            </div>
                            <div class="card-body bg-light">
                                <form @submit.prevent="submitNewItem" class="d-flex flex-column gap-3">
                                    <div>
                                        <label class="form-label">Menü cím</label>
                                        <input v-model="form.label" type="text" class="form-control" placeholder="pl. Beállítások" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Szülő</label>
                                        <select v-model="form.parent" class="form-select">
                                            <option :value="null">— Gyökér (nincs szülő) —</option>
                                            <option v-for="opt in parentOptions" :key="opt.id" :value="opt.id">{{ opt.optionLabel }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Ikon</label>
                                        <select v-model="form.icon" class="form-select">
                                            <option value="">— Nincs ikon —</option>
                                            <option v-for="ic in iconOptions" :key="ic.value" :value="ic.value">{{ ic.label }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">URL</label>
                                        <input v-model="form.url" type="text" class="form-control" placeholder="pl. /beallitasok">
                                    </div>
                                    <button type="submit" class="btn btn-primary" :disabled="saving">
                                        <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                                        Mentés
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="errorMessage" class="position-fixed alert alert-danger alert-dismissible fade show"
                style="top: 20px; right: 20px; z-index: 1000;" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ errorMessage }}
                <button type="button" class="btn-close" @click="errorMessage = ''" aria-label="Close"></button>
            </div>
            <div v-if="successMessage" class="position-fixed alert alert-success alert-dismissible fade show"
                style="top: 20px; right: 20px; z-index: 1000;" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ successMessage }}
                <button type="button" class="btn-close" @click="successMessage = ''" aria-label="Close"></button>
            </div>
        </div>
        `,
        data() {
            return {
                isLoading: false,
                errorMessage: '',
                menu: [],
                saving: false,
                activeCsrfToken: this.csrfToken,
                successMessage: this.flashSuccess,
                form: {
                    label: '',
                    parent: null,
                    icon: '',
                    url: ''
                },
                iconOptions: [
                    { value: 'bi bi-house-fill', label: 'House' },
                    { value: 'bi bi-folder-fill', label: 'Folder' },
                    { value: 'bi bi-gear-fill', label: 'Gear' },
                    { value: 'bi bi-search', label: 'Search' },
                    { value: 'bi bi-pencil-square', label: 'Pencil' },
                    { value: 'bi bi-geo-alt-fill', label: 'Geo' },
                    { value: 'bi bi-music-note-beamed', label: 'Music' },
                    { value: 'bi bi-cash-coin', label: 'Cash' },
                    { value: 'bi bi-envelope-fill', label: 'Envelope' },
                    { value: 'bi bi-laptop', label: 'Laptop' },
                    { value: 'bi bi-phone', label: 'Phone' },
                    { value: 'bi bi-link-45deg', label: 'Link' },
                    { value: 'bi bi-list-ul', label: 'List' },
                    { value: 'bi bi-star-fill', label: 'Star' }
                ]
            };
        },
        computed: {
            parentOptions() {
                const out = [];
                const walk = (items, parentLabel) => {
                    (items || []).forEach(item => {
                        const optionLabel = parentLabel ? `${item.label} (${parentLabel})` : item.label;
                        out.push({ id: item.id, optionLabel });
                        if (item.children && item.children.length) walk(item.children, item.label);
                    });
                };
                walk(this.menu, null);
                return out;
            }
        },
        methods: {
            handleLogout() {
                this.isLoading = true;
                this.errorMessage = '';

                fetch(this.logoutUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.activeCsrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.csrf_token) this.activeCsrfToken = data.csrf_token;
                    if (!data.success) throw new Error(data.message || 'Kijelentkezés sikertelen.');
                    window.location.href = data.redirect ?? this.redirectUrl;
                })
                .catch(error => {
                    this.isLoading = false;
                    this.errorMessage = error.message;
                });
            },
            async getMenu() {
                const response = await fetch(this.menuApiUrl, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': this.activeCsrfToken
                    }
                });
                if (!response.ok) {
                    throw new Error('Menü betöltése sikertelen.');
                }
                return response.json();
            },
            async submitNewItem() {
                this.saving = true;
                this.errorMessage = '';
                const payload = {
                    label: this.form.label.trim(),
                    parent: this.form.parent || null,
                    icon: this.form.icon || null,
                    url: (this.form.url && this.form.url.trim()) || null
                };
                try {
                    const response = await fetch(this.menuApiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.activeCsrfToken
                        },
                        body: JSON.stringify(payload)
                    });
                    const data = await response.json().catch(() => ({}));
                    if (data.csrf_token) this.activeCsrfToken = data.csrf_token;
                    if (!response.ok) {
                        throw new Error(data.message || 'Mentés sikertelen.');
                    }
                    this.form = { label: '', parent: null, icon: '', url: '' };
                    this.menu = await this.getMenu();
                } catch (err) {
                    this.errorMessage = err.message;
                } finally {
                    this.saving = false;
                }
            }
        },
        async mounted() {
            try {
                this.menu = await this.getMenu();
                console.log(this.menu);
            } catch (err) {
                this.errorMessage = err.message;
            }
        }
    };

    const app = createApp({
        components: {
            'dashboard-component': DashboardComponent,
            'menu-item': MenuItem
        }
    });

    app.mount('#app');
</script>
<?= $this->endSection() ?>
