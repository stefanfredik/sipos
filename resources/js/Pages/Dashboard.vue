<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Skeleton } from '@/components/ui/skeleton';
import CardSkeleton from '@/Components/CardSkeleton.vue';
import TableSkeleton from '@/Components/TableSkeleton.vue';
import {
    Users,
    Baby,
    User,
    Activity,
    Calendar,
    ArrowRight,
    TrendingUp,
    Clock,
    FileText,
} from 'lucide-vue-next';
import { Line, Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend,
    Filler,
    ArcElement,
} from 'chart.js';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
    Filler,
);

const props = defineProps<{
    stats?: {
        total_balita: number;
        total_bumil: number;
        total_lansia: number;
        pemeriksaan_bulan_ini: number;
    };
    upcomingJadwal?: UpcomingJadwal[];
    recentAktivitas?: RecentAktivitas[];
    chartData?: ChartDataItem[];
    distribusiData?: DistribusiItem[];
    alerts?: {
        kek_count: number;
        stunting_count: number;
        hipertensi_count: number;
    };
    jadwalValidationQueue?: JadwalValidationItem[];
    myPosyanduJadwal?: MyPosyanduJadwalItem[];
    loading?: boolean;
}>();

const isLoading = computed(() => props.loading ?? false);

interface UpcomingJadwal {
    id: string;
    posyandu_nama: string;
    tanggal: string;
    waktu: string;
}

interface RecentAktivitas {
    id: string;
    nama: string;
    type_label: string;
    tanggal: string;
    status: string;
}

interface ChartDataItem {
    month: string;
    total: number;
}

interface DistribusiItem {
    nama: string;
    total: number;
}

interface JadwalValidationItem {
    id: string;
    posyandu_nama: string;
    kader_nama: string;
    tanggal: string;
    created_at: string;
}

interface MyPosyanduJadwalItem {
    id: string;
    posyandu_nama: string;
    tanggal: string;
    waktu: string;
    status: string;
    status_label: string;
}

const barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        y: { beginAtZero: true, grid: { color: 'hsl(var(--border))' } },
        x: { grid: { display: false } },
    },
} as any;

// Warna-warna untuk setiap posyandu
const posyanduColors = [
    'hsl(var(--primary))',           // Blue
    'hsl(142 72% 29%)',              // Green
    'hsl(352 96% 56%)',              // Red
    'hsl(47 96% 53%)',               // Yellow
    'hsl(262 80% 50%)',              // Purple
    'hsl(25 95% 53%)',               // Orange
    'hsl(199 89% 48%)',              // Sky
    'hsl(280 85% 58%)',              // Violet
];

const processedBarChartData = computed(() => {
    const data = props.distribusiData || [];
    return {
        labels: data.map((_, idx) => `●`), // Label hanya dot
        datasets: [
            {
                label: 'Kader per Posyandu',
                backgroundColor: data.map((_, idx) => posyanduColors[idx % posyanduColors.length]),
                data: data.map((d) => d.total),
                borderRadius: 6,
                borderSkipped: false,
            },
        ],
    };
});

// Data untuk legend custom
const posyanduLegend = computed(() => {
    return (props.distribusiData || []).map((item, idx) => ({
        nama: item.nama,
        color: posyanduColors[idx % posyanduColors.length],
        total: item.total,
    }));
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        y: { beginAtZero: true, grid: { color: 'hsl(var(--border))' } },
        x: { grid: { display: false } },
    },
};

const processedChartData = computed(() => ({
    labels: (props.chartData ?? []).map((d) => d.month),
    datasets: [
        {
            label: 'Total Pemeriksaan',
            backgroundColor: 'hsl(var(--primary) / 0.1)',
            borderColor: 'hsl(var(--primary))',
            data: (props.chartData ?? []).map((d) => d.total),
            fill: true,
            tension: 0.4,
        },
    ],
}));

const statCards = computed(() => [
    {
        title: 'Total Balita',
        value: props.stats?.total_balita ?? 0,
        icon: Baby,
        desc: 'Aktif terpantau',
        variant: 'default' as const,
    },
    {
        title: 'Ibu Hamil',
        value: props.stats?.total_bumil ?? 0,
        icon: User,
        desc: 'Pemeriksaan rutin',
        variant: 'default' as const,
    },
    {
        title: 'Lansia',
        value: props.stats?.total_lansia ?? 0,
        icon: Users,
        desc: 'Kesehatan berkala',
        variant: 'default' as const,
    },
    {
        title: 'Periksa Bulan Ini',
        value: props.stats?.pemeriksaan_bulan_ini ?? 0,
        icon: Activity,
        desc: 'Total kunjungan',
        variant: 'primary' as const,
    },
]);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold">Dashboard</h2>
                </div>
                <Link :href="route('pemeriksaan.create')">
                    <Button size="sm">
                        <Activity class="mr-2 h-4 w-4" />
                        Input Pemeriksaan
                    </Button>
                </Link>
            </div>
        </template>

        <div class="space-y-4">
            <!-- Loading State -->
            <div v-if="isLoading">
                <CardSkeleton />
                
                <div class="grid gap-3 lg:grid-cols-7 mt-4">
                    <Card class="lg:col-span-4">
                        <CardHeader>
                            <Skeleton class="h-6 w-40" />
                            <Skeleton class="h-4 w-60 mt-2" />
                        </CardHeader>
                        <CardContent class="h-[300px]">
                            <Skeleton class="h-full w-full" />
                        </CardContent>
                    </Card>
                    <Card class="lg:col-span-3">
                        <CardHeader>
                            <Skeleton class="h-6 w-40" />
                            <Skeleton class="h-4 w-60 mt-2" />
                        </CardHeader>
                        <CardContent class="h-[300px]">
                            <Skeleton class="h-full w-full" />
                        </CardContent>
                    </Card>
                </div>

                <TableSkeleton :rows="5" :columns="5" class="mt-6" />
            </div>

            <!-- Content -->
            <template v-else>
            <!-- Alerts -->
            <div
                v-if="
                    alerts &&
                    (alerts.kek_count > 0 ||
                        alerts.stunting_count > 0 ||
                        alerts.hipertensi_count > 0)
                "
                class="grid gap-3 sm:grid-cols-3"
            >
                <Card
                    v-if="alerts.kek_count > 0"
                    class="border-destructive/50 bg-destructive/5"
                >
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-1"
                    >
                        <CardTitle class="text-xs font-medium text-destructive"
                            >KEK (Kurang Energi Kronis)</CardTitle
                        >
                        <Activity class="h-3.5 w-3.5 text-destructive" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-lg font-bold text-destructive">
                            {{ alerts.kek_count }}
                        </div>
                        <p class="mt-0.5 text-xs text-destructive/70">
                            Ibu hamil dengan LILA &lt; 23.5 cm
                        </p>
                    </CardContent>
                </Card>
                <Card
                    v-if="alerts.stunting_count > 0"
                    class="border-destructive/50 bg-destructive/5"
                >
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-1"
                    >
                        <CardTitle class="text-xs font-medium text-destructive"
                            >Risiko Stunting</CardTitle
                        >
                        <Baby class="h-3.5 w-3.5 text-destructive" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-lg font-bold text-destructive">
                            {{ alerts.stunting_count }}
                        </div>
                        <p class="mt-0.5 text-xs text-destructive/70">
                            Balita dengan BB/TB rendah
                        </p>
                    </CardContent>
                </Card>
                <Card
                    v-if="alerts.hipertensi_count > 0"
                    class="border-destructive/50 bg-destructive/5"
                >
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-1"
                    >
                        <CardTitle class="text-xs font-medium text-destructive"
                            >Hipertensi</CardTitle
                        >
                        <Activity class="h-3.5 w-3.5 text-destructive" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-lg font-bold text-destructive">
                            {{ alerts.hipertensi_count }}
                        </div>
                        <p class="mt-0.5 text-xs text-destructive/70">
                            Lansia dengan tensi tinggi
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Bidan: Validation Queue -->
            <Card
                v-if="jadwalValidationQueue?.length"
                class="border-amber-500/50 bg-amber-50"
            >
                <CardHeader>
                    <CardTitle class="flex items-center gap-2 text-amber-700">
                        <FileText class="h-5 w-5" />
                        Antrian Validasi Jadwal
                    </CardTitle>
                    <CardDescription class="text-amber-600"
                        >Jadwal yang menunggu validasi Anda</CardDescription
                    >
                </CardHeader>
                <CardContent class="space-y-3">
                    <div
                        v-for="j in jadwalValidationQueue"
                        :key="j.id"
                        class="flex items-center justify-between rounded-lg border border-amber-200 bg-white p-2"
                    >
                        <div>
                            <p class="text-xs font-medium">
                                {{ j.posyandu_nama }}
                            </p>
                            <p class="text-xs text-muted-foreground">
                                Diajukan oleh {{ j.kader_nama }} •
                                {{ j.created_at }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <Link :href="route('jadwal-posyandu.show', j.id)">
                                <Button size="sm" variant="outline"
                                    >Lihat</Button
                                >
                            </Link>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Kader: My Posyandu Jadwal -->
            <Card v-if="myPosyanduJadwal?.length">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Calendar class="h-5 w-5" />
                        Jadwal Posyandu Saya
                    </CardTitle>
                    <CardDescription>Jadwal di posyandu Anda</CardDescription>
                </CardHeader>
                <CardContent class="space-y-3">
                    <div
                        v-for="j in myPosyanduJadwal"
                        :key="j.id"
                        class="flex items-center justify-between rounded-lg border p-2"
                    >
                        <div>
                            <p class="text-xs font-medium">
                                {{ j.posyandu_nama }}
                            </p>
                            <p class="text-xs text-muted-foreground">
                                {{ j.tanggal }} • {{ j.waktu }}
                            </p>
                        </div>
                        <Badge
                            :variant="
                                j.status === 'validated'
                                    ? 'default'
                                    : 'secondary'
                            "
                        >
                            {{ j.status_label }}
                        </Badge>
                    </div>
                </CardContent>
            </Card>

            <!-- Stats -->
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <Card
                    v-for="stat in statCards"
                    :key="stat.title"
                    :class="
                        stat.variant === 'primary'
                            ? 'bg-primary text-primary-foreground'
                            : ''
                    "
                >
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-1"
                    >
                        <CardTitle
                            class="text-xs font-medium"
                            :class="
                                stat.variant === 'primary'
                                    ? 'text-primary-foreground/80'
                                    : 'text-muted-foreground'
                            "
                        >
                            {{ stat.title }}
                        </CardTitle>
                        <component
                            :is="stat.icon"
                            class="h-3.5 w-3.5"
                            :class="
                                stat.variant === 'primary'
                                    ? 'text-primary-foreground/70'
                                    : 'text-muted-foreground'
                            "
                        />
                    </CardHeader>
                    <CardContent class="pt-2">
                        <div class="text-xl font-bold">{{ stat.value }}</div>
                        <p
                            class="mt-0.5 text-xs"
                            :class="
                                stat.variant === 'primary'
                                    ? 'text-primary-foreground/70'
                                    : 'text-muted-foreground'
                            "
                        >
                            {{ stat.desc }}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Chart + Jadwal -->
            <Card class="lg:col-span-7">
                <CardHeader
                    class="flex flex-row items-center justify-between pb-2"
                >
                    <div>
                        <CardTitle class="text-base">Jadwal Terdekat</CardTitle>
                        <CardDescription class="text-xs">Agenda posyandu</CardDescription>
                    </div>
                    <Link :href="route('jadwal-posyandu.index')">
                        <Button variant="ghost" size="sm"
                            >Lihat Semua</Button
                        >
                    </Link>
                </CardHeader>
                <CardContent class="space-y-2 pt-2">
                    <div
                        v-for="j in upcomingJadwal"
                        :key="j.id"
                        class="flex items-center gap-2 rounded-lg border p-2 transition-colors hover:bg-muted/50"
                    >
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary"
                        >
                            <Calendar class="h-3.5 w-3.5" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p
                                class="truncate text-xs leading-none font-medium"
                            >
                                {{ j.posyandu_nama }}
                            </p>
                            <p
                                class="mt-0.5 flex items-center gap-1 text-xs text-muted-foreground"
                            >
                                <Clock class="h-2.5 w-2.5" />
                                {{ j.tanggal }} • {{ j.waktu }}
                            </p>
                        </div>
                        <Link :href="route('jadwal-posyandu.index')">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="h-8 w-8 shrink-0"
                            >
                                <ArrowRight class="h-4 w-4" />
                            </Button>
                        </Link>
                    </div>
                    <div
                        v-if="!upcomingJadwal?.length"
                        class="flex flex-col items-center justify-center py-8 text-muted-foreground"
                    >
                        <Calendar class="mb-2 h-10 w-10 opacity-20" />
                        <p class="text-sm">Tidak ada jadwal terdekat</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Recent Activity -->
            <Card>
                <CardHeader class="flex flex-row items-center justify-between pb-2">
                    <div>
                        <CardTitle class="text-base">Aktivitas Terbaru</CardTitle>
                        <CardDescription class="text-xs"
                            >Pemeriksaan yang baru diinput</CardDescription
                        >
                    </div>
                    <Link :href="route('pemeriksaan.index')">
                        <Button variant="outline" size="sm"
                            >Riwayat Lengkap</Button
                        >
                    </Link>
                </CardHeader>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="text-xs h-8">Peserta</TableHead>
                            <TableHead class="text-xs h-8">Kategori</TableHead>
                            <TableHead class="text-xs h-8">Tanggal</TableHead>
                            <TableHead class="text-xs h-8">Status</TableHead>
                            <TableHead class="text-xs text-right h-8">Aksi</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="item in recentAktivitas"
                            :key="item.id"
                        >
                            <TableCell class="font-medium text-xs py-2">{{
                                item.nama
                            }}</TableCell>
                            <TableCell class="text-xs py-2">
                                <Badge variant="secondary" class="text-xs">{{
                                    item.type_label
                                }}</Badge>
                            </TableCell>
                            <TableCell class="text-muted-foreground text-xs py-2">{{
                                item.tanggal
                            }}</TableCell>
                            <TableCell class="text-xs py-2">
                                <Badge
                                    :variant="
                                        item.status === 'Hadir'
                                            ? 'default'
                                            : 'secondary'
                                    "
                                    class="text-xs"
                                >
                                    {{ item.status }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-right py-2">
                                <Link
                                    :href="route('pemeriksaan.show', item.id)"
                                >
                                    <Button variant="ghost" size="sm" class="h-7 text-xs"
                                        >Detail</Button
                                    >
                                </Link>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!recentAktivitas?.length">
                            <TableCell
                                colspan="5"
                                class="h-24 text-center text-muted-foreground"
                            >
                                Belum ada aktivitas terbaru
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <!-- Charts at the bottom -->
            <div class="grid gap-3 lg:grid-cols-2">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-base">Trend Pemeriksaan</CardTitle>
                        <CardDescription class="text-xs"
                            >Jumlah kunjungan 6 bulan terakhir</CardDescription
                        >
                    </CardHeader>
                    <CardContent class="h-[250px] pt-2">
                        <Line
                            :data="processedChartData"
                            :options="chartOptions"
                        />
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-base">Distribusi Kader</CardTitle>
                        <CardDescription class="text-xs">Per Posyandu</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3 pt-2">
                        <div class="h-[250px]">
                            <Bar
                                v-if="distribusiData?.length"
                                :data="processedBarChartData"
                                :options="barChartOptions"
                            />
                            <div
                                v-else
                                class="flex h-full flex-col items-center justify-center text-muted-foreground"
                            >
                                <Users class="mb-2 h-10 w-10 opacity-20" />
                                <p class="text-xs">Data tidak tersedia</p>
                            </div>
                        </div>
                        <!-- Custom Legend -->
                        <div v-if="posyanduLegend.length" class="space-y-1.5 border-t pt-2">
                            <p class="text-xs font-medium text-muted-foreground">Keterangan:</p>
                            <div class="grid grid-cols-2 gap-1.5">
                                <div
                                    v-for="(item, idx) in posyanduLegend"
                                    :key="idx"
                                    class="flex items-center gap-2"
                                >
                                    <div
                                        class="h-3 w-3 rounded-full shrink-0"
                                        :style="{ backgroundColor: item.color }"
                                    ></div>
                                    <span class="text-xs truncate">{{ item.nama }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
            </template>
        </div>
    </AuthenticatedLayout>
</template>
