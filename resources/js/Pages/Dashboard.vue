<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table'
import {
    Users, Baby, User, Activity, Calendar, ArrowRight, TrendingUp, Clock,
} from 'lucide-vue-next'
import { Line, Bar } from 'vue-chartjs'
import {
    Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement,
    Title, Tooltip, Legend, Filler, ArcElement,
} from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, ArcElement, Title, Tooltip, Legend, Filler)

const barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        y: { beginAtZero: true, grid: { color: 'hsl(var(--border))' } },
        x: { grid: { display: false } },
    },
}

const processedBarChartData = {
    labels: (props.distribusiData || []).map(d => d.nama),
    datasets: [{
        label: 'Kader per Posyandu',
        backgroundColor: 'hsl(var(--primary))',
        data: (props.distribusiData || []).map(d => d.total),
        borderRadius: 6,
    }],
}

interface UpcomingJadwal {
    id: string
    posyandu_nama: string
    tanggal: string
    waktu: string
}

interface RecentAktivitas {
    id: string
    nama: string
    type_label: string
    tanggal: string
    status: string
}

interface ChartDataItem {
    month: string
    total: number
}

interface DistribusiItem {
    nama: string
    total: number
}

const props = defineProps<{
    stats: {
        total_balita: number
        total_bumil: number
        total_lansia: number
        pemeriksaan_bulan_ini: number
    }
    upcomingJadwal: UpcomingJadwal[]
    recentAktivitas: RecentAktivitas[]
    chartData: ChartDataItem[]
    distribusiData?: DistribusiItem[]
    alerts?: {
        kek_count: number
        stunting_count: number
        hipertensi_count: number
    }
}>()

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        y: { beginAtZero: true, grid: { color: 'hsl(var(--border))' } },
        x: { grid: { display: false } },
    },
}

const processedChartData = {
    labels: props.chartData.map(d => d.month),
    datasets: [{
        label: 'Total Pemeriksaan',
        backgroundColor: 'hsl(var(--primary) / 0.1)',
        borderColor: 'hsl(var(--primary))',
        data: props.chartData.map(d => d.total),
        fill: true,
        tension: 0.4,
    }],
}

const statCards = [
    { title: 'Total Balita', value: props.stats.total_balita, icon: Baby, desc: 'Aktif terpantau', variant: 'default' as const },
    { title: 'Ibu Hamil', value: props.stats.total_bumil, icon: User, desc: 'Pemeriksaan rutin', variant: 'default' as const },
    { title: 'Lansia', value: props.stats.total_lansia, icon: Users, desc: 'Kesehatan berkala', variant: 'default' as const },
    { title: 'Periksa Bulan Ini', value: props.stats.pemeriksaan_bulan_ini, icon: Activity, desc: 'Total kunjungan', variant: 'primary' as const },
]
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

        <div class="space-y-6">
            <!-- Alerts -->
            <div v-if="alerts && (alerts.kek_count > 0 || alerts.stunting_count > 0 || alerts.hipertensi_count > 0)" class="grid gap-4 sm:grid-cols-3">
                <Card v-if="alerts.kek_count > 0" class="border-destructive/50 bg-destructive/5">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-destructive">KEK (Kurang Energi Kronis)</CardTitle>
                        <Activity class="h-4 w-4 text-destructive" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-destructive">{{ alerts.kek_count }}</div>
                        <p class="text-xs text-destructive/70 mt-1">Ibu hamil dengan LILA &lt; 23.5 cm</p>
                    </CardContent>
                </Card>
                <Card v-if="alerts.stunting_count > 0" class="border-destructive/50 bg-destructive/5">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-destructive">Risiko Stunting</CardTitle>
                        <Baby class="h-4 w-4 text-destructive" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-destructive">{{ alerts.stunting_count }}</div>
                        <p class="text-xs text-destructive/70 mt-1">Balita dengan BB/TB rendah</p>
                    </CardContent>
                </Card>
                <Card v-if="alerts.hipertensi_count > 0" class="border-destructive/50 bg-destructive/5">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-destructive">Hipertensi</CardTitle>
                        <Activity class="h-4 w-4 text-destructive" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-destructive">{{ alerts.hipertensi_count }}</div>
                        <p class="text-xs text-destructive/70 mt-1">Lansia dengan tensi tinggi</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Stats -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Card v-for="stat in statCards" :key="stat.title" :class="stat.variant === 'primary' ? 'bg-primary text-primary-foreground' : ''">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium" :class="stat.variant === 'primary' ? 'text-primary-foreground/80' : 'text-muted-foreground'">
                            {{ stat.title }}
                        </CardTitle>
                        <component :is="stat.icon" class="h-4 w-4" :class="stat.variant === 'primary' ? 'text-primary-foreground/70' : 'text-muted-foreground'" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stat.value }}</div>
                        <p class="text-xs mt-1" :class="stat.variant === 'primary' ? 'text-primary-foreground/70' : 'text-muted-foreground'">
                            {{ stat.desc }}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Chart + Jadwal -->
            <div class="grid gap-4 lg:grid-cols-7">
                <Card class="lg:col-span-4">
                    <CardHeader>
                        <CardTitle>Trend Pemeriksaan</CardTitle>
                        <CardDescription>Jumlah kunjungan 6 bulan terakhir</CardDescription>
                    </CardHeader>
                    <CardContent class="h-[300px]">
                        <Line :data="processedChartData" :options="chartOptions" />
                    </CardContent>
                </Card>

                <Card class="lg:col-span-3">
                    <CardHeader>
                        <CardTitle>Distribusi Kader</CardTitle>
                        <CardDescription>Per Posyandu</CardDescription>
                    </CardHeader>
                    <CardContent class="h-[300px]">
                        <Bar v-if="distribusiData?.length" :data="processedBarChartData" :options="barChartOptions" />
                        <div v-else class="flex flex-col items-center justify-center h-full text-muted-foreground">
                            <Users class="h-10 w-10 mb-2 opacity-20" />
                            <p class="text-sm">Data tidak tersedia</p>
                        </div>
                    </CardContent>
                </Card>

                <Card class="lg:col-span-7">
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle>Jadwal Terdekat</CardTitle>
                            <CardDescription>Agenda posyandu</CardDescription>
                        </div>
                        <Link :href="route('jadwal-posyandu.index')">
                            <Button variant="ghost" size="sm">Lihat Semua</Button>
                        </Link>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <div
                            v-for="j in upcomingJadwal"
                            :key="j.id"
                            class="flex items-center gap-3 rounded-lg border p-3 transition-colors hover:bg-muted/50"
                        >
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                <Calendar class="h-4 w-4" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium leading-none truncate">{{ j.posyandu_nama }}</p>
                                <p class="text-xs text-muted-foreground mt-1 flex items-center gap-1">
                                    <Clock class="h-3 w-3" />
                                    {{ j.tanggal }} • {{ j.waktu }}
                                </p>
                            </div>
                            <Link :href="route('jadwal-posyandu.index')">
                                <Button variant="ghost" size="icon" class="h-8 w-8 shrink-0">
                                    <ArrowRight class="h-4 w-4" />
                                </Button>
                            </Link>
                        </div>
                        <div v-if="!upcomingJadwal?.length" class="flex flex-col items-center justify-center py-8 text-muted-foreground">
                            <Calendar class="h-10 w-10 mb-2 opacity-20" />
                            <p class="text-sm">Tidak ada jadwal terdekat</p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Recent Activity -->
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <div>
                        <CardTitle>Aktivitas Terbaru</CardTitle>
                        <CardDescription>Pemeriksaan yang baru diinput</CardDescription>
                    </div>
                    <Link :href="route('pemeriksaan.index')">
                        <Button variant="outline" size="sm">Riwayat Lengkap</Button>
                    </Link>
                </CardHeader>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Peserta</TableHead>
                            <TableHead>Kategori</TableHead>
                            <TableHead>Tanggal</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Aksi</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in recentAktivitas" :key="item.id">
                            <TableCell class="font-medium">{{ item.nama }}</TableCell>
                            <TableCell>
                                <Badge variant="secondary">{{ item.type_label }}</Badge>
                            </TableCell>
                            <TableCell class="text-muted-foreground">{{ item.tanggal }}</TableCell>
                            <TableCell>
                                <Badge :variant="item.status === 'Hadir' ? 'default' : 'secondary'">
                                    {{ item.status }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-right">
                                <Link :href="route('pemeriksaan.show', item.id)">
                                    <Button variant="ghost" size="sm">Detail</Button>
                                </Link>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!recentAktivitas?.length">
                            <TableCell colspan="5" class="h-24 text-center text-muted-foreground">
                                Belum ada aktivitas terbaru
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
