<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
import { useToast } from '@/Composables/useToast';
import { usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

defineProps<{
    title?: string;
}>();

const page = usePage();
const toast = useToast();
const flash = computed(() => page.props.flash as any);

watch(
    () => flash.value?.success,
    (message) => {
        if (message) {
            toast.success('Berhasil', message);
        }
    },
);

watch(
    () => flash.value?.error,
    (message) => {
        if (message) {
            toast.error('Gagal', message);
        }
    },
);

watch(
    () => (page.props as any).status,
    (status) => {
        if (status) {
            toast.info('Info', status);
        }
    },
);
</script>

<template>
    <div class="auth-root">
        <Toaster position="top-right" />
        <!-- Left Panel: Branding -->
        <div class="auth-left">
            <!-- Animated blobs -->
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>

            <!-- Grid overlay -->
            <div class="grid-overlay"></div>

            <div class="auth-left-content">
                <!-- Logo badge -->
                <div class="logo-badge">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                </div>

                <h1 class="brand-title">SIPOS</h1>
                <p class="brand-subtitle">Sistem Informasi Posyandu</p>

                <div class="divider-line"></div>

                <!-- Feature list -->
                <ul class="feature-list">
                    <li class="feature-item">
                        <span class="feature-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </span>
                        <span>Pencatatan data peserta terpadu</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </span>
                        <span>Pemantauan tumbuh kembang real-time</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                        <span>Penjadwalan & notifikasi otomatis</span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </span>
                        <span>Laporan bulanan instan ke Puskesmas</span>
                    </li>
                </ul>

                <!-- Location badge -->
                <div class="location-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>Posyandu Desa Belumbang, Kerambitan, Tabanan, Bali</span>
                </div>
            </div>
        </div>

        <!-- Right Panel: Form -->
        <div class="auth-right">
            <!-- Top decoration dots -->
            <div class="deco-dots"></div>

            <div class="auth-form-wrapper">
                <!-- Mobile logo (hidden on desktop) -->
                <div class="mobile-logo">
                    <div class="mobile-logo-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <span>SIPOS</span>
                </div>

                <slot />
            </div>

            <!-- Bottom brand footer -->
            <p class="auth-footer">
                &copy; {{ new Date().getFullYear() }} SIPOS — Posyandu Desa Belumbang
            </p>
        </div>
    </div>
</template>

<style scoped>
/* ─── Root Layout ─────────────────────────────────────────── */
.auth-root {
    display: flex;
    min-height: 100vh;
    font-family: 'Inter', system-ui, sans-serif;
}

/* ─── Left Panel ──────────────────────────────────────────── */
.auth-left {
    position: relative;
    display: none;
    flex: 1;
    overflow: hidden;
    background: linear-gradient(135deg, #1e3a5f 0%, #2d6a4f 50%, #1b4332 100%);
}

@media (min-width: 1024px) {
    .auth-left {
        display: flex;
        align-items: center;
        justify-content: center;
    }
}

/* Animated blobs */
.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.35;
    animation: blobFloat 8s ease-in-out infinite;
}

.blob-1 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, #40916c, #52b788);
    top: -100px; left: -100px;
    animation-delay: 0s;
}

.blob-2 {
    width: 350px; height: 350px;
    background: radial-gradient(circle, #2dc653, #74c69d);
    bottom: -80px; right: -80px;
    animation-delay: 3s;
}

.blob-3 {
    width: 280px; height: 280px;
    background: radial-gradient(circle, #1a7431, #52b788);
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    animation-delay: 5s;
}

@keyframes blobFloat {
    0%, 100% { transform: scale(1) translate(0, 0); }
    33% { transform: scale(1.08) translate(20px, -20px); }
    66% { transform: scale(0.95) translate(-15px, 10px); }
}

/* Grid overlay */
.grid-overlay {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 40px 40px;
}

/* Left content */
.auth-left-content {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 3rem;
    max-width: 480px;
}

.logo-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 16px;
    color: #ffffff;
    margin-bottom: 1.25rem;
    backdrop-filter: blur(8px);
}

.brand-title {
    font-size: 3.5rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -2px;
    line-height: 1;
    margin: 0 0 0.5rem;
}

.brand-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
    letter-spacing: 0.3px;
}

.divider-line {
    width: 48px;
    height: 3px;
    background: linear-gradient(90deg, #74c69d, transparent);
    border-radius: 99px;
    margin: 2rem 0;
}

/* Feature list */
.feature-list {
    list-style: none;
    padding: 0;
    margin: 0 0 2.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.9rem;
    line-height: 1.4;
}

.feature-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 8px;
    color: #74c69d;
    flex-shrink: 0;
}

/* Location badge */
.location-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 99px;
    padding: 0.4rem 0.85rem;
    color: rgba(255, 255, 255, 0.75);
    font-size: 0.75rem;
    backdrop-filter: blur(6px);
}

/* ─── Right Panel ─────────────────────────────────────────── */
.auth-right {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex: 1;
    background: #f8fafc;
    padding: 2rem 1.5rem;
    min-height: 100vh;
}

@media (min-width: 1024px) {
    .auth-right {
        flex: 0 0 480px;
        max-width: 480px;
    }
}

.deco-dots {
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background-image: radial-gradient(circle, #d1fae5 1.5px, transparent 1.5px);
    background-size: 20px 20px;
    opacity: 0.6;
    pointer-events: none;
}

.auth-form-wrapper {
    width: 100%;
    max-width: 380px;
}

/* Mobile logo (only on small screens) */
.mobile-logo {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 2rem;
    font-size: 1.3rem;
    font-weight: 800;
    color: #1e3a5f;
    letter-spacing: -0.5px;
}

@media (min-width: 1024px) {
    .mobile-logo {
        display: none;
    }
}

.mobile-logo-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, #2d6a4f, #40916c);
    border-radius: 10px;
    color: white;
}

.auth-footer {
    position: absolute;
    bottom: 1.5rem;
    font-size: 0.72rem;
    color: #94a3b8;
    text-align: center;
}
</style>
