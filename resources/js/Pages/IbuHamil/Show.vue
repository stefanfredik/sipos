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
import { ArrowLeft, Edit, Trash2, User, Phone, MapPin, ChevronRight, Calendar, Activity, HeartPulse, Plus } from 'lucide-vue-next';
import { computed } from 'vue';
import { Line as LineChart } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, LinearScale, PointElement, CategoryScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, LinearScale, PointElement, CategoryScale);

const props = defineProps<{
    ibu_hamil: {
        data: {
            id: string; nik: string; nama: string; foto: string | null;
            tgl_lahir: string; umur_label: string; kehamilan_keberapa: number;
            jarak_anak: number | null; usia_kehamilan: number; no_telp: string;
            alamat: string; keterangan: string | null; is_active: boolean; created_at: string;
        }
    };
    pemeriksaan: { data: any[] };
}>();

const d = props.ibu_hamil.data;

const chartData = computed(() => ({
    labels: props.pemeriksaan.data.map(p => p.tgl_pemeriksaan),
    datasets: [
        { label: 'Berat Badan (kg)', backgroundColor: '#ec4899', borderColor: '#ec4899', data: props.pemeriksaan.data.map(p => p.berat_badan), tension: 0.3 },
        { label: 'Tekanan Darah (Sistole)', backgroundColor: '#ef4444', borderColor: '#ef4444', data: props.pemeriksaan.data.map(p => p.sistole), tension: 0.3 },
    ]
}));

const chartOptions = {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' as const } },
    scales: { y: { beginAtZero: false } }
};

function deleteIbuHamil() {
    router.delete(route('ibu-hamil.destroy', d.id));
}
</script>

<template>
    <Head :title="'Detail ' + d.nama" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Button variant="ghost" size="icon" @click="router.get(route('ibu-hamil.index'))"
                        class="h-9 w-9 rounded-full hover:bg-white/50">
                        <ArrowLeft class="h-5 w-5 text-gray-600" />
                    </Button>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-gray-900">Detail Ibu Hamil</h2>
                        <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                            <span>Data Ibu Hamil</span>
                            <ChevronRight class="h-3 w-3" />
                            <span class="font-medium text-gray-700">{{ d.nama }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" @click="router.get(route('ibu-hamil.edit', d.id))" class="flex items-center gap-2 rounded-lg">
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
                                <AlertDialogTitle>Hapus Data Ibu Hamil?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Data ibu hamil <strong>{{ d.nama }}</strong> akan dihapus permanen beserta seluruh riwayat pemeriksaan.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Batal</AlertDialogCancel>
                                <AlertDialogAction @click="deleteIbuHamil" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">Hapus</AlertDialogAction>
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
                    <div class="h-1.5 w-full bg-gradient-to-r from-pink-500 to-rose-600" />
                    <CardContent class="p-6">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                            <div class="h-24 w-24 rounded-full overflow-hidden bg-pink-50 flex items-center justify-center border-4 border-white shadow-md shrink-0">
                                <img v-if="d.foto" :src="d.foto" :alt="d.nama" class="h-full w-full object-cover" />
                                <User v-else class="h-10 w-10 text-pink-400" />
                            </div>
                            <div class="text-center sm:text-left flex-1">
                                <h3 class="text-xl font-bold text-gray-900">{{ d.nama }}</h3>
                                <p class="font-mono text-sm text-muted-foreground mt-0.5">NIK: {{ d.nik }}</p>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                                    <Badge :class="d.is_active ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-500 border-gray-200'" class="text-xs font-semibold">
                                        {{ d.is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </Badge>
                                    <Badge class="bg-pink-100 text-pink-700 border-pink-200 text-xs font-semibold">
                                        Kehamilan ke-{{ d.kehamilan_keberapa }}
                                    </Badge>
                                    <Badge class="bg-rose-50 text-rose-700 border-rose-200 text-xs font-semibold">
                                        {{ d.usia_kehamilan }} Minggu
                                    </Badge>
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
                                <div class="p-1.5 rounded-lg bg-pink-50 text-pink-600"><Calendar class="h-4 w-4" /></div>
                                Data Pribadi
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-3">
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Tanggal Lahir / Umur</p>
                                <p class="text-sm font-medium text-gray-700">{{ d.tgl_lahir }} ({{ d.umur_label }})</p>
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

                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-pink-50 text-pink-600"><Activity class="h-4 w-4" /></div>
                                Data Kehamilan
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-3">
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Usia Kehamilan</p>
                                <p class="text-sm font-medium text-gray-700">{{ d.usia_kehamilan }} Minggu</p>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Jarak Anak</p>
                                <p class="text-sm font-medium text-gray-700">{{ d.jarak_anak ? d.jarak_anak + ' Tahun' : 'Anak pertama' }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Alamat -->
                <Card class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-pink-50 text-pink-600"><MapPin class="h-4 w-4" /></div>
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
                            <div class="p-1.5 rounded-lg bg-pink-50 text-pink-600"><HeartPulse class="h-4 w-4" /></div>
                            Monitoring Kesehatan
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

                <!-- History -->
                <Card class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100 flex flex-row items-center justify-between">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            Riwayat Pemeriksaan
                        </CardTitle>
                        <Link :href="route('pemeriksaan.create', { type: 'ibu_hamil', id: d.id })">
                            <Button size="sm" class="bg-pink-600 hover:bg-pink-700 text-white rounded-lg flex items-center gap-1.5">
                                <Plus class="h-4 w-4" /> Tambah
                            </Button>
                        </Link>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div class="overflow-hidden">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50/70 border-b">
                                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Usia Hamil</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">BB (kg)</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Tensi</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">KEK</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in pemeriksaan.data" :key="item.id" class="border-b hover:bg-gray-50/50 transition-colors">
                                        <td class="px-5 py-3 font-medium text-gray-700">{{ item.tgl_pemeriksaan }}</td>
                                        <td class="px-4 py-3 text-muted-foreground">{{ item.usia_kehamilan || '-' }} Mgg</td>
                                        <td class="px-4 py-3 text-muted-foreground">{{ item.berat_badan || '-' }}</td>
                                        <td class="px-4 py-3 font-mono text-xs text-muted-foreground">{{ item.tensi_darah || '-' }}</td>
                                        <td class="px-4 py-3">
                                            <Badge :class="item.kek_status ? 'bg-red-50 text-red-700 border-red-200 text-xs font-semibold' : 'bg-gray-50 text-gray-600 border-gray-200 text-xs font-semibold'">
                                                {{ item.kek_status ? 'KEK' : 'Normal' }}
                                            </Badge>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <Button variant="ghost" size="sm" class="text-pink-600 hover:text-pink-700 hover:bg-pink-50"
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
                        </div>
                    </CardContent>
                </Card>

                <!-- Keterangan -->
                <Card v-if="d.keterangan" class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="text-base font-semibold text-gray-700">Keterangan Medis</CardTitle>
                    </CardHeader>
                    <CardContent class="p-5">
                        <p class="text-sm leading-relaxed text-gray-600 whitespace-pre-wrap bg-gray-50 border border-gray-100 rounded-lg p-3.5">{{ d.keterangan }}</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
