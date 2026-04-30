<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Skeleton } from '@/components/ui/skeleton';
import CardSkeleton from '@/Components/CardSkeleton.vue';
import TableSkeleton from '@/Components/TableSkeleton.vue';
import {
    Users, Baby, User, Activity, Calendar, ArrowRight,
    TrendingUp, Clock, FileText, HeartPulse, AlertTriangle,
} from 'lucide-vue-next';
import { Line, Bar } from 'vue-chartjs';
import {
    Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement,
    BarElement, Title, Tooltip, Legend, Filler, ArcElement,
} from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, ArcElement, Title, Tooltip, Legend, Filler);

const props = defineProps<{
    stats?: { total_balita: number; total_bumil: number; total_lansia: number; pemeriksaan_bulan_ini: number };
    upcomingJadwal?: UpcomingJadwal[];
    recentAktivitas?: RecentAktivitas[];
    chartData?: ChartDataItem[];
    distribusiData?: DistribusiItem[];
    alerts?: { kek_count: number; stunting_count: number; hipertensi_count: number };
    jadwalValidationQueue?: JadwalValidationItem[];
    myPosyanduJadwal?: MyPosyanduJadwalItem[];
    loading?: boolean;
}>();

const isLoading = computed(() => props.loading ?? false);
const hasAlerts = computed(() => props.alerts && (props.alerts.kek_count > 0 || props.alerts.stunting_count > 0 || props.alerts.hipertensi_count > 0));

interface UpcomingJadwal { id: string; posyandu_nama: string; tanggal: string; waktu: string }
interface RecentAktivitas { id: string; nama: string; type_label: string; tanggal: string; status: string }
interface ChartDataItem { month: string; total: number }
interface DistribusiItem { nama: string; total: number }
interface JadwalValidationItem { id: string; posyandu_nama: string; kader_nama: string; tanggal: string; created_at: string }
interface MyPosyanduJadwalItem { id: string; posyandu_nama: string; tanggal: string; waktu: string; status: string; status_label: string }

const posyanduColors = [
    '#6366f1', '#10b981', '#ef4444', '#f59e0b', '#8b5cf6', '#f97316', '#06b6d4', '#ec4899',
];

const processedBarChartData = computed(() => {
    const data = props.distribusiData || [];
    return {
        labels: data.map(() => '●'),
        datasets: [{
            label: 'Kader per Posyandu',
            backgroundColor: data.map((_, idx) => posyanduColors[idx % posyanduColors.length]),
            data: data.map((d) => d.total),
            borderRadius: 6,
            borderSkipped: false,
        }],
    };
});

const posyanduLegend = computed(() =>
    (props.distribusiData || []).map((item, idx) => ({
        nama: item.nama, color: posyanduColors[idx % posyanduColors.length], total: item.total,
    }))
);

const chartOptions = {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.06)' } },
        x: { grid: { display: false } },
    },
} as any;

const processedChartData = computed(() => ({
    labels: (props.chartData ?? []).map((d) => d.month),
    datasets: [{
        label: 'Total Pemeriksaan',
        backgroundColor: 'rgba(99,102,241,0.08)',
        borderColor: '#6366f1',
        data: (props.chartData ?? []).map((d) => d.total),
        fill: true, tension: 0.4,
        pointBackgroundColor: '#6366f1',
        pointRadius: 4,
    }],
}));

const statCards = computed(() => [
    { title: 'Total Balita', value: props.stats?.total_balita ?? 0, icon: Baby, desc: 'Aktif terpantau', gradient: 'from-blue-500 to-cyan-600', light: 'bg-blue-50 text-blue-600', route: 'balita.index' },
    { title: 'Ibu Hamil', value: props.stats?.total_bumil ?? 0, icon: User, desc: 'Pemeriksaan rutin', gradient: 'from-pink-500 to-rose-600', light: 'bg-pink-50 text-pink-600', route: 'ibu-hamil.index' },
    { title: 'Lansia', value: props.stats?.total_lansia ?? 0, icon: Users, desc: 'Kesehatan berkala', gradient: 'from-orange-400 to-amber-500', light: 'bg-orange-50 text-orange-500', route: 'lansia.index' },
    { title: 'Periksa Bulan Ini', value: props.stats?.pemeriksaan_bulan_ini ?? 0, icon: HeartPulse, desc: 'Total kunjungan', gradient: 'from-violet-600 to-indigo-600', light: 'bg-violet-50 text-violet-600', route: 'pemeriksaan.index' },
]);

function typeLabel(type: string) {
    return { ibu_hamil: 'Ibu Hamil', balita: 'Balita', lansia: 'Lansia' }[type] ?? type;
}
function typeBadgeClass(type: string) {
    return { ibu_hamil: 'bg-pink-50 text-pink-700 border-pink-200', balita: 'bg-blue-50 text-blue-700 border-blue-200', lansia: 'bg-orange-50 text-orange-700 border-orange-200' }[type] ?? 'bg-gray-100 text-gray-600';
}
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Dashboard</h2>
                    <p class="text-sm text-muted-foreground mt-0.5">Ringkasan kondisi posyandu hari ini</p>
                </div>
                <Button @click="router.get(route('pemeriksaan.create'))"
                    class="bg-violet-600 hover:bg-violet-700 text-white flex items-center gap-2 rounded-lg shadow-sm shadow-violet-200">
                    <Activity class="h-4 w-4" /> Input Pemeriksaan
                </Button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Loading -->
                <template v-if="isLoading">
                    <CardSkeleton />
                    <div class="grid gap-4 lg:grid-cols-2 mt-4">
                        <Card v-for="i in 2" :key="i">
                            <CardHeader><Skeleton class="h-6 w-40" /><Skeleton class="h-4 w-60 mt-2" /></CardHeader>
                            <CardContent class="h-[280px]"><Skeleton class="h-full w-full" /></CardContent>
                        </Card>
                    </div>
                    <TableSkeleton :rows="5" :columns="5" class="mt-6" />
                </template>

                <template v-else>

                    <!-- Health Alerts -->
                    <div v-if="hasAlerts" class="grid gap-3 sm:grid-cols-3">
                        <Card v-if="alerts!.kek_count > 0" class="border-red-200 bg-red-50/60 overflow-hidden">
                            <div class="h-1 w-full bg-red-400" />
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-1 pt-3 px-4">
                                <CardTitle class="text-xs font-bold uppercase tracking-wider text-red-700">KEK — Ibu Hamil</CardTitle>
                                <div class="p-1.5 rounded-lg bg-red-100 text-red-600"><AlertTriangle class="h-3.5 w-3.5" /></div>
                            </CardHeader>
                            <CardContent class="px-4 pb-3">
                                <div class="text-2xl font-bold text-red-700">{{ alerts!.kek_count }}</div>
                                <p class="text-xs text-red-500 mt-0.5">LILA &lt; 23.5 cm</p>
                            </CardContent>
                        </Card>
                        <Card v-if="alerts!.stunting_count > 0" class="border-amber-200 bg-amber-50/60 overflow-hidden">
                            <div class="h-1 w-full bg-amber-400" />
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-1 pt-3 px-4">
                                <CardTitle class="text-xs font-bold uppercase tracking-wider text-amber-700">Risiko Stunting</CardTitle>
                                <div class="p-1.5 rounded-lg bg-amber-100 text-amber-600"><Baby class="h-3.5 w-3.5" /></div>
                            </CardHeader>
                            <CardContent class="px-4 pb-3">
                                <div class="text-2xl font-bold text-amber-700">{{ alerts!.stunting_count }}</div>
                                <p class="text-xs text-amber-500 mt-0.5">BB/TB rendah pada balita</p>
                            </CardContent>
                        </Card>
                        <Card v-if="alerts!.hipertensi_count > 0" class="border-orange-200 bg-orange-50/60 overflow-hidden">
                            <div class="h-1 w-full bg-orange-400" />
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-1 pt-3 px-4">
                                <CardTitle class="text-xs font-bold uppercase tracking-wider text-orange-700">Hipertensi</CardTitle>
                                <div class="p-1.5 rounded-lg bg-orange-100 text-orange-600"><Activity class="h-3.5 w-3.5" /></div>
                            </CardHeader>
                            <CardContent class="px-4 pb-3">
                                <div class="text-2xl font-bold text-orange-700">{{ alerts!.hipertensi_count }}</div>
                                <p class="text-xs text-orange-500 mt-0.5">Tensi tinggi pada lansia</p>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Validation Queue (Bidan) -->
                    <Card v-if="jadwalValidationQueue?.length" class="border-amber-200 bg-amber-50/40 overflow-hidden">
                        <div class="h-1 w-full bg-amber-400" />
                        <CardHeader class="px-5 pt-4 pb-3">
                            <CardTitle class="flex items-center gap-2 text-amber-800 text-base">
                                <FileText class="h-5 w-5" /> Antrian Validasi Jadwal
                            </CardTitle>
                            <CardDescription class="text-amber-600">Jadwal yang menunggu persetujuan Anda</CardDescription>
                        </CardHeader>
                        <CardContent class="px-5 pb-4 space-y-2">
                            <div v-for="j in jadwalValidationQueue" :key="j.id"
                                class="flex items-center justify-between rounded-lg border border-amber-200 bg-white px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ j.posyandu_nama }}</p>
                                    <p class="text-xs text-muted-foreground mt-0.5">Diajukan oleh {{ j.kader_nama }} • {{ j.created_at }}</p>
                                </div>
                                <Button size="sm" variant="outline" class="border-amber-200 text-amber-700 hover:bg-amber-50"
                                    @click="router.get(route('jadwal-posyandu.show', j.id))">Lihat</Button>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- My Jadwal (Kader) -->
                    <Card v-if="myPosyanduJadwal?.length" class="border-none shadow-sm bg-white overflow-hidden">
                        <CardHeader class="px-5 pt-4 pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><Calendar class="h-4 w-4" /></div>
                                Jadwal Posyandu Saya
                            </CardTitle>
                            <CardDescription>Jadwal aktif di posyandu Anda</CardDescription>
                        </CardHeader>
                        <CardContent class="px-5 pb-4 pt-3 space-y-2">
                            <div v-for="j in myPosyanduJadwal" :key="j.id"
                                class="flex items-center justify-between rounded-lg border border-gray-100 px-4 py-3 hover:bg-gray-50/50 transition-colors">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ j.posyandu_nama }}</p>
                                    <p class="text-xs text-muted-foreground mt-0.5 flex items-center gap-1">
                                        <Clock class="h-3 w-3" /> {{ j.tanggal }} • {{ j.waktu }}
                                    </p>
                                </div>
                                <Badge :class="j.status === 'validated' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 text-xs font-semibold' : 'bg-gray-100 text-gray-500 border-gray-200 text-xs font-semibold'">
                                    {{ j.status_label }}
                                </Badge>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Stat Cards -->
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <Card v-for="stat in statCards" :key="stat.title"
                            class="border-none shadow-sm bg-white overflow-hidden cursor-pointer hover:shadow-md transition-shadow"
                            @click="router.get(route(stat.route))">
                            <CardContent class="p-4 flex items-center gap-3">
                                <div :class="['p-2.5 rounded-xl bg-gradient-to-br text-white shrink-0', stat.gradient]">
                                    <component :is="stat.icon" class="h-5 w-5" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground truncate">{{ stat.title }}</p>
                                    <p class="text-2xl font-bold text-gray-900 leading-tight">{{ stat.value }}</p>
                                    <p class="text-xs text-muted-foreground">{{ stat.desc }}</p>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Jadwal Terdekat -->
                    <Card class="border-none shadow-sm bg-white overflow-hidden">
                        <CardHeader class="px-5 pt-4 pb-3 border-b border-gray-100 flex flex-row items-center justify-between">
                            <div>
                                <CardTitle class="text-base font-semibold text-gray-700 flex items-center gap-2">
                                    <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><Calendar class="h-4 w-4" /></div>
                                    Jadwal Terdekat
                                </CardTitle>
                                <CardDescription class="text-xs mt-0.5 ml-9">Agenda posyandu mendatang</CardDescription>
                            </div>
                            <Button variant="ghost" size="sm" class="text-violet-600 hover:text-violet-700 hover:bg-violet-50"
                                @click="router.get(route('jadwal-posyandu.index'))">
                                Lihat Semua <ArrowRight class="ml-1.5 h-3.5 w-3.5" />
                            </Button>
                        </CardHeader>
                        <CardContent class="px-5 pb-4 pt-3 space-y-2">
                            <div v-for="j in upcomingJadwal" :key="j.id"
                                class="flex items-center gap-3 rounded-lg border border-gray-100 px-4 py-3 hover:bg-gray-50/50 transition-colors cursor-pointer"
                                @click="router.get(route('jadwal-posyandu.index'))">
                                <div class="h-9 w-9 rounded-full bg-violet-50 flex items-center justify-center shrink-0">
                                    <Calendar class="h-4 w-4 text-violet-500" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ j.posyandu_nama }}</p>
                                    <p class="text-xs text-muted-foreground flex items-center gap-1 mt-0.5">
                                        <Clock class="h-3 w-3" /> {{ j.tanggal }} • {{ j.waktu }}
                                    </p>
                                </div>
                                <ArrowRight class="h-4 w-4 text-gray-300 shrink-0" />
                            </div>
                            <div v-if="!upcomingJadwal?.length"
                                class="py-10 flex flex-col items-center justify-center text-muted-foreground">
                                <div class="p-3 rounded-full bg-gray-100 mb-3"><Calendar class="h-6 w-6 text-gray-300" /></div>
                                <p class="text-sm font-medium text-gray-400">Tidak ada jadwal terdekat</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Recent Activity -->
                    <Card class="border-none shadow-sm bg-white overflow-hidden">
                        <CardHeader class="px-5 pt-4 pb-3 border-b border-gray-100 flex flex-row items-center justify-between">
                            <div>
                                <CardTitle class="text-base font-semibold text-gray-700">Aktivitas Terbaru</CardTitle>
                                <CardDescription class="text-xs mt-0.5">Pemeriksaan yang baru diinput</CardDescription>
                            </div>
                            <Button variant="outline" size="sm" class="rounded-lg"
                                @click="router.get(route('pemeriksaan.index'))">Riwayat Lengkap</Button>
                        </CardHeader>
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-gray-50/70 hover:bg-gray-50/70">
                                    <TableHead class="pl-5 text-xs font-bold uppercase tracking-wider text-gray-500 h-9">Peserta</TableHead>
                                    <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500 h-9">Kategori</TableHead>
                                    <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500 h-9">Tanggal</TableHead>
                                    <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500 h-9">Status</TableHead>
                                    <TableHead class="text-right pr-5 h-9" />
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="item in recentAktivitas" :key="item.id" class="hover:bg-gray-50/50">
                                    <TableCell class="pl-5 font-semibold text-sm text-gray-800">{{ item.nama }}</TableCell>
                                    <TableCell>
                                        <Badge class="text-xs font-semibold" :class="typeBadgeClass(item.type_label)">
                                            {{ typeLabel(item.type_label) }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="text-muted-foreground text-sm">{{ item.tanggal }}</TableCell>
                                    <TableCell class="text-center">
                                        <Badge :class="item.status === 'Hadir' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 text-xs font-semibold' : 'bg-gray-100 text-gray-500 border-gray-200 text-xs font-semibold'">
                                            {{ item.status }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="text-right pr-5">
                                        <Button variant="ghost" size="sm" class="h-7 text-xs text-violet-600 hover:text-violet-700 hover:bg-violet-50"
                                            @click="router.get(route('pemeriksaan.show', item.id))">Detail</Button>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="!recentAktivitas?.length">
                                    <TableCell colspan="5" class="py-12 text-center text-muted-foreground text-sm">
                                        Belum ada aktivitas terbaru
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </Card>

                    <!-- Charts -->
                    <div class="grid gap-4 lg:grid-cols-2">
                        <Card class="border-none shadow-sm bg-white overflow-hidden">
                            <CardHeader class="px-5 pt-4 pb-3 border-b border-gray-100">
                                <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                    <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><TrendingUp class="h-4 w-4" /></div>
                                    Trend Pemeriksaan
                                </CardTitle>
                                <CardDescription class="text-xs mt-0.5 ml-9">Jumlah kunjungan 6 bulan terakhir</CardDescription>
                            </CardHeader>
                            <CardContent class="p-5 h-[260px]">
                                <Line :data="processedChartData" :options="chartOptions" />
                            </CardContent>
                        </Card>

                        <Card class="border-none shadow-sm bg-white overflow-hidden">
                            <CardHeader class="px-5 pt-4 pb-3 border-b border-gray-100">
                                <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                    <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><Users class="h-4 w-4" /></div>
                                    Distribusi Kader
                                </CardTitle>
                                <CardDescription class="text-xs mt-0.5 ml-9">Jumlah kader per posyandu</CardDescription>
                            </CardHeader>
                            <CardContent class="p-5 space-y-3">
                                <div class="h-[180px]">
                                    <Bar v-if="distribusiData?.length" :data="processedBarChartData" :options="chartOptions" />
                                    <div v-else class="flex h-full flex-col items-center justify-center text-muted-foreground">
                                        <Users class="mb-2 h-8 w-8 opacity-20" />
                                        <p class="text-xs">Data tidak tersedia</p>
                                    </div>
                                </div>
                                <div v-if="posyanduLegend.length" class="border-t border-gray-100 pt-2 grid grid-cols-2 gap-1.5">
                                    <div v-for="(item, idx) in posyanduLegend" :key="idx" class="flex items-center gap-2">
                                        <div class="h-2.5 w-2.5 rounded-full shrink-0" :style="{ backgroundColor: item.color }" />
                                        <span class="text-xs text-gray-600 truncate">{{ item.nama }}</span>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
