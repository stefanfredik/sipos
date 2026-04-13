<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    canResetPassword?: boolean;
    status?: string;
    systemStats: {
        posyandu: number;
        kader: number;
        balita: number;
        lansia: number;
        ibuHamil: number;
        jadwal: number;
    };
    recentActivity: Array<{
        name: string;
        type: string;
        date: string;
    }>;
}>();

// Login Form Logic
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
    <Head title="SIPOS — Sistem Informasi Posyandu" />

    <div class="split-root">
        
        <!-- ── LEFT PANE (VISUAL) ──────────────── -->
        <div class="pane-visual">
            <div class="visual-bg"></div>
            <div class="visual-content">
                <div class="brand-top">
                    <div class="b-ico">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    </div>
                    <div class="b-text">
                        <div class="b-name">SIPOS</div>
                        <div class="b-sub">Sistem Informasi Posyandu</div>
                    </div>
                </div>

                <div class="v-main">
                    <div class="v-badge">Transformasi ILP Desa Belumbang</div>
                    <img src="/images/posyandu-concept.jpg" alt="Konsep Posyandu" class="v-img" />
                    <div class="v-label">
                        <h3>Integrasi Layanan Primer</h3>
                        <p>Layanan kesehatan menyeluruh dari masa kehamilan hingga lansia dalam satu pintu pelayanan digital.</p>
                    </div>
                </div>

                <div class="v-footer">
                    © {{ new Date().getFullYear() }} Posyandu Desa Belumbang · Bali
                </div>
            </div>
        </div>

        <!-- ── RIGHT PANE (CONTENT & LOGIN) ───── -->
        <div class="pane-content">
            <div class="top-nav">
                <Link v-if="($page.props as any).auth?.user" :href="route('dashboard')" class="nav-link">
                    Dashboard →
                </Link>
                <div v-else class="nav-loc">Kec. Kerambitan, Kab. Tabanan</div>
            </div>

            <div class="main-form-area" id="login-anchor">
                
                <!-- CASE 1: AUTHENTICATED -->
                <div v-if="($page.props as any).auth?.user" class="card-auth">
                    <h1 class="h-title">Selamat Datang Kembali</h1>
                    <p class="h-desc">Anda sedang masuk sebagai akun kader/bidan. Klik tombol di bawah untuk masuk ke dashboard manajemen data.</p>
                    
                    <div class="mini-dashboard">
                        <div class="m-card">
                            <span class="m-n">{{ systemStats.balita + systemStats.lansia + systemStats.ibuHamil }}</span>
                            <span class="m-l">Peserta</span>
                        </div>
                        <div class="m-card">
                            <span class="m-n">{{ systemStats.jadwal }}</span>
                            <span class="m-l">Jadwal</span>
                        </div>
                    </div>

                    <div class="recent-box">
                        <div class="rb-t">Aktivitas Terkini</div>
                        <div v-if="recentActivity.length > 0">
                            <div v-for="(act, idx) in recentActivity" :key="idx" class="rb-item">
                                <span class="rb-dot" :class="act.type === 'Balita' ? 'g' : act.type === 'Ibu Hamil' ? 'b' : 'p'"></span>
                                <span class="rb-name">{{ act.name }}</span>
                                <span class="rb-time">{{ act.date }}</span>
                            </div>
                        </div>
                    </div>

                    <Link :href="route('dashboard')" class="btn-primary">Masuk ke Dashboard</Link>
                </div>

                <!-- CASE 2: GUEST (LOGIN) -->
                <div v-else class="auth-wrap">
                    <div class="h-header">
                        <h1 class="h-title">Digitalisasi <span class="t-green">Kesehatan Desa</span></h1>
                        <p class="h-desc">Silakan masukkan akun Anda untuk mulai mengelola data Posyandu Desa Belumbang.</p>
                    </div>

                    <div v-if="status" class="alert-box">{{ status }}</div>

                    <form @submit.prevent="submit" class="auth-form">
                        <div class="field">
                            <label>Username</label>
                            <input v-model="form.username" type="text" placeholder="Masukkan username" required autofocus />
                        </div>

                        <div class="field">
                            <div class="f-row">
                                <label>Password</label>
                                <Link v-if="canResetPassword" :href="route('password.request')">Lupa Password?</Link>
                            </div>
                            <div class="p-field">
                                <input v-model="form.password" :type="showPassword ? 'text' : 'password'" placeholder="Masukkan password" required />
                                <button type="button" class="p-btn" @click="showPassword = !showPassword">
                                    <svg v-if="!showPassword" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary" :disabled="form.processing">
                            {{ form.processing ? 'Menghubungkan...' : 'Masuk →' }}
                        </button>
                    </form>

                    <div class="trust-bar">
                        <div class="t-item">
                            <span class="t-n">{{ systemStats.posyandu }}</span>
                            <span class="t-l">Unit Posyandu</span>
                        </div>
                        <div class="t-item">
                            <span class="t-n">{{ systemStats.kader }}</span>
                            <span class="t-l">Kader Aktif</span>
                        </div>
                        <div class="t-item">
                            <span class="t-n">{{ systemStats.balita + systemStats.lansia + systemStats.ibuHamil }}</span>
                            <span class="t-l">Total Peserta</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<style scoped>
/* ── BASE ─────────────────────────────────── */
.split-root { font-family: 'Inter', system-ui, sans-serif; height: 100vh; display: flex; overflow: hidden; background: #fff; }
*, ::before, ::after { box-sizing: border-box; }

/* ── LEFT PANE (VISUAL) ──────────────────── */
.pane-visual { position: relative; width: 60%; background: #f0fdf4; display: flex; align-items: center; justify-content: center; overflow: hidden; }
@media (max-width: 1024px) { .pane-visual { display: none; } }

.visual-bg { position: absolute; inset: 0; background-image: radial-gradient(rgba(5,150,105,0.08) 1.5px, transparent 1.5px); background-size: 40px 40px; }

.visual-content { position: relative; z-index: 10; width: 100%; max-width: 800px; padding: 4rem; display: flex; flex-direction: column; height: 100%; }

.brand-top { display: flex; align-items: center; gap: .8rem; margin-bottom: auto; }
.b-ico { background: #059669; color: white; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px; box-shadow: 0 4px 12px rgba(5,150,105,0.2); }
.b-name { font-weight: 900; font-size: 1.4rem; line-height: 1; color: #0f172a; }
.b-sub { font-size: .75rem; color: #64748b; font-weight: 600; text-transform: uppercase; margin-top: .2rem; }

.v-main { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 2rem 0; }
.v-badge { align-self: flex-start; background: #fff; color: #059669; font-size: .75rem; font-weight: 700; padding: .4rem 1rem; border-radius: 99px; margin-bottom: 2rem; border: 1px solid #dcfce7; }
.v-img { width: 100%; height: auto; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); }
.v-label { margin-top: 3rem; }
.v-label h3 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 .75rem; letter-spacing: -0.5px; }
.v-label p { font-size: 1rem; color: #475569; line-height: 1.7; max-width: 500px; }

.v-footer { margin-top: auto; font-size: .8rem; color: #94a3b8; font-weight: 500; }

/* ── RIGHT PANE (CONTENT) ────────────────── */
.pane-content { width: 40%; background: #ffffff; padding: 3rem; display: flex; flex-direction: column; overflow-y: auto; }
@media (max-width: 1024px) { .pane-content { width: 100%; height: 100vh; padding: 2rem; } }

.top-nav { display: flex; justify-content: flex-end; align-items: center; margin-bottom: 4rem; }
.nav-link { font-weight: 700; font-size: .85rem; color: #059669; text-decoration: none; }
.nav-loc { font-size: .8rem; color: #94a3b8; font-weight: 500; }

.main-form-area { flex: 1; display: flex; flex-direction: column; justify-content: center; max-width: 420px; margin: 0 auto; width: 100%; }

.h-header { margin-bottom: 2.5rem; }
.h-title { font-size: 2.2rem; font-weight: 900; color: #0f172a; line-height: 1.2; letter-spacing: -1.5px; margin: 0 0 1rem; }
.t-green { color: #059669; }
.h-desc { font-size: 1rem; color: #64748b; line-height: 1.6; }

.alert-box { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: .8rem 1rem; border-radius: 4px; font-size: .85rem; font-weight: 500; margin-bottom: 2rem; }

.auth-form { display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 3rem; }
.field { display: flex; flex-direction: column; gap: .6rem; }
.f-row { display: flex; justify-content: space-between; align-items: center; }
.field label { font-size: .85rem; font-weight: 700; color: #334155; }
.field a { font-size: .78rem; font-weight: 600; color: #059669; text-decoration: none; }

.field input { width: 100%; height: 48px; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0 1rem; font-size: .95rem; background: #f8fafc; transition: all .2s; }
.field input:focus { outline: none; border-color: #059669; background: #fff; box-shadow: 0 0 0 4px rgba(5,150,105,0.08); }

.p-field { position: relative; }
.p-btn { position: absolute; right: .5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: .5rem; }

.btn-primary { 
    height: 52px; background: #0f172a; color: white; border: none; border-radius: 10px; font-weight: 800; font-size: 1rem; cursor: pointer; 
    transition: all .2s; display: flex; align-items: center; justify-content: center; text-decoration: none;
}
.btn-primary:hover:not(:disabled) { transform: scale(1.02); opacity: .9; }
.btn-primary:disabled { opacity: .6; cursor: wait; }

/* Stats Bar */
.trust-bar { display: flex; border-top: 1px solid #f1f5f9; padding-top: 2rem; gap: 1.5rem; justify-content: space-between; }
.t-item { display: flex; flex-direction: column; }
.t-n { font-size: 1.1rem; font-weight: 800; color: #0f172a; }
.t-l { font-size: .7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-top: .15rem; }

/* Mini Dashboard (Auth) */
.card-auth { display: flex; flex-direction: column; }
.mini-dashboard { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 1.5rem 0 2rem; }
.m-card { background: #f8fafc; border-radius: 12px; padding: 1rem; border: 1px solid #e2e8f0; }
.m-n { font-size: 1.5rem; font-weight: 900; color: #0f172a; display: block; }
.m-l { font-size: .75rem; color: #64748b; font-weight: 600; text-transform: uppercase; }

.recent-box { margin-bottom: 2.5rem; }
.rb-t { font-size: .9rem; font-weight: 800; color: #334155; margin-bottom: 1rem; }
.rb-item { display: flex; align-items: center; gap: .75rem; margin-bottom: .75rem; font-size: .9rem; }
.rb-dot { width: 8px; height: 8px; border-radius: 50%; }
.rb-dot.g { background: #22c55e; } .rb-dot.b { background: #3b82f6; } .rb-dot.p { background: #a855f7; }
.rb-name { flex: 1; font-weight: 600; color: #1e293b; }
.rb-time { font-size: .75rem; color: #94a3b8; }
</style>
