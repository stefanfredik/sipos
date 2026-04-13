<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const showPassword = ref(false);

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Masuk — SIPOS" />

        <div class="login-container">
            <!-- Header -->
            <div class="login-header">
                <h2 class="login-title">Selamat Datang</h2>
                <p class="login-desc">Masuk ke akun SIPOS Anda untuk melanjutkan</p>
            </div>

            <!-- Status -->
            <div v-if="status" class="status-banner">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="login-form">
                <!-- Username field -->
                <div class="field-group">
                    <label for="username" class="field-label">Username</label>
                    <div class="input-wrapper" :class="{ 'input-error': form.errors.username }">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <input
                            id="username"
                            type="text"
                            v-model="form.username"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan username Anda"
                            class="field-input"
                        />
                    </div>
                    <p v-if="form.errors.username" class="field-error">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ form.errors.username }}
                    </p>
                </div>

                <!-- Password field -->
                <div class="field-group">
                    <div class="field-label-row">
                        <label for="password" class="field-label">Password</label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="forgot-link"
                        >
                            Lupa password?
                        </Link>
                    </div>
                    <div class="input-wrapper" :class="{ 'input-error': form.errors.password }">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password Anda"
                            class="field-input"
                        />
                        <button
                            type="button"
                            class="toggle-password"
                            @click="showPassword = !showPassword"
                            tabindex="-1"
                        >
                            <!-- Eye icon (show) -->
                            <svg v-if="!showPassword" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <!-- Eye-off icon (hide) -->
                            <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <p v-if="form.errors.password" class="field-error">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ form.errors.password }}
                    </p>
                </div>

                <!-- Remember me -->
                <div class="remember-row">
                    <label class="remember-label">
                        <input
                            id="remember"
                            type="checkbox"
                            v-model="form.remember"
                            class="remember-checkbox"
                        />
                        <span class="remember-custom-box">
                            <svg v-if="form.remember" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span class="remember-text">Ingat saya selama 30 hari</span>
                    </label>
                </div>

                <!-- Submit button -->
                <button
                    type="submit"
                    class="submit-btn"
                    :disabled="form.processing"
                    :class="{ 'btn-loading': form.processing }"
                >
                    <span v-if="form.processing" class="btn-spinner">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="spin-icon"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                        Memproses...
                    </span>
                    <span v-else class="btn-content">
                        Masuk ke SIPOS
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </span>
                </button>
            </form>

            <!-- Divider with role hint -->
            <div class="role-hint">
                <div class="role-hint-line"></div>
                <span class="role-hint-text">Akses sesuai peran Anda</span>
                <div class="role-hint-line"></div>
            </div>

            <!-- Role chips -->
            <div class="role-chips">
                <span class="role-chip role-admin">Admin</span>
                <span class="role-chip role-bidan">Bidan</span>
                <span class="role-chip role-kader">Kader</span>
                <span class="role-chip role-peserta">Peserta</span>
            </div>
        </div>
    </AuthLayout>
</template>

<style scoped>
/* ─── Container ────────────────────────────────────────────── */
.login-container {
    width: 100%;
    padding: 0;
}

/* ─── Header ───────────────────────────────────────────────── */
.login-header {
    margin-bottom: 2rem;
}

.login-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.8px;
    margin: 0 0 0.35rem;
}

.login-desc {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
}

/* ─── Status Banner ────────────────────────────────────────── */
.status-banner {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 0.65rem 0.9rem;
    color: #15803d;
    font-size: 0.85rem;
    margin-bottom: 1.5rem;
}

/* ─── Form ─────────────────────────────────────────────────── */
.login-form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* Field */
.field-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.field-label-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.field-label {
    font-size: 0.82rem;
    font-weight: 600;
    color: #374151;
    letter-spacing: 0.2px;
}

.forgot-link {
    font-size: 0.8rem;
    font-weight: 500;
    color: #2d6a4f;
    text-decoration: none;
    transition: color 0.15s;
}

.forgot-link:hover {
    color: #1b4332;
    text-decoration: underline;
}

/* Input wrapper */
.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    transition: border-color 0.2s, box-shadow 0.2s;
    overflow: hidden;
}

.input-wrapper:focus-within {
    border-color: #40916c;
    box-shadow: 0 0 0 3px rgba(64, 145, 108, 0.12);
}

.input-wrapper.input-error {
    border-color: #ef4444;
    box-shadow: none;
}

.input-wrapper.input-error:focus-within {
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.input-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    color: #94a3b8;
    flex-shrink: 0;
}

.field-input {
    flex: 1;
    height: 44px;
    background: transparent;
    border: none;
    outline: none;
    font-size: 0.9rem;
    color: #0f172a;
    padding: 0 0.75rem 0 0;
}

.field-input::placeholder {
    color: #cbd5e1;
}

.toggle-password {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 44px;
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    transition: color 0.15s;
    flex-shrink: 0;
}

.toggle-password:hover {
    color: #475569;
}

/* Error text */
.field-error {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.78rem;
    color: #ef4444;
    margin: 0;
}

/* ─── Remember ─────────────────────────────────────────────── */
.remember-row {
    margin-top: -0.25rem;
}

.remember-label {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
    user-select: none;
}

.remember-checkbox {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}

.remember-custom-box {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 5px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    flex-shrink: 0;
    transition: background 0.15s, border-color 0.15s;
}

.remember-checkbox:checked + .remember-custom-box {
    background: #2d6a4f;
    border-color: #2d6a4f;
}

.remember-text {
    font-size: 0.83rem;
    color: #475569;
}

/* ─── Submit Button ────────────────────────────────────────── */
.submit-btn {
    position: relative;
    width: 100%;
    height: 48px;
    background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);
    border: none;
    border-radius: 12px;
    color: #ffffff;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    letter-spacing: 0.3px;
    transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
    box-shadow: 0 4px 15px rgba(45, 106, 79, 0.35);
    margin-top: 0.25rem;
}

.submit-btn:hover:not(:disabled) {
    opacity: 0.92;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(45, 106, 79, 0.45);
}

.submit-btn:active:not(:disabled) {
    transform: translateY(0);
}

.submit-btn:disabled {
    opacity: 0.75;
    cursor: not-allowed;
}

.btn-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-spinner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.spin-icon {
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* ─── Role Hint ─────────────────────────────────────────────── */
.role-hint {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1.75rem;
}

.role-hint-line {
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}

.role-hint-text {
    font-size: 0.72rem;
    color: #94a3b8;
    white-space: nowrap;
    letter-spacing: 0.3px;
}

/* ─── Role Chips ────────────────────────────────────────────── */
.role-chips {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.75rem;
    flex-wrap: wrap;
}

.role-chip {
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.3rem 0.7rem;
    border-radius: 99px;
    letter-spacing: 0.3px;
}

.role-admin {
    background: #ede9fe;
    color: #6d28d9;
}

.role-bidan {
    background: #fce7f3;
    color: #be185d;
}

.role-kader {
    background: #dcfce7;
    color: #15803d;
}

.role-peserta {
    background: #dbeafe;
    color: #1d4ed8;
}
</style>
