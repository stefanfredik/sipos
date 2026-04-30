<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { ref, computed } from 'vue';
import { 
    Activity, 
    ArrowLeft, 
    Save, 
    User, 
    Baby, 
    Users, 
    Scale, 
    Maximize2, 
    HeartPulse, 
    ClipboardList,
    Calendar,
    Stethoscope,
    MessageSquare,
    CheckCircle2,
    Info,
    ChevronRight,
} from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';
import SearchableSelect from '@/components/ui/SearchableSelect.vue';
import { cn } from '@/lib/utils';

interface Participant {
    id: string;
    nama: string;
    nik: string;
}

const props = defineProps<{
    pemeriksaan: { data: any };
    jadwal: any[];
    participants: {
        ibu_hamil: Participant[];
        balita: Participant[];
        lansia: Participant[];
    };
    kader: any[];
    bidan: any[];
}>();

const toast = useToast();
const data = props.pemeriksaan?.data || props.pemeriksaan;

const categoryConfig = {
    ibu_hamil: {
        label: 'Ibu Hamil',
        icon: User,
        color: 'text-pink-600',
        bg: 'bg-pink-50',
        border: 'border-pink-200',
        ring: 'ring-pink-500',
        activeBg: 'bg-pink-100',
    },
    balita: {
        label: 'Balita',
        icon: Baby,
        color: 'text-blue-600',
        bg: 'bg-blue-50',
        border: 'border-blue-200',
        ring: 'ring-blue-500',
        activeBg: 'bg-blue-100',
    },
    lansia: {
        label: 'Lansia',
        icon: Users,
        color: 'text-emerald-600',
        bg: 'bg-emerald-50',
        border: 'border-emerald-200',
        ring: 'ring-emerald-500',
        activeBg: 'bg-emerald-100',
    }
};

const currentConfig = computed(() => categoryConfig[form.peserta_type as keyof typeof categoryConfig]);

const jadwalOptions = computed(() =>
    props.jadwal.map((j) => ({
        id: j.id,
        label: `${new Date(j.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })} - ${j.posyandu?.nama_posyandu}`,
    })),
);

const kaderOptions = computed(() =>
    props.kader.map((k) => ({
        id: k.id,
        label: k.nama_kader,
    })),
);

const bidanOptions = computed(() =>
    props.bidan.map((b) => ({
        id: b.id,
        label: b.nama_bidan,
    })),
);

const form = useForm({
    jadwal_posyandu_id: data.jadwal_posyandu_id,
    peserta_type: data.peserta_type,
    peserta_id: data.peserta_id,
    tgl_pemeriksaan: data.tgl_pemeriksaan,
    hadir: data.hadir,
    kader_id: data.kader_id || '',
    bidan_id: data.bidan_id || '',
    
    // Core Vitals
    berat_badan: data.berat_badan ?? '',
    tinggi_badan: data.tinggi_badan ?? '',
    sistole: data.sistole ?? '',
    diastole: data.diastole ?? '',

    // Ibu Hamil specific
    usia_kehamilan: data.usia_kehamilan ?? '',
    lila: data.lila ?? '',
    kek_status: !!data.kek_status,
    mt_bumil: !!data.mt_bumil,
    ttd_status: !!data.ttd_status,
    kelas_bumil: !!data.kelas_bumil,

    // Balita specific
    lingkar_kepala: data.lingkar_kepala ?? '',
    lingkar_lengan: data.lingkar_lengan ?? '',
    obat_cacing: !!data.obat_cacing,
    vitamin: data.vitamin ?? '',

    // Lansia specific
    lingkar_perut: data.lingkar_perut ?? '',
    jenis_keluhan: data.jenis_keluhan ?? '',
    obat_vitamin: data.obat_vitamin ?? '',

    edukasi: data.edukasi ?? '',
    keterangan: data.keterangan ?? '',
});

const submit = () => {
    form.put(route('pemeriksaan.update', data.id), {
        onSuccess: () => {
            toast.success('Berhasil', 'Data pemeriksaan berhasil diperbarui.');
            router.get(route('pemeriksaan.index'));
        },
        onError: () => {
            toast.error('Gagal', 'Terjadi kesalahan saat memperbarui data.');
        },
    });
};
</script>

<template>
    <Head title="Edit Pemeriksaan" />

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
                        <h2 class="text-xl font-bold tracking-tight text-gray-900">Edit Pemeriksaan</h2>
                        <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                            <span>Pemeriksaan</span>
                            <ChevronRight class="h-3 w-3" />
                            <span class="font-medium text-gray-700">{{ data.peserta?.nama || 'Peserta' }}</span>
                        </div>
                    </div>
                </div>
                <Badge
                    v-if="currentConfig"
                    variant="outline"
                    :class="cn('px-3 py-1.5 font-semibold text-sm hidden md:flex items-center gap-1.5', currentConfig.color, currentConfig.border, currentConfig.bg)"
                >
                    <component :is="currentConfig.icon" class="h-3.5 w-3.5" />
                    {{ currentConfig.label }}
                </Badge>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6">

                    <!-- Bagian 1: Konteks Pemeriksaan -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                                    <User class="h-4 w-4" />
                                </div>
                                Informasi Peserta & Jadwal
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-5">
                            <!-- Peserta summary row -->
                            <div
                                v-if="currentConfig"
                                :class="cn('flex items-center gap-4 p-4 rounded-xl border', currentConfig.border, currentConfig.bg)"
                            >
                                <div :class="cn('p-2.5 rounded-xl bg-white shadow-sm', currentConfig.color)">
                                    <component :is="currentConfig.icon" class="h-6 w-6" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 truncate">{{ data.peserta?.nama || 'Peserta' }}</p>
                                    <p class="text-sm text-muted-foreground">{{ currentConfig.label }} · NIK: {{ data.peserta?.nik || '-' }}</p>
                                </div>
                                <div class="flex items-center gap-3 shrink-0 pl-4 border-l" :class="currentConfig.border">
                                    <div class="text-right">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-tight">Status Hadir</p>
                                        <p class="text-sm font-semibold" :class="form.hadir ? 'text-green-600' : 'text-gray-400'">
                                            {{ form.hadir ? 'Hadir' : 'Tidak Hadir' }}
                                        </p>
                                    </div>
                                    <Switch id="hadir" v-model:checked="form.hadir" class="data-[state=checked]:bg-green-500" />
                                </div>
                            </div>

                            <!-- Jadwal & Tanggal row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <Label for="jadwal_posyandu_id" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Jadwal Kegiatan</Label>
                                    <SearchableSelect
                                        v-model="form.jadwal_posyandu_id"
                                        :options="jadwalOptions"
                                        placeholder="Pilih Jadwal..."
                                    />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="tgl_pemeriksaan" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Tanggal Periksa</Label>
                                    <div class="relative">
                                        <Calendar class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" />
                                        <Input
                                            type="date"
                                            id="tgl_pemeriksaan"
                                            v-model="form.tgl_pemeriksaan"
                                            required
                                            class="pl-10"
                                        />
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Bagian 2: Data Kesehatan (hanya jika hadir) -->
                    <Transition
                        enter-active-class="transition-all duration-300 ease-out"
                        enter-from-class="-translate-y-3 opacity-0"
                        enter-to-class="translate-y-0 opacity-100"
                        leave-active-class="transition-all duration-200 ease-in"
                        leave-from-class="translate-y-0 opacity-100"
                        leave-to-class="-translate-y-3 opacity-0"
                    >
                        <div v-if="form.hadir" class="space-y-6">
                            <!-- Tanda Vital -->
                            <Card class="border-none shadow-sm bg-white">
                                <CardHeader class="pb-3 border-b border-gray-100">
                                    <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                        <div class="p-1.5 rounded-lg bg-red-50 text-red-500">
                                            <HeartPulse class="h-4 w-4" />
                                        </div>
                                        Tanda Vital
                                    </CardTitle>
                                </CardHeader>
                                <CardContent class="p-5">
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="space-y-1.5">
                                            <Label for="berat_badan" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Berat Badan</Label>
                                            <div class="relative">
                                                <Scale class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" />
                                                <Input id="berat_badan" type="number" step="0.1" v-model="form.berat_badan" class="pl-10 pr-10" placeholder="0.0" />
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">kg</span>
                                            </div>
                                        </div>
                                        <div class="space-y-1.5">
                                            <Label for="tinggi_badan" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Tinggi Badan</Label>
                                            <div class="relative">
                                                <Maximize2 class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" />
                                                <Input id="tinggi_badan" type="number" step="0.1" v-model="form.tinggi_badan" class="pl-10 pr-10" placeholder="0.0" />
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">cm</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-1.5">
                                        <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Tekanan Darah (Tensi)</Label>
                                        <div class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 bg-gray-50">
                                            <Input
                                                id="sistole"
                                                type="number"
                                                v-model="form.sistole"
                                                class="border-0 bg-transparent text-center text-xl font-bold shadow-none focus-visible:ring-0 p-0 h-9"
                                                placeholder="120"
                                            />
                                            <span class="text-2xl font-light text-gray-300 shrink-0">/</span>
                                            <Input
                                                id="diastole"
                                                type="number"
                                                v-model="form.diastole"
                                                class="border-0 bg-transparent text-center text-xl font-bold shadow-none focus-visible:ring-0 p-0 h-9"
                                                placeholder="80"
                                            />
                                            <span class="text-xs font-bold text-gray-400 border-l pl-2 shrink-0">mmHg</span>
                                        </div>
                                        <div class="flex justify-between text-[10px] font-bold uppercase text-gray-400 px-1">
                                            <span>Sistole</span>
                                            <span>Diastole</span>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>

                            <!-- Data Spesifik Kategori -->
                            <!-- Ibu Hamil -->
                            <Card v-if="form.peserta_type === 'ibu_hamil'" class="border-none shadow-sm border-l-4 border-pink-400 bg-white">
                                <CardHeader class="pb-3 border-b border-pink-50">
                                    <CardTitle class="flex items-center gap-2 text-base font-semibold text-pink-700">
                                        <div class="p-1.5 rounded-lg bg-pink-50 text-pink-500">
                                            <User class="h-4 w-4" />
                                        </div>
                                        Data Ibu Hamil
                                    </CardTitle>
                                </CardHeader>
                                <CardContent class="p-5 space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <Label for="usia_kehamilan" class="text-xs font-semibold uppercase tracking-wider text-pink-600/70">Usia Kehamilan</Label>
                                            <div class="relative">
                                                <Input id="usia_kehamilan" type="number" v-model="form.usia_kehamilan" class="pr-16 border-pink-100 focus:border-pink-300" placeholder="0" />
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-pink-400">Minggu</span>
                                            </div>
                                        </div>
                                        <div class="space-y-1.5">
                                            <Label for="lila" class="text-xs font-semibold uppercase tracking-wider text-pink-600/70">LiLA</Label>
                                            <div class="relative">
                                                <Input id="lila" type="number" step="0.1" v-model="form.lila" class="pr-10 border-pink-100 focus:border-pink-300" placeholder="0.0" />
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-pink-400">cm</span>
                                            </div>
                                        </div>
                                    </div>
                                    <Separator class="bg-pink-50" />
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-pink-50/60 border border-pink-100">
                                            <Label for="kek_status" class="text-sm font-medium text-pink-800 cursor-pointer">Status KEK</Label>
                                            <Switch id="kek_status" v-model:checked="form.kek_status" class="data-[state=checked]:bg-pink-500" />
                                        </div>
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-pink-50/60 border border-pink-100">
                                            <Label for="mt_bumil" class="text-sm font-medium text-pink-800 cursor-pointer">Terima MT</Label>
                                            <Switch id="mt_bumil" v-model:checked="form.mt_bumil" class="data-[state=checked]:bg-pink-500" />
                                        </div>
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-pink-50/60 border border-pink-100">
                                            <Label for="ttd_status" class="text-sm font-medium text-pink-800 cursor-pointer">Terima TTD</Label>
                                            <Switch id="ttd_status" v-model:checked="form.ttd_status" class="data-[state=checked]:bg-pink-500" />
                                        </div>
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-pink-50/60 border border-pink-100">
                                            <Label for="kelas_bumil" class="text-sm font-medium text-pink-800 cursor-pointer">Ikut Kelas Ibu</Label>
                                            <Switch id="kelas_bumil" v-model:checked="form.kelas_bumil" class="data-[state=checked]:bg-pink-500" />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>

                            <!-- Balita -->
                            <Card v-else-if="form.peserta_type === 'balita'" class="border-none shadow-sm border-l-4 border-blue-400 bg-white">
                                <CardHeader class="pb-3 border-b border-blue-50">
                                    <CardTitle class="flex items-center gap-2 text-base font-semibold text-blue-700">
                                        <div class="p-1.5 rounded-lg bg-blue-50 text-blue-500">
                                            <Baby class="h-4 w-4" />
                                        </div>
                                        Pertumbuhan Balita
                                    </CardTitle>
                                </CardHeader>
                                <CardContent class="p-5 space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <Label for="lingkar_kepala" class="text-xs font-semibold uppercase tracking-wider text-blue-600/70">Lkr. Kepala</Label>
                                            <div class="relative">
                                                <Input id="lingkar_kepala" type="number" step="0.1" v-model="form.lingkar_kepala" class="pr-10 border-blue-100 focus:border-blue-300" placeholder="0.0" />
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-blue-400">cm</span>
                                            </div>
                                        </div>
                                        <div class="space-y-1.5">
                                            <Label for="lingkar_lengan" class="text-xs font-semibold uppercase tracking-wider text-blue-600/70">Lkr. Lengan</Label>
                                            <div class="relative">
                                                <Input id="lingkar_lengan" type="number" step="0.1" v-model="form.lingkar_lengan" class="pr-10 border-blue-100 focus:border-blue-300" placeholder="0.0" />
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-blue-400">cm</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <Label for="vitamin" class="text-xs font-semibold uppercase tracking-wider text-blue-600/70">Vitamin Diberikan</Label>
                                        <Input id="vitamin" v-model="form.vitamin" class="border-blue-100 focus:border-blue-300" placeholder="Contoh: Vitamin A Biru" />
                                    </div>
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50/60 border border-blue-100">
                                        <Label for="obat_cacing" class="text-sm font-medium text-blue-800 cursor-pointer">Diberikan Obat Cacing</Label>
                                        <Switch id="obat_cacing" v-model:checked="form.obat_cacing" class="data-[state=checked]:bg-blue-500" />
                                    </div>
                                </CardContent>
                            </Card>

                            <!-- Lansia -->
                            <Card v-else-if="form.peserta_type === 'lansia'" class="border-none shadow-sm border-l-4 border-emerald-400 bg-white">
                                <CardHeader class="pb-3 border-b border-emerald-50">
                                    <CardTitle class="flex items-center gap-2 text-base font-semibold text-emerald-700">
                                        <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-500">
                                            <Users class="h-4 w-4" />
                                        </div>
                                        Kesehatan Lansia
                                    </CardTitle>
                                </CardHeader>
                                <CardContent class="p-5 space-y-4">
                                    <div class="space-y-1.5">
                                        <Label for="lingkar_perut" class="text-xs font-semibold uppercase tracking-wider text-emerald-600/70">Lingkar Perut</Label>
                                        <div class="relative">
                                            <Input id="lingkar_perut" type="number" step="0.1" v-model="form.lingkar_perut" class="pr-10 border-emerald-100 focus:border-emerald-300" placeholder="0.0" />
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-emerald-400">cm</span>
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <Label for="jenis_keluhan" class="text-xs font-semibold uppercase tracking-wider text-emerald-600/70">Keluhan Utama</Label>
                                        <Input id="jenis_keluhan" v-model="form.jenis_keluhan" class="border-emerald-100 focus:border-emerald-300" placeholder="Misal: Pusing, Sakit Sendi..." />
                                    </div>
                                    <div class="space-y-1.5">
                                        <Label for="obat_vitamin" class="text-xs font-semibold uppercase tracking-wider text-emerald-600/70">Obat / Vitamin</Label>
                                        <Input id="obat_vitamin" v-model="form.obat_vitamin" class="border-emerald-100 focus:border-emerald-300" placeholder="Obat yang diberikan..." />
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                    </Transition>

                    <!-- Bagian 3: Petugas & Catatan -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600">
                                    <Stethoscope class="h-4 w-4" />
                                </div>
                                Petugas & Catatan
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <Label for="kader_id" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                        <User class="h-3 w-3" /> Kader Petugas
                                    </Label>
                                    <SearchableSelect v-model="form.kader_id" :options="kaderOptions" placeholder="Pilih Kader..." />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="bidan_id" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                        <Activity class="h-3 w-3" /> Bidan Pendamping
                                    </Label>
                                    <SearchableSelect v-model="form.bidan_id" :options="bidanOptions" placeholder="Pilih Bidan..." />
                                </div>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <Label for="edukasi" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                        <MessageSquare class="h-3 w-3" /> Edukasi / Konseling
                                    </Label>
                                    <Textarea id="edukasi" v-model="form.edukasi" placeholder="Materi edukasi yang disampaikan..." class="min-h-[90px] resize-none" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label for="keterangan" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                        <Info class="h-3 w-3" /> Keterangan Tambahan
                                    </Label>
                                    <Textarea id="keterangan" v-model="form.keterangan" placeholder="Catatan medis atau informasi penting..." class="min-h-[90px] resize-none" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-2 pb-6">
                        <Button type="button" variant="ghost" @click="router.get(route('pemeriksaan.index'))" class="text-gray-500 hover:text-gray-700">
                            Batalkan Perubahan
                        </Button>
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm shadow-blue-200 transition-all active:scale-95 flex items-center gap-2"
                        >
                            <div v-if="form.processing" class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent" />
                            <Save v-else class="h-4 w-4" />
                            <span class="font-semibold">{{ form.processing ? 'Memperbarui...' : 'Perbarui Data' }}</span>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
  transform: translateY(8px);
}
</style>
