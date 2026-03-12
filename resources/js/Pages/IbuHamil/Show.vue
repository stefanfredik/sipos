<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Activity, ArrowLeft, Calendar, Edit, MapPin, Phone, Trash, TrendingUp, User } from 'lucide-vue-next';
import { computed } from 'vue';
import {
    Line as LineChart
} from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    LinearScale,
    PointElement,
    CategoryScale,
} from 'chart.js';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    LinearScale,
    PointElement,
    CategoryScale
);

const props = defineProps<{
    ibu_hamil: {
        data: {
            id: string;
            nik: string;
            nama: string;
            foto: string | null;
            tgl_lahir: string;
            umur_label: string;
            kehamilan_keberapa: number;
            jarak_anak: number | null;
            usia_kehamilan: number;
            no_telp: string;
            alamat: string;
            keterangan: string | null;
            is_active: boolean;
            created_at: string;
        }
    };
    pemeriksaan: { data: any[] };
}>();

const data = props.ibu_hamil.data;

const chartData = computed(() => ({
    labels: props.pemeriksaan.data.map(p => p.tgl_pemeriksaan),
    datasets: [
        {
            label: 'Berat Badan (kg)',
            backgroundColor: '#ec4899',
            borderColor: '#ec4899',
            data: props.pemeriksaan.data.map(p => p.berat_badan),
            tension: 0.3
        },
        {
            label: 'Tekanan Darah (Sistole)',
            backgroundColor: '#ef4444',
            borderColor: '#ef4444',
            data: props.pemeriksaan.data.map(p => p.sistole),
            tension: 0.3
        }
    ]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom' as const,
        }
    },
    scales: {
        y: {
            beginAtZero: false
        }
    }
};

const deleteIbuHamil = () => {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('ibu-hamil.destroy', data.id));
    }
};
</script>

<template>
    <Head :title="'Detail ' + data.nama" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('ibu-hamil.index')">
                        <Button variant="outline" size="icon">
                            <ArrowLeft class="h-4 w-4" />
                        </Button>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Ibu Hamil</h2>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('ibu-hamil.edit', data.id)">
                        <Button variant="outline" class="flex items-center gap-2">
                            <Edit class="h-4 w-4" />
                            Edit
                        </Button>
                    </Link>
                    <Button variant="destructive" class="flex items-center gap-2" @click="deleteIbuHamil">
                        <Trash class="h-4 w-4" />
                        Hapus
                    </Button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Profile Card -->
                    <Card class="md:col-span-1">
                        <CardContent class="pt-6 flex flex-col items-center text-center">
                            <div class="h-32 w-32 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-4 mb-4">
                                <img v-if="data.foto" :src="data.foto" :alt="data.nama" class="h-full w-full object-cover" />
                                <User v-else class="h-16 w-16 text-gray-400" />
                            </div>
                            <h3 class="text-xl font-bold">{{ data.nama }}</h3>
                            <p class="text-sm text-muted-foreground mb-4">{{ data.nik }}</p>
                            <Badge :variant="data.is_active ? 'default' : 'destructive'" class="mb-4">
                                {{ data.is_active ? 'Status: Aktif' : 'Status: Tidak Aktif' }}
                            </Badge>
                        </CardContent>
                    </Card>

                    <!-- Info Card -->
                    <Card class="md:col-span-2">
                        <CardHeader>
                            <CardTitle>Informasi Personal & Kehamilan</CardTitle>
                            <CardDescription>Detail lengkap riwayat dan kondisi saat ini.</CardDescription>
                        </CardHeader>
                        <CardContent class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                            <div class="flex items-start gap-3">
                                <Calendar class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Tanggal Lahir / Umur</p>
                                    <p class="text-sm text-muted-foreground">{{ data.tgl_lahir }} ({{ data.umur_label }})</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <Phone class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">No. Telepon</p>
                                    <p class="text-sm text-muted-foreground">{{ data.no_telp }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <Activity class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Kehamilan Ke</p>
                                    <p class="text-sm text-muted-foreground">{{ data.kehamilan_keberapa }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <Activity class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Usia Kehamilan</p>
                                    <p class="text-sm text-muted-foreground">{{ data.usia_kehamilan }} Minggu</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 md:col-span-2">
                                <MapPin class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Alamat</p>
                                    <p class="text-sm text-muted-foreground">{{ data.alamat }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Health Chart -->
                <Card>
                    <CardHeader>
                        <CardTitle>Monitoring Kesehatan</CardTitle>
                        <CardDescription>Tren berat badan dan tekanan darah selama kehamilan.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="h-[300px] w-full" v-if="pemeriksaan.data.length > 0">
                            <LineChart :data="chartData" :options="chartOptions" />
                        </div>
                        <div v-else class="h-20 flex items-center justify-center text-muted-foreground border-2 border-dashed rounded-lg">
                            Belum ada data pemeriksaan untuk ditampilkan dalam grafik.
                        </div>
                    </CardContent>
                </Card>

                <!-- History Table -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle>Riwayat Pemeriksaan</CardTitle>
                            <CardDescription>Semua catatan kesehatan selama kehamilan.</CardDescription>
                        </div>
                        <Link :href="route('pemeriksaan.create', { type: 'ibu_hamil', id: data.id })">
                            <Button size="sm">Tambah Data</Button>
                        </Link>
                    </CardHeader>
                    <CardContent>
                        <div class="rounded-md border overflow-hidden">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-muted/50 border-b">
                                        <th class="px-4 py-3 text-left font-medium">Tanggal</th>
                                        <th class="px-4 py-3 text-left font-medium">Usia Hamil</th>
                                        <th class="px-4 py-3 text-left font-medium">BB (kg)</th>
                                        <th class="px-4 py-3 text-left font-medium">Tensi</th>
                                        <th class="px-4 py-3 text-left font-medium">KEK</th>
                                        <th class="px-4 py-3 text-right font-medium">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in pemeriksaan.data" :key="item.id" class="border-b transition-colors hover:bg-muted/30">
                                        <td class="px-4 py-3 font-medium">{{ item.tgl_pemeriksaan }}</td>
                                        <td class="px-4 py-3">{{ item.usia_kehamilan || '-' }} Mgg</td>
                                        <td class="px-4 py-3">{{ item.berat_badan || '-' }}</td>
                                        <td class="px-4 py-3 font-mono text-xs">{{ item.tensi_darah || '-' }}</td>
                                        <td class="px-4 py-3">
                                            <Badge :variant="item.kek_status ? 'destructive' : 'outline'">
                                                {{ item.kek_status ? 'KEK' : 'Normal' }}
                                            </Badge>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <Button variant="ghost" size="sm" @click="router.get(route('pemeriksaan.show', item.id))">Detail</Button>
                                        </td>
                                    </tr>
                                    <tr v-if="pemeriksaan.data.length === 0">
                                        <td colspan="6" class="px-4 py-8 text-center text-muted-foreground italic">
                                            Belum ada data riwayat pemeriksaan.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>

                <!-- Additional Info -->
                <Card v-if="data.keterangan">
                    <CardHeader>
                        <CardTitle>Keterangan Medis / Tambahan</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-wrap">
                            {{ data.keterangan }}
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
