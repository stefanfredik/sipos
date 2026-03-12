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
import { Calendar, Activity, User, Baby, Users, FileText, Download, ArrowLeft } from 'lucide-vue-next'
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

const props = defineProps<{
    peserta: Peserta | null
    pesertaType: string | null
    riwayatPemeriksaan: RiwayatPemeriksaan[]
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

const prepareChartData = (field: keyof RiwayatPemeriksaan) => {
    return {
        labels: props.riwayatPemeriksaan.map(p => p.tanggal),
        datasets: [
            {
                label: field === 'berat_badan' ? 'Berat Badan (kg)' :
                       field === 'tinggi_badan' ? 'Tinggi Badan (cm)' :
                       field === 'lila' ? 'LILA (cm)' : 'Tensi',
                backgroundColor: 'hsl(var(--primary) / 0.1)',
                borderColor: 'hsl(var(--primary))',
                data: props.riwayatPemeriksaan.map(p => p[field]),
                fill: true,
                tension: 0.4,
            },
        ],
    }
}

const getPesertaIcon = () => {
    if (props.pesertaType === 'ibu_hamil') return User
    if (props.pesertaType === 'balita') return Baby
    return Users
}

const exportKms = () => {
    // TODO: Implement PDF export
    alert('Fitur export KMS akan segera tersedia')
}
</script>

<template>
    <Head title="KMS Digital" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('portal.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <div class="flex-1">
                    <h2 class="text-lg font-semibold">Kartu Menuju Sehat (KMS)</h2>
                    <p class="text-sm text-muted-foreground">
                        {{ pesertaType === 'ibu_hamil' ? 'Ibu Hamil' : pesertaType === 'balita' ? 'Balita' : 'Lansia' }}
                    </p>
                </div>
                <Button size="sm" @click="exportKms">
                    <Download class="mr-2 h-4 w-4" />
                    Export PDF
                </Button>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Header KMS -->
            <Card v-if="peserta" class="border-primary/20">
                <CardHeader>
                    <div class="flex items-center gap-4">
                        <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center overflow-hidden">
                            <img v-if="peserta.foto" :src="peserta.foto" :alt="peserta.nama" class="h-full w-full object-cover" />
                            <component :is="getPesertaIcon()" v-else class="h-8 w-8 text-primary" />
                        </div>
                        <div>
                            <CardTitle class="text-xl">{{ peserta.nama }}</CardTitle>
                            <CardDescription class="flex gap-3 mt-1">
                                <span>NIK: {{ peserta.nik }}</span>
                                <span>•</span>
                                <span>{{ peserta.umur_label }}</span>
                                <span>•</span>
                                <span>{{ peserta.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
            </Card>

            <!-- Grafik -->
            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-base">Grafik Berat Badan</CardTitle>
                    </CardHeader>
                    <CardContent class="h-[250px]">
                        <Line
                            v-if="riwayatPemeriksaan?.length"
                            :data="prepareChartData('berat_badan')"
                            :options="chartOptions"
                        />
                        <div v-else class="flex items-center justify-center h-full text-muted-foreground text-sm">
                            Belum ada data
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="pesertaType !== 'lansia'">
                    <CardHeader>
                        <CardTitle class="text-base">Grafik Tinggi Badan</CardTitle>
                    </CardHeader>
                    <CardContent class="h-[250px]">
                        <Line
                            v-if="riwayatPemeriksaan?.length && riwayatPemeriksaan[0].tinggi_badan"
                            :data="prepareChartData('tinggi_badan')"
                            :options="chartOptions"
                        />
                        <div v-else class="flex items-center justify-center h-full text-muted-foreground text-sm">
                            Belum ada data
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="pesertaType === 'ibu_hamil'">
                    <CardHeader>
                        <CardTitle class="text-base">Grafik LILA</CardTitle>
                    </CardHeader>
                    <CardContent class="h-[250px]">
                        <Line
                            v-if="riwayatPemeriksaan?.length && riwayatPemeriksaan[0].lila"
                            :data="prepareChartData('lila')"
                            :options="chartOptions"
                        />
                        <div v-else class="flex items-center justify-center h-full text-muted-foreground text-sm">
                            Belum ada data
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Tabel Riwayat -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <FileText class="h-5 w-5" />
                        Riwayat Pemeriksaan Lengkap
                    </CardTitle>
                    <CardDescription>Seluruh riwayat pemeriksaan dari awal hingga sekarang</CardDescription>
                </CardHeader>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[120px]">Tanggal</TableHead>
                            <TableHead>Posyandu</TableHead>
                            <TableHead class="text-right">BB (kg)</TableHead>
                            <TableHead class="text-right">TB (cm)</TableHead>
                            <TableHead class="text-right">LILA (cm)</TableHead>
                            <TableHead class="text-right">Tensi</TableHead>
                            <TableHead v-if="pesertaType === 'ibu_hamil'" class="text-right">Usia Kehamilan</TableHead>
                            <TableHead v-if="pesertaType === 'balita'" class="text-right">Lingkar Kepala</TableHead>
                            <TableHead>Keterangan</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="p in riwayatPemeriksaan" :key="p.id">
                            <TableCell class="font-medium">{{ p.tanggal }}</TableCell>
                            <TableCell>{{ p.posyandu_nama }}</TableCell>
                            <TableCell class="text-right">{{ p.berat_badan ?? '-' }}</TableCell>
                            <TableCell class="text-right">{{ p.tinggi_badan ?? '-' }}</TableCell>
                            <TableCell class="text-right">{{ p.lila ?? '-' }}</TableCell>
                            <TableCell class="text-right">{{ p.tensi_darah ?? '-' }}</TableCell>
                            <TableCell v-if="pesertaType === 'ibu_hamil'" class="text-right">
                                {{ p.usia_kehamilan ? `${p.usia_kehamilan} minggu` : '-' }}
                            </TableCell>
                            <TableCell v-if="pesertaType === 'balita'" class="text-right">
                                {{ p.lingkar_kepala ?? '-' }}
                            </TableCell>
                            <TableCell class="max-w-[200px] truncate text-muted-foreground">
                                {{ p.keterangan || '-' }}
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!riwayatPemeriksaan?.length">
                            <TableCell
                                :colspan="pesertaType === 'ibu_hamil' ? 8 : pesertaType === 'balita' ? 8 : 7"
                                class="h-24 text-center text-muted-foreground"
                            >
                                Belum ada riwayat pemeriksaan
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <!-- Info Footer -->
            <Card class="bg-muted/50">
                <CardContent class="py-4">
                    <div class="flex items-start gap-3">
                        <Activity class="h-5 w-5 text-muted-foreground mt-0.5" />
                        <div class="text-sm text-muted-foreground">
                            <p class="font-medium mb-1">Informasi Penting:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li v-if="pesertaType === 'ibu_hamil'">
                                    LILA normal untuk ibu hamil adalah ≥ 23.5 cm. LILA &lt; 23.5 cm menunjukkan risiko KEK (Kurang Energi Kronis).
                                </li>
                                <li v-if="pesertaType === 'balita'">
                                    Pantau grafik pertumbuhan secara rutin. Jika grafik mendatar atau menurun, konsultasikan dengan bidan.
                                </li>
                                <li v-if="pesertaType === 'lansia'">
                                    Tekanan darah normal adalah &lt; 140/90 mmHg. Jika lebih tinggi, konsultasikan dengan tenaga kesehatan.
                                </li>
                            </ul>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
