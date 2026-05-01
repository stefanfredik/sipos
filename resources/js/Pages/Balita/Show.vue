<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import {
    AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent,
    AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { ArrowLeft, Edit, Trash2, User, Phone, MapPin, ChevronRight, Calendar, Baby, Plus, HeartPulse } from 'lucide-vue-next';
import { computed } from 'vue';
import { Line as LineChart } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, LinearScale, PointElement, CategoryScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, LinearScale, PointElement, CategoryScale);

const props = defineProps<{
    balita: {
        data: {
            id: string; nik: string; nama: string; foto: string | null;
            nama_orang_tua: string; tgl_lahir: string; umur_label: string;
            jenis_kelamin: string; no_telp: string; alamat: string;
            keterangan: string | null; is_active: boolean; created_at: string;
        }
    };
    pemeriksaan: { data: any[] };
}>();

const d = props.balita.data;

const chartData = computed(() => ({
    labels: props.pemeriksaan.data.map(p => p.tgl_pemeriksaan),
    datasets: [
        { label: 'Berat Badan (kg)', backgroundColor: '#3b82f6', borderColor: '#3b82f6', data: props.pemeriksaan.data.map(p => p.berat_badan), tension: 0.3 },
        { label: 'Tinggi Badan (cm)', backgroundColor: '#06b6d4', borderColor: '#06b6d4', data: props.pemeriksaan.data.map(p => p.tinggi_badan), tension: 0.3 },
    ]
}));

const chartOptions = {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' as const } },
    scales: { y: { beginAtZero: false } }
};

function deleteBalita() {
    router.delete(route('balita.destroy', { balita: d.id }));
}
</script>

<template>
    <Head :title="'Detail ' + d.nama" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Button variant="ghost" size="icon" @click="router.get(route('balita.index'))"
                        class="h-9 w-9 rounded-full hover:bg-white/50">
                        <ArrowLeft class="h-5 w-5 text-gray-600" />
                    </Button>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-gray-900">Detail Balita</h2>
                        <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                            <span>Data Balita</span>
                            <ChevronRight class="h-3 w-3" />
                            <span class="font-medium text-gray-700">{{ d.nama }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" @click="router.get(route('balita.edit', { balita: d.id }))" class="flex items-center gap-2 rounded-lg">
                        <Edit class="h-4 w-4" /> Edit
                    </Button>
                    <AlertDialog>
                        <AlertDialogTrigger as-child>
                            <Button variant="outline" class="flex items-center gap-2 rounded-lg border-red-200 text-red-600 hover:bg-red-50">
                                <Trash2 class="h-4 w-4" /> Hapus
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Hapus Data Balita?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Data balita <strong>{{ d.nama }}</strong> akan dihapus permanen beserta seluruh riwayat pemeriksaan.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Batal</AlertDialogCancel>
                                <Button @click="deleteBalita" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">Hapus</Button>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-5">

                <!-- Hero -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 to-cyan-500" />
                    <CardContent class="p-6">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                            <div class="h-24 w-24 rounded-full overflow-hidden bg-blue-50 flex items-center justify-center border-4 border-white shadow-md shrink-0">
                                <img v-if="d.foto" :src="d.foto" :alt="d.nama" class="h-full w-full object-cover" />
                                <Baby v-else class="h-10 w-10 text-blue-400" />
                            </div>
                            <div class="text-center sm:text-left flex-1">
                                <h3 class="text-xl font-bold text-gray-900">{{ d.nama }}</h3>
                                <p class="font-mono text-sm text-muted-foreground mt-0.5">NIK: {{ d.nik }}</p>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                                    <Badge :class="d.is_active ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-500 border-gray-200'" class="text-xs font-semibold">
                                        {{ d.is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </Badge>
                                    <Badge :class="d.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-pink-50 text-pink-700 border-pink-200'" class="text-xs font-semibold">
                                        {{ d.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </Badge>
                                    <Badge class="bg-cyan-50 text-cyan-700 border-cyan-200 text-xs font-semibold">{{ d.umur_label }}</Badge>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600"><Calendar class="h-4 w-4" /></div>
                                Data Balita
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-3">
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Tanggal Lahir / Umur</p>
                                <p class="text-sm font-medium text-gray-700">{{ d.tgl_lahir }} ({{ d.umur_label }})</p>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Jenis Kelamin</p>
                                <p class="text-sm font-medium text-gray-700">{{ d.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600"><User class="h-4 w-4" /></div>
                                Orang Tua / Wali
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-3">
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Nama Orang Tua</p>
                                <p class="text-sm font-medium text-gray-700">{{ d.nama_orang_tua }}</p>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">No. Telepon</p>
                                <p class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                                    <Phone class="h-3.5 w-3.5 text-gray-400" /> {{ d.no_telp || '—' }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Alamat -->
                <Card class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600"><MapPin class="h-4 w-4" /></div>
                            Alamat Lengkap
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5">
                        <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 border border-gray-100 rounded-lg p-3.5">{{ d.alamat || '—' }}</p>
                    </CardContent>
                </Card>

                <!-- Chart -->
                <Card class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600"><HeartPulse class="h-4 w-4" /></div>
                            Grafik Pertumbuhan
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5">
                        <div v-if="pemeriksaan.data.length > 0" class="h-[280px]">
                            <LineChart :data="chartData" :options="chartOptions" />
                        </div>
                        <div v-else class="h-20 flex items-center justify-center text-muted-foreground border-2 border-dashed rounded-lg text-sm">
                            Belum ada data pemeriksaan untuk ditampilkan.
                        </div>
                    </CardContent>
                </Card>

                <!-- Riwayat -->
                <Card class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100 flex flex-row items-center justify-between">
                        <CardTitle class="text-base font-semibold text-gray-700">Riwayat Pemeriksaan</CardTitle>
                        <Link :href="route('pemeriksaan.create', { type: 'balita', id: d.id })">
                            <Button size="sm" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-1.5">
                                <Plus class="h-4 w-4" /> Tambah
                            </Button>
                        </Link>
                    </CardHeader>
                    <CardContent class="p-0">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50/70 border-b">
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">BB (kg)</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">TB (cm)</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">L. Kepala</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in pemeriksaan.data" :key="item.id" class="border-b hover:bg-gray-50/50 transition-colors">
                                    <td class="px-5 py-3 font-medium text-gray-700">{{ item.tgl_pemeriksaan }}</td>
                                    <td class="px-4 py-3 text-muted-foreground">{{ item.berat_badan || '-' }}</td>
                                    <td class="px-4 py-3 text-muted-foreground">{{ item.tinggi_badan || '-' }}</td>
                                    <td class="px-4 py-3 text-muted-foreground">{{ item.lingkar_kepala || '-' }}</td>
                                    <td class="px-4 py-3">
                                        <Badge :class="item.hadir ? 'bg-emerald-50 text-emerald-700 border-emerald-200 text-xs font-semibold' : 'bg-red-50 text-red-700 border-red-200 text-xs font-semibold'">
                                            {{ item.hadir ? 'Hadir' : 'Absen' }}
                                        </Badge>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50"
                                            @click="router.get(route('pemeriksaan.show', item.id))">Detail</Button>
                                    </td>
                                </tr>
                                <tr v-if="pemeriksaan.data.length === 0">
                                    <td colspan="6" class="px-5 py-10 text-center text-muted-foreground italic text-sm">
                                        Belum ada data riwayat pemeriksaan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </CardContent>
                </Card>

                <Card v-if="d.keterangan" class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="text-base font-semibold text-gray-700">Keterangan / Catatan Tambahan</CardTitle>
                    </CardHeader>
                    <CardContent class="p-5">
                        <p class="text-sm leading-relaxed text-gray-600 whitespace-pre-wrap bg-gray-50 border border-gray-100 rounded-lg p-3.5">{{ d.keterangan }}</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>