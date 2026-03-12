<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
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
    TrendingUp,
    Heart
} from 'lucide-vue-next';
import { Separator } from '@/components/ui/separator';

interface Pemeriksaan {
    id: string;
    tgl_pemeriksaan: string;
    peserta_type: 'ibu_hamil' | 'balita' | 'lansia';
    peserta_id: string;
    berat_badan: string;
    tinggi_badan: string;
    tensi_darah: string;
    sistole: number;
    diastole: number;
    hadir: boolean;
    peserta: {
        nama: string;
        nik: string;
    };
    jadwal: {
        tanggal: string;
        posyandu: { nama: string };
    };
    kader: { nama: string };
    bidan: { nama: string };
    
    // Category specific
    usia_kehamilan: number;
    lila: string;
    kek_status: boolean;
    mt_bumil: boolean;
    ttd_status: boolean;
    kelas_bumil: boolean;
    
    lingkar_kepala: string;
    lingkar_lengan: string;
    obat_cacing: boolean;
    vitamin: string;
    
    lingkar_perut: string;
    jenis_keluhan: string;
    obat_vitamin: string;
    
    edukasi: string;
    keterangan: string;
}

const props = defineProps<{
    pemeriksaan: { data: Pemeriksaan };
}>();

const data = props.pemeriksaan.data;

const getPesertaLabel = (type: string) => {
    switch (type) {
        case 'ibu_hamil': return 'Ibu Hamil';
        case 'balita': return 'Balita';
        case 'lansia': return 'Lansia';
        default: return type;
    }
};

const getPesertaColor = (type: string) => {
    switch (type) {
        case 'ibu_hamil': return 'text-pink-600 bg-pink-50';
        case 'balita': return 'text-blue-600 bg-blue-50';
        case 'lansia': return 'text-green-600 bg-green-50';
        default: return 'text-gray-600 bg-gray-50';
    }
};
</script>

<template>
    <Head title="Detail Pemeriksaan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" @click="router.get(route('pemeriksaan.index'))">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pemeriksaan</h2>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('pemeriksaan.edit', data.id)">
                        <Button variant="outline" class="flex items-center gap-2">
                            <Edit class="h-4 w-4" />
                            Edit
                        </Button>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Header Info -->
                <Card class="overflow-hidden border-none shadow-md">
                    <div class="h-2 w-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500" />
                    <CardHeader class="flex flex-row items-center gap-6">
                        <div :class="['p-4 rounded-xl shrink-0', getPesertaColor(data.peserta_type)]">
                            <User v-if="data.peserta_type === 'ibu_hamil'" class="h-8 w-8" />
                            <Baby v-else-if="data.peserta_type === 'balita'" class="h-8 w-8" />
                            <Users v-else class="h-8 w-8" />
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <CardTitle class="text-2xl">{{ data.peserta?.nama }}</CardTitle>
                                <Badge variant="secondary">{{ getPesertaLabel(data.peserta_type) }}</Badge>
                                <Badge :variant="data.hadir ? 'default' : 'destructive'">
                                    {{ data.hadir ? 'Hadir' : 'Absen' }}
                                </Badge>
                            </div>
                            <CardDescription class="text-base mt-1">NIK: {{ data.peserta?.nik }}</CardDescription>
                        </div>
                    </CardHeader>
                </Card>

                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Left Column: Context -->
                    <div class="space-y-6 md:col-span-1">
                        <Card>
                            <CardHeader>
                                <CardTitle class="text-sm font-medium flex items-center gap-2">
                                    <Calendar class="h-4 w-4" />
                                    Waktu & Lokasi
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="grid gap-4 text-sm font-medium">
                                <div class="flex justify-between items-center">
                                    <span class="text-muted-foreground">Tgl. Periksa:</span>
                                    <span>{{ data.tgl_pemeriksaan }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-muted-foreground font-normal">Posyandu:</span>
                                    <div class="flex items-center gap-1">
                                        <MapPin class="h-3 w-3" />
                                        <span>{{ data.jadwal?.posyandu?.nama || '-' }}</span>
                                    </div>
                                </div>
                                <Separator />
                                <div class="space-y-1">
                                    <p class="text-xs text-muted-foreground font-normal">Petugas Kader:</p>
                                    <p>{{ data.kader?.nama || '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs text-muted-foreground font-normal">Bidan Pendamping:</p>
                                    <p>{{ data.bidan?.nama || '-' }}</p>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle class="text-sm font-medium flex items-center gap-2">
                                    <TrendingUp class="h-4 w-4" />
                                    Statistik Dasar
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="grid gap-4">
                                <div class="flex justify-between items-end">
                                    <div class="space-y-1">
                                        <p class="text-xs text-muted-foreground">Berat Badan</p>
                                        <p class="text-2xl font-bold">{{ data.berat_badan || '-' }} <span class="text-xs font-normal">kg</span></p>
                                    </div>
                                    <Activity class="h-8 w-8 text-blue-200" />
                                </div>
                                <div class="flex justify-between items-end">
                                    <div class="space-y-1">
                                        <p class="text-xs text-muted-foreground">Tinggi Badan</p>
                                        <p class="text-2xl font-bold">{{ data.tinggi_badan || '-' }} <span class="text-xs font-normal">cm</span></p>
                                    </div>
                                    <TrendingUp class="h-8 w-8 text-green-200" />
                                </div>
                                <div class="flex justify-between items-end" v-if="data.tensi_darah">
                                    <div class="space-y-1">
                                        <p class="text-xs text-muted-foreground">Tensi Darah</p>
                                        <p class="text-2xl font-bold font-mono tracking-tighter">{{ data.tensi_darah }}</p>
                                    </div>
                                    <Heart class="h-8 w-8 text-red-100" />
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Right Column: Specific Data -->
                    <div class="space-y-6 md:col-span-2">
                        <!-- Category Highlights -->
                        <Card v-if="data.peserta_type === 'ibu_hamil'">
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Stethoscope class="h-5 w-5 text-pink-500" />
                                    Hasil Pemeriksaan Kehamilan
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="grid gap-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-3 rounded-lg border bg-muted/20">
                                        <p class="text-xs text-muted-foreground">Usia Kehamilan</p>
                                        <p class="text-lg font-semibold">{{ data.usia_kehamilan || '-' }} Minggu</p>
                                    </div>
                                    <div class="p-3 rounded-lg border bg-muted/20">
                                        <p class="text-xs text-muted-foreground">Lingkar Lengan Atas (LiLA)</p>
                                        <p class="text-lg font-semibold">{{ data.lila || '-' }} cm</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <Badge :variant="data.kek_status ? 'destructive' : 'outline'">
                                        {{ data.kek_status ? 'Chronic Energy Deficiency (KEK)' : 'Normal Nutritional Status' }}
                                    </Badge>
                                    <Badge :variant="data.ttd_status ? 'default' : 'outline'">
                                        TTD: {{ data.ttd_status ? 'Diterima' : 'Tidak Diterima' }}
                                    </Badge>
                                    <Badge :variant="data.kelas_bumil ? 'default' : 'outline'">
                                        Kelas Bumil: {{ data.kelas_bumil ? 'Mengikuti' : 'Tidak Mengikuti' }}
                                    </Badge>
                                </div>
                            </CardContent>
                        </Card>

                        <Card v-if="data.peserta_type === 'balita'">
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Stethoscope class="h-5 w-5 text-blue-500" />
                                    Hasil Pemeriksaan Balita
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="grid gap-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-3 rounded-lg border bg-muted/20">
                                        <p class="text-xs text-muted-foreground">Lingkar Kepala</p>
                                        <p class="text-lg font-semibold">{{ data.lingkar_kepala || '-' }} cm</p>
                                    </div>
                                    <div class="p-3 rounded-lg border bg-muted/20">
                                        <p class="text-xs text-muted-foreground">Lingkar Lengan</p>
                                        <p class="text-lg font-semibold">{{ data.lingkar_lengan || '-' }} cm</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center text-sm">
                                        <span>Pemberian Vitamin:</span>
                                        <span class="font-semibold">{{ data.vitamin || 'Tidak Ada' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span>Obat Cacing:</span>
                                        <Badge :variant="data.obat_cacing ? 'default' : 'outline'">
                                            {{ data.obat_cacing ? 'Diberikan' : 'Tidak Diberikan' }}
                                        </Badge>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card v-if="data.peserta_type === 'lansia'">
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Stethoscope class="h-5 w-5 text-green-500" />
                                    Hasil Pemeriksaan Lansia
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="grid gap-4">
                                <div class="p-3 rounded-lg border bg-muted/20 w-fit min-w-[200px]">
                                    <p class="text-xs text-muted-foreground">Lingkar Perut</p>
                                    <p class="text-lg font-semibold">{{ data.lingkar_perut || '-' }} cm</p>
                                </div>
                                <div class="space-y-3 mt-2">
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-1">Keluhan Utama:</p>
                                        <p class="text-sm border-l-4 border-yellow-400 pl-3 py-1 bg-yellow-50/50">{{ data.jenis_keluhan || 'Tidak ada keluhan.' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-1">Obat/Vitamin:</p>
                                        <p>{{ data.obat_vitamin || '-' }}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Education & Notes -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2 text-base">
                                    <FileText class="h-4 w-4" />
                                    Edukasi & Catatan
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="grid gap-6">
                                <div class="space-y-2">
                                    <h4 class="text-sm font-semibold">Materi Edukasi:</h4>
                                    <div class="text-sm text-gray-600 bg-gray-50 p-4 rounded-lg whitespace-pre-wrap min-h-[60px]">
                                        {{ data.edukasi || 'Tidak ada catatan edukasi.' }}
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <h4 class="text-sm font-semibold">Keterangan Tambahan:</h4>
                                    <div class="text-sm text-gray-600 border-dashed border-2 p-4 rounded-lg whitespace-pre-wrap min-h-[60px]">
                                        {{ data.keterangan || '-' }}
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
