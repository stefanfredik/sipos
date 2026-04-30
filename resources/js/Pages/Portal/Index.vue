<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { Calendar, Activity, User, Baby, Users, FileText, TrendingUp, Clock, MapPin } from 'lucide-vue-next'
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler,
} from 'chart.js'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
)

interface Peserta {
    id: string
    nik: string
    nama: string
    foto: string | null
    tgl_lahir: string
    umur_label: string
    jenis_kelamin: string
    no_telp: string
    alamat: string
    keterangan: string | null
    is_active: boolean
}

interface RiwayatPemeriksaan {
    id: string
    tanggal: string
    berat_badan: number | null
    tinggi_badan: number | null
    lila: number | null
    tensi_darah: string | null
    usia_kehamilan: number | null
    lingkar_kepala: number | null
    posyandu_nama: string
    keterangan: string | null
}

interface UpcomingJadwal {
    id: string
    posyandu_nama: string
    tanggal: string
    waktu: string
    lokasi: string
}

interface GrafikData {
    labels: string[]
    beratBadan: (number | null)[]
    tinggiBadan?: (number | null)[]
    lila?: (number | null)[]
    tensiSistole?: (string | null)[]
}

const props = defineProps<{
    peserta: Peserta | null
    pesertaType: string | null
    riwayatPemeriksaan: RiwayatPemeriksaan[]
    grafikData: GrafikData
    upcomingJadwal: UpcomingJadwal[]
}>()

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: true, position: 'top' as const },
    },
    scales: {
        y: { beginAtZero: false, grid: { color: 'hsl(var(--border))' } },
        x: { grid: { display: false } },
    },
}

const beratBadanChartData = {
    labels: props.grafikData.labels,
    datasets: [
        {
            label: 'Berat Badan (kg)',
            backgroundColor: 'hsl(var(--primary) / 0.1)',
            borderColor: 'hsl(var(--primary))',
            data: props.grafikData.beratBadan,
            fill: true,
            tension: 0.4,
        },
    ],
}

const getPesertaIcon = () => {
    if (props.pesertaType === 'ibu_hamil') return User
    if (props.pesertaType === 'balita') return Baby
    return Users
}

const getPesertaTitle = () => {
    if (props.pesertaType === 'ibu_hamil') return 'Ibu Hamil'
    if (props.pesertaType === 'balita') return 'Balita'
    return 'Lansia'
}

const getPesertaAccent = () => {
    if (props.pesertaType === 'ibu_hamil') return { bar: 'bg-gradient-to-r from-pink-400 to-rose-500', icon: 'bg-pink-50 text-pink-600' }
    if (props.pesertaType === 'balita') return { bar: 'bg-gradient-to-r from-blue-400 to-cyan-500', icon: 'bg-blue-50 text-blue-600' }
    return { bar: 'bg-gradient-to-r from-orange-400 to-amber-500', icon: 'bg-orange-50 text-orange-500' }
}
</script>

<template>
    <Head title="Portal Peserta" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Portal {{ getPesertaTitle() }}</h2>
                    <p class="text-sm text-muted-foreground mt-0.5">Kartu Menuju Sehat (KMS) Digital</p>
                </div>
                <Link :href="route('portal.kms')">
                    <Button size="sm" class="flex items-center gap-2 rounded-lg bg-violet-600 hover:bg-violet-700 text-white">
                        <FileText class="h-4 w-4" />
                        Lihat KMS
                    </Button>
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-5">

                <!-- Hero: Profil Peserta -->
                <Card v-if="peserta" class="border-none shadow-sm bg-white overflow-hidden">
                    <div :class="['h-1.5 w-full', getPesertaAccent().bar]" />
                    <CardContent class="p-5">
                        <div class="flex items-center gap-4">
                            <div :class="['h-14 w-14 rounded-2xl flex items-center justify-center overflow-hidden shrink-0', getPesertaAccent().icon.split(' ')[0]]">
                                <img v-if="peserta.foto" :src="peserta.foto" :alt="peserta.nama" class="h-full w-full object-cover" />
                                <component :is="getPesertaIcon()" v-else :class="['h-7 w-7', getPesertaAccent().icon.split(' ')[1]]" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ peserta.nama }}</h3>
                                    <Badge :class="peserta.is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 text-xs font-semibold' : 'bg-gray-100 text-gray-500 border-gray-200 text-xs font-semibold'">
                                        {{ peserta.is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </Badge>
                                </div>
                                <p class="text-sm text-muted-foreground mt-0.5">NIK: {{ peserta.nik }}</p>
                                <div class="flex flex-wrap gap-3 mt-1 text-xs text-muted-foreground">
                                    <span class="flex items-center gap-1"><User class="h-3 w-3" />{{ peserta.umur_label }}</span>
                                    <span class="flex items-center gap-1"><Activity class="h-3 w-3" />{{ peserta.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    <span class="flex items-center gap-1"><Calendar class="h-3 w-3" />{{ new Date(peserta.tgl_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Jadwal Terdekat -->
                <Card v-if="upcomingJadwal?.length" class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><Calendar class="h-4 w-4" /></div>
                            Jadwal Posyandu Terdekat
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5 space-y-2">
                        <div v-for="j in upcomingJadwal" :key="j.id"
                            class="flex items-center gap-3 rounded-lg border border-gray-100 px-4 py-3 hover:bg-gray-50/50 transition-colors">
                            <div class="h-9 w-9 rounded-full bg-violet-50 flex items-center justify-center shrink-0">
                                <Calendar class="h-4 w-4 text-violet-500" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800">{{ j.posyandu_nama }}</p>
                                <p class="text-xs text-muted-foreground mt-0.5">{{ j.tanggal }} • {{ j.waktu }}<span v-if="j.lokasi"> • {{ j.lokasi }}</span></p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Grafik Pertumbuhan -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><TrendingUp class="h-4 w-4" /></div>
                            Grafik Pertumbuhan
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5 h-[260px]">
                        <Line v-if="grafikData.labels?.length" :data="beratBadanChartData" :options="chartOptions" />
                        <div v-else class="flex flex-col items-center justify-center h-full gap-3">
                            <div class="p-3 rounded-full bg-gray-100"><TrendingUp class="h-6 w-6 text-gray-300" /></div>
                            <p class="text-sm font-medium text-gray-400">Belum ada data pemeriksaan</p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Riwayat Pemeriksaan -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <CardHeader class="pb-3 border-b border-gray-100 flex flex-row items-center justify-between">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><Activity class="h-4 w-4" /></div>
                            Riwayat Pemeriksaan
                        </CardTitle>
                        <span class="text-xs text-muted-foreground">10 pemeriksaan terakhir</span>
                    </CardHeader>
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-50/70 hover:bg-gray-50/70">
                                <TableHead class="pl-5 text-xs font-bold uppercase tracking-wider text-gray-500">Tanggal</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Posyandu</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">BB (kg)</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">TB (cm)</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">LILA (cm)</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">Tensi</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Keterangan</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="p in riwayatPemeriksaan" :key="p.id" class="hover:bg-gray-50/50">
                                <TableCell class="pl-5 font-semibold text-sm text-gray-700">{{ p.tanggal }}</TableCell>
                                <TableCell class="text-sm text-muted-foreground">{{ p.posyandu_nama }}</TableCell>
                                <TableCell class="text-center text-sm font-medium">{{ p.berat_badan ?? '-' }}</TableCell>
                                <TableCell class="text-center text-sm text-muted-foreground">{{ p.tinggi_badan ?? '-' }}</TableCell>
                                <TableCell class="text-center text-sm text-muted-foreground">{{ p.lila ?? '-' }}</TableCell>
                                <TableCell class="text-center text-sm text-muted-foreground">{{ p.tensi_darah ?? '-' }}</TableCell>
                                <TableCell class="max-w-[200px] truncate text-xs text-muted-foreground">{{ p.keterangan || '-' }}</TableCell>
                            </TableRow>
                            <TableRow v-if="!riwayatPemeriksaan?.length">
                                <TableCell colspan="7" class="py-14">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-3 rounded-full bg-gray-100"><Activity class="h-6 w-6 text-gray-300" /></div>
                                        <p class="text-sm font-medium text-gray-400">Belum ada riwayat pemeriksaan</p>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
