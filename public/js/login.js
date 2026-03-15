const { createApp } = Vue;

const LoginComponent = {
    name: 'login-component',
    props: {
        loginApiUrl: { type: String, required: true },
        csrfToken:   { type: String, required: true }
    },
    template: `
    <div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0 fw-bold">Bejelentkezés</h4>
                    </div>
                    <div class="card-body p-4">                            
                        <form @submit.prevent="handleLogin">
                            <div class="mb-3">
                                <label for="username" class="form-label text-secondary fw-medium">Felhasználónév</label>
                                <input 
                                    type="username" 
                                    id="username" 
                                    v-model="username"
                                    class="form-control"  
                                    required
                                    :disabled="isLoading"
                                >
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label text-secondary fw-medium">Jelszó</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    v-model="password"
                                    class="form-control" 
                                    required
                                    :disabled="isLoading"
                                >
                            </div>
                            <div class="d-grid gap-2 mt-4">
                                <button 
                                    type="submit" 
                                    class="btn btn-primary btn-lg"
                                    :disabled="isLoading"
                                >
                                    <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    <i v-else class="bi bi-box-arrow-in-right me-2"></i>
                                    {{ isLoading ? 'Bejelentkezés...' : 'Bejelentkezés' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="errorMessage" class="position-fixed alert alert-danger alert-dismissible fade show" style="top: 20px; right: 20px; z-index: 1000;" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ errorMessage }}
            <button type="button" class="btn-close" @click="errorMessage = ''" aria-label="Close"></button>
        </div>
    </div>
    `,
    data() {
        return {
            username: '',
            password: '',
            isLoading: false,
            errorMessage: '',
            activeCsrfToken: this.csrfToken
        };
    },
    methods: {
        handleLogin() {
            this.isLoading = true;
            this.errorMessage = '';

            const loginData = {
                username: this.username,
                password: this.password
            };

            fetch(this.loginApiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.activeCsrfToken
                },
                body: JSON.stringify(loginData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.csrf_token) this.activeCsrfToken = data.csrf_token;
                this.isLoading = false;
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    this.errorMessage = data.message || 'Bejelentkezés sikertelen.';
                    this.password = '';
                }
            })
            .catch(error => {
                this.isLoading = false;
                this.errorMessage = error.message;
                this.password = '';
            });
        }
    }
};

const app = createApp({
    components: {
        'login-component': LoginComponent
    },
});

app.mount('#app');
