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
import { Calendar, Activity, User, Baby, Users, FileText, TrendingUp } from 'lucide-vue-next'
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
</script>

<template>
    <Head title="Portal Peserta" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold">Portal {{ getPesertaTitle() }}</h2>
                    <p class="text-sm text-muted-foreground">Kartu Menuju Sehat (KMS) Digital</p>
                </div>
                <Link :href="route('portal.kms')">
                    <Button size="sm">
                        <FileText class="mr-2 h-4 w-4" />
                        Lihat KMS
                    </Button>
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Profil Peserta -->
            <Card v-if="peserta">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <component :is="getPesertaIcon()" class="h-5 w-5" />
                        Profil {{ getPesertaTitle() }}
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="flex items-start gap-4">
                        <div class="h-20 w-20 rounded-full bg-muted flex items-center justify-center overflow-hidden">
                            <img v-if="peserta.foto" :src="peserta.foto" :alt="peserta.nama" class="h-full w-full object-cover" />
                            <component :is="getPesertaIcon()" v-else class="h-8 w-8 text-muted-foreground" />
                        </div>
                        <div class="grid gap-1 flex-1">
                            <p class="text-lg font-semibold">{{ peserta.nama }}</p>
                            <p class="text-sm text-muted-foreground">NIK: {{ peserta.nik }}</p>
                            <div class="flex gap-4 text-sm">
                                <span class="text-muted-foreground">
                                    <User class="h-3 w-3 inline mr-1" />
                                    {{ peserta.umur_label }}
                                </span>
                                <span class="text-muted-foreground">
                                    <Activity class="h-3 w-3 inline mr-1" />
                                    {{ peserta.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                                <span class="text-muted-foreground">
                                    <Calendar class="h-3 w-3 inline mr-1" />
                                    {{ new Date(peserta.tgl_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                                </span>
                            </div>
                        </div>
                        <Badge :variant="peserta.is_active ? 'default' : 'secondary'">
                            {{ peserta.is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </Badge>
                    </div>
                </CardContent>
            </Card>

            <!-- Jadwal Terdekat -->
            <Card v-if="upcomingJadwal?.length">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Calendar class="h-5 w-5" />
                        Jadwal Posyandu Terdekat
                    </CardTitle>
                    <CardDescription>Agenda pemeriksaan berikutnya</CardDescription>
                </CardHeader>
                <CardContent class="space-y-3">
                    <div
                        v-for="j in upcomingJadwal"
                        :key="j.id"
                        class="flex items-center gap-3 rounded-lg border p-3 transition-colors hover:bg-muted/50"
                    >
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <Calendar class="h-5 w-5" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium leading-none">{{ j.posyandu_nama }}</p>
                            <p class="text-xs text-muted-foreground mt-1">
                                {{ j.tanggal }} • {{ j.waktu }} • {{ j.lokasi }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Grafik Pertumbuhan -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <TrendingUp class="h-5 w-5" />
                        Grafik Pertumbuhan
                    </CardTitle>
                    <CardDescription>Monitor perkembangan kesehatan</CardDescription>
                </CardHeader>
                <CardContent class="h-[300px]">
                    <Line v-if="grafikData.labels?.length" :data="beratBadanChartData" :options="chartOptions" />
                    <div v-else class="flex flex-col items-center justify-center h-full text-muted-foreground">
                        <TrendingUp class="h-10 w-10 mb-2 opacity-20" />
                        <p class="text-sm">Belum ada data pemeriksaan</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Riwayat Pemeriksaan -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Activity class="h-5 w-5" />
                        Riwayat Pemeriksaan
                    </CardTitle>
                    <CardDescription>10 pemeriksaan terakhir</CardDescription>
                </CardHeader>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Tanggal</TableHead>
                            <TableHead>Posyandu</TableHead>
                            <TableHead>BB (kg)</TableHead>
                            <TableHead>TB (cm)</TableHead>
                            <TableHead>LILA (cm)</TableHead>
                            <TableHead>Tensi</TableHead>
                            <TableHead>Keterangan</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="p in riwayatPemeriksaan" :key="p.id">
                            <TableCell class="font-medium">{{ p.tanggal }}</TableCell>
                            <TableCell>{{ p.posyandu_nama }}</TableCell>
                            <TableCell>{{ p.berat_badan ?? '-' }}</TableCell>
                            <TableCell>{{ p.tinggi_badan ?? '-' }}</TableCell>
                            <TableCell>{{ p.lila ?? '-' }}</TableCell>
                            <TableCell>{{ p.tensi_darah ?? '-' }}</TableCell>
                            <TableCell class="max-w-[200px] truncate text-muted-foreground">
                                {{ p.keterangan || '-' }}
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!riwayatPemeriksaan?.length">
                            <TableCell colspan="7" class="h-24 text-center text-muted-foreground">
                                Belum ada riwayat pemeriksaan
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
