<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import {
    Activity,
    ArrowLeft,
    Edit,
    User,
    Baby,
    Users,
    Calendar,
    MapPin,
    Stethoscope,
    FileText,
    Scale,
    Maximize2,
    Heart,
    ChevronRight,
    Check,
    X,
    HeartPulse,
} from 'lucide-vue-next';

interface Pemeriksaan {
    id: string;
    tgl_pemeriksaan: string;
    peserta_type: 'ibu_hamil' | 'balita' | 'lansia';
    peserta_id: string;
    berat_badan: string | null;
    tinggi_badan: string | null;
    sistole: number | null;
    diastole: number | null;
    hadir: boolean;
    peserta: { nama: string; nik: string };
    jadwal: { tanggal: string; posyandu: { nama: string } };
    kader: { nama: string } | null;
    bidan: { nama: string } | null;

    // Ibu Hamil
    usia_kehamilan: number | null;
    lila: string | null;
    kek_status: boolean;
    mt_bumil: boolean;
    ttd_status: boolean;
    kelas_bumil: boolean;

    // Balita
    lingkar_kepala: string | null;
    lingkar_lengan: string | null;
    obat_cacing: boolean;
    vitamin: string | null;

    // Lansia
    lingkar_perut: string | null;
    jenis_keluhan: string | null;
    obat_vitamin: string | null;

    edukasi: string | null;
    keterangan: string | null;
}

const props = defineProps<{ pemeriksaan: { data: Pemeriksaan } }>();
const data = props.pemeriksaan.data;

const categoryConfig = {
    ibu_hamil: {
        label: 'Ibu Hamil',
        icon: User,
        color: 'text-pink-600',
        bg: 'bg-pink-50',
        border: 'border-pink-200',
        accent: 'bg-pink-500',
        badgeBg: 'bg-pink-100 text-pink-700 border-pink-200',
    },
    balita: {
        label: 'Balita',
        icon: Baby,
        color: 'text-blue-600',
        bg: 'bg-blue-50',
        border: 'border-blue-200',
        accent: 'bg-blue-500',
        badgeBg: 'bg-blue-100 text-blue-700 border-blue-200',
    },
    lansia: {
        label: 'Lansia',
        icon: Users,
        color: 'text-emerald-600',
        bg: 'bg-emerald-50',
        border: 'border-emerald-200',
        accent: 'bg-emerald-500',
        badgeBg: 'bg-emerald-100 text-emerald-700 border-emerald-200',
    },
};

const config = categoryConfig[data.peserta_type];

const tensiFormatted = (data.sistole && data.diastole)
    ? `${data.sistole} / ${data.diastole}`
    : null;

const formatDate = (dateStr: string) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });
};
</script>

<template>
    <Head title="Detail Pemeriksaan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Button
                        variant="ghost"
                        size="icon"
                        @click="router.get(route('pemeriksaan.index'))"
                        class="h-9 w-9 rounded-full hover:bg-white/50"
                    >
                        <ArrowLeft class="h-5 w-5 text-gray-600" />
                    </Button>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-gray-900">Detail Pemeriksaan</h2>
                        <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                            <span>Pemeriksaan</span>
                            <ChevronRight class="h-3 w-3" />
                            <span class="font-medium text-gray-700">{{ data.peserta?.nama }}</span>
                        </div>
                    </div>
                </div>
                <Button
                    variant="outline"
                    @click="router.get(route('pemeriksaan.edit', data.id))"
                    class="flex items-center gap-2 rounded-lg"
                >
                    <Edit class="h-4 w-4" />
                    Edit Data
                </Button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Hero Card: Peserta -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <div :class="['h-1.5 w-full', config.accent]" />
                    <CardContent class="p-5">
                        <div class="flex items-center gap-4">
                            <div :class="['p-3 rounded-xl shrink-0', config.bg]">
                                <component :is="config.icon" :class="['h-8 w-8', config.color]" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ data.peserta?.nama }}</h3>
                                    <Badge variant="outline" :class="['text-xs font-semibold px-2 py-0.5', config.badgeBg]">
                                        {{ config.label }}
                                    </Badge>
                                    <Badge
                                        variant="outline"
                                        :class="data.hadir
                                            ? 'bg-green-50 text-green-700 border-green-200'
                                            : 'bg-red-50 text-red-700 border-red-200'"
                                        class="text-xs font-semibold px-2 py-0.5"
                                    >
                                        {{ data.hadir ? '✓ Hadir' : '✗ Tidak Hadir' }}
                                    </Badge>
                                </div>
                                <p class="text-sm text-muted-foreground mt-0.5">NIK: {{ data.peserta?.nik || '-' }}</p>
                            </div>
                            <div class="text-right shrink-0 hidden sm:block">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-tight">Tgl. Periksa</p>
                                <p class="text-sm font-semibold text-gray-700">{{ formatDate(data.tgl_pemeriksaan) }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Main Grid -->
                <div class="grid gap-6 md:grid-cols-3">

                    <!-- Left: Context Info -->
                    <div class="space-y-4 md:col-span-1">

                        <!-- Waktu & Lokasi -->
                        <Card class="border-none shadow-sm bg-white">
                            <CardHeader class="pb-3 border-b border-gray-100">
                                <CardTitle class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <div class="p-1 rounded-lg bg-amber-50 text-amber-600">
                                        <Calendar class="h-3.5 w-3.5" />
                                    </div>
                                    Waktu & Lokasi
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="p-4 space-y-3 text-sm">
                                <div class="flex justify-between items-start gap-2">
                                    <span class="text-muted-foreground shrink-0">Tanggal:</span>
                                    <span class="font-medium text-right">{{ formatDate(data.tgl_pemeriksaan) }}</span>
                                </div>
                                <div class="flex justify-between items-center gap-2">
                                    <span class="text-muted-foreground shrink-0">Posyandu:</span>
                                    <div class="flex items-center gap-1 font-medium">
                                        <MapPin class="h-3 w-3 text-gray-400 shrink-0" />
                                        <span class="text-right">{{ data.jadwal?.posyandu?.nama || '-' }}</span>
                                    </div>
                                </div>
                                <Separator class="bg-gray-100" />
                                <div class="space-y-2.5">
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Kader Petugas</p>
                                        <p class="font-medium text-gray-700 mt-0.5">{{ data.kader?.nama || '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Bidan Pendamping</p>
                                        <p class="font-medium text-gray-700 mt-0.5">{{ data.bidan?.nama || '—' }}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Tanda Vital -->
                        <Card class="border-none shadow-sm bg-white">
                            <CardHeader class="pb-3 border-b border-gray-100">
                                <CardTitle class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <div class="p-1 rounded-lg bg-red-50 text-red-500">
                                        <HeartPulse class="h-3.5 w-3.5" />
                                    </div>
                                    Tanda Vital
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="p-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-muted-foreground">
                                        <Scale class="h-3.5 w-3.5" />
                                        <span class="text-xs">Berat Badan</span>
                                    </div>
                                    <span class="font-bold text-gray-900">
                                        {{ data.berat_badan || '—' }}
                                        <span v-if="data.berat_badan" class="text-xs font-normal text-gray-400">kg</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-muted-foreground">
                                        <Maximize2 class="h-3.5 w-3.5" />
                                        <span class="text-xs">Tinggi Badan</span>
                                    </div>
                                    <span class="font-bold text-gray-900">
                                        {{ data.tinggi_badan || '—' }}
                                        <span v-if="data.tinggi_badan" class="text-xs font-normal text-gray-400">cm</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-muted-foreground">
                                        <Heart class="h-3.5 w-3.5" />
                                        <span class="text-xs">Tensi Darah</span>
                                    </div>
                                    <span class="font-bold text-gray-900 font-mono">
                                        <span v-if="tensiFormatted">{{ tensiFormatted }} <span class="text-xs font-normal text-gray-400">mmHg</span></span>
                                        <span v-else>—</span>
                                    </span>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Right: Category-Specific + Notes -->
                    <div class="space-y-4 md:col-span-2">

                        <!-- Ibu Hamil -->
                        <Card v-if="data.peserta_type === 'ibu_hamil'" class="border-none shadow-sm border-l-4 border-pink-400 bg-white">
                            <CardHeader class="pb-3 border-b border-pink-50">
                                <CardTitle class="flex items-center gap-2 text-base font-semibold text-pink-700">
                                    <div class="p-1.5 rounded-lg bg-pink-50 text-pink-500">
                                        <Stethoscope class="h-4 w-4" />
                                    </div>
                                    Hasil Pemeriksaan Kehamilan
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="p-5 space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-3 rounded-xl bg-pink-50/60 border border-pink-100">
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-pink-500">Usia Kehamilan</p>
                                        <p class="text-2xl font-bold text-gray-900 mt-1">
                                            {{ data.usia_kehamilan || '—' }}
                                            <span v-if="data.usia_kehamilan" class="text-sm font-normal text-gray-500">minggu</span>
                                        </p>
                                    </div>
                                    <div class="p-3 rounded-xl bg-pink-50/60 border border-pink-100">
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-pink-500">LiLA</p>
                                        <p class="text-2xl font-bold text-gray-900 mt-1">
                                            {{ data.lila || '—' }}
                                            <span v-if="data.lila" class="text-sm font-normal text-gray-500">cm</span>
                                        </p>
                                    </div>
                                </div>
                                <Separator class="bg-pink-50" />
                                <div class="grid grid-cols-2 gap-2">
                                    <div :class="['flex items-center gap-2 p-2.5 rounded-lg border text-sm font-medium', data.kek_status ? 'bg-red-50 border-red-200 text-red-700' : 'bg-gray-50 border-gray-100 text-gray-500']">
                                        <component :is="data.kek_status ? X : Check" class="h-4 w-4 shrink-0" />
                                        <span>Status KEK</span>
                                    </div>
                                    <div :class="['flex items-center gap-2 p-2.5 rounded-lg border text-sm font-medium', data.mt_bumil ? 'bg-pink-50 border-pink-200 text-pink-700' : 'bg-gray-50 border-gray-100 text-gray-500']">
                                        <component :is="data.mt_bumil ? Check : X" class="h-4 w-4 shrink-0" />
                                        <span>Terima MT</span>
                                    </div>
                                    <div :class="['flex items-center gap-2 p-2.5 rounded-lg border text-sm font-medium', data.ttd_status ? 'bg-pink-50 border-pink-200 text-pink-700' : 'bg-gray-50 border-gray-100 text-gray-500']">
                                        <component :is="data.ttd_status ? Check : X" class="h-4 w-4 shrink-0" />
                                        <span>Terima TTD</span>
                                    </div>
                                    <div :class="['flex items-center gap-2 p-2.5 rounded-lg border text-sm font-medium', data.kelas_bumil ? 'bg-pink-50 border-pink-200 text-pink-700' : 'bg-gray-50 border-gray-100 text-gray-500']">
                                        <component :is="data.kelas_bumil ? Check : X" class="h-4 w-4 shrink-0" />
                                        <span>Kelas Ibu</span>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Balita -->
                        <Card v-else-if="data.peserta_type === 'balita'" class="border-none shadow-sm border-l-4 border-blue-400 bg-white">
                            <CardHeader class="pb-3 border-b border-blue-50">
                                <CardTitle class="flex items-center gap-2 text-base font-semibold text-blue-700">
                                    <div class="p-1.5 rounded-lg bg-blue-50 text-blue-500">
                                        <Stethoscope class="h-4 w-4" />
                                    </div>
                                    Hasil Pemeriksaan Balita
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="p-5 space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-3 rounded-xl bg-blue-50/60 border border-blue-100">
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-blue-500">Lkr. Kepala</p>
                                        <p class="text-2xl font-bold text-gray-900 mt-1">
                                            {{ data.lingkar_kepala || '—' }}
                                            <span v-if="data.lingkar_kepala" class="text-sm font-normal text-gray-500">cm</span>
                                        </p>
                                    </div>
                                    <div class="p-3 rounded-xl bg-blue-50/60 border border-blue-100">
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-blue-500">Lkr. Lengan</p>
                                        <p class="text-2xl font-bold text-gray-900 mt-1">
                                            {{ data.lingkar_lengan || '—' }}
                                            <span v-if="data.lingkar_lengan" class="text-sm font-normal text-gray-500">cm</span>
                                        </p>
                                    </div>
                                </div>
                                <Separator class="bg-blue-50" />
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center text-sm p-2.5 rounded-lg bg-gray-50 border border-gray-100">
                                        <span class="text-muted-foreground">Vitamin diberikan:</span>
                                        <span class="font-semibold text-gray-700">{{ data.vitamin || 'Tidak ada' }}</span>
                                    </div>
                                    <div :class="['flex items-center gap-2 p-2.5 rounded-lg border text-sm font-medium', data.obat_cacing ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-gray-50 border-gray-100 text-gray-500']">
                                        <component :is="data.obat_cacing ? Check : X" class="h-4 w-4 shrink-0" />
                                        <span>Obat Cacing {{ data.obat_cacing ? 'Diberikan' : 'Tidak Diberikan' }}</span>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Lansia -->
                        <Card v-else-if="data.peserta_type === 'lansia'" class="border-none shadow-sm border-l-4 border-emerald-400 bg-white">
                            <CardHeader class="pb-3 border-b border-emerald-50">
                                <CardTitle class="flex items-center gap-2 text-base font-semibold text-emerald-700">
                                    <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-500">
                                        <Stethoscope class="h-4 w-4" />
                                    </div>
                                    Hasil Pemeriksaan Lansia
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="p-5 space-y-4">
                                <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100 w-fit min-w-[150px]">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-500">Lingkar Perut</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">
                                        {{ data.lingkar_perut || '—' }}
                                        <span v-if="data.lingkar_perut" class="text-sm font-normal text-gray-500">cm</span>
                                    </p>
                                </div>
                                <Separator class="bg-emerald-50" />
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Keluhan Utama</p>
                                        <div class="text-sm border-l-4 border-emerald-400 pl-3 py-2 bg-emerald-50/40 rounded-r-lg text-gray-700">
                                            {{ data.jenis_keluhan || 'Tidak ada keluhan.' }}
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Obat / Vitamin</p>
                                        <p class="text-sm font-medium text-gray-700">{{ data.obat_vitamin || '—' }}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Edukasi & Catatan -->
                        <Card class="border-none shadow-sm bg-white">
                            <CardHeader class="pb-3 border-b border-gray-100">
                                <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                    <div class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600">
                                        <FileText class="h-4 w-4" />
                                    </div>
                                    Edukasi & Catatan
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="p-5 space-y-4">
                                <div>
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Materi Edukasi / Konseling</p>
                                    <div class="text-sm text-gray-600 bg-gray-50 border border-gray-100 p-3.5 rounded-lg whitespace-pre-wrap min-h-[60px] leading-relaxed">
                                        {{ data.edukasi || 'Tidak ada catatan edukasi.' }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Keterangan Tambahan</p>
                                    <div class="text-sm text-gray-600 bg-gray-50 border border-dashed border-gray-200 p-3.5 rounded-lg whitespace-pre-wrap min-h-[60px] leading-relaxed">
                                        {{ data.keterangan || 'Tidak ada keterangan tambahan.' }}
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
