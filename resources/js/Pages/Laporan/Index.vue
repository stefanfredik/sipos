<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select'
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table'
import { Badge } from '@/components/ui/badge'
import { reactive } from 'vue'
import { Printer, Download, Activity, User, Baby, Users, FileText } from 'lucide-vue-next'

interface Laporan {
    id: string
    tgl_pemeriksaan: string
    peserta_type: string
    peserta: { nama: string; nik: string } | null
    jadwal: { posyandu: { nama_posyandu: string } } | null
    hadir: boolean
    berat_badan: string
    tinggi_badan: string
    tensi_darah: string | null
}

const props = defineProps<{
    laporan: Laporan[]
    stats: {
        total_bumil: number
        total_balita: number
        total_lansia: number
        total_hadir: number
    }
    posyandu: Array<{ id: string; nama_posyandu: string }>
    filters: {
        bulan: string
        tahun: string
        posyandu_id: string | null
    }
}>()

const bulanOptions = [
    { value: '1', label: 'Januari' },
    { value: '2', label: 'Februari' },
    { value: '3', label: 'Maret' },
    { value: '4', label: 'April' },
    { value: '5', label: 'Mei' },
    { value: '6', label: 'Juni' },
    { value: '7', label: 'Juli' },
    { value: '8', label: 'Agustus' },
    { value: '9', label: 'September' },
    { value: '10', label: 'Oktober' },
    { value: '11', label: 'November' },
    { value: '12', label: 'Desember' },
]

const currentYear = new Date().getFullYear()
const tahunOptions = Array.from({ length: 5 }, (_, i) => ({
    value: String(currentYear - i),
    label: String(currentYear - i),
}))

const filterState = reactive({
    bulan: props.filters.bulan,
    tahun: props.filters.tahun,
    posyandu_id: props.filters.posyandu_id || 'all',
})

function applyFilters() {
    router.get(route('laporan.index'), {
        bulan: filterState.bulan,
        tahun: filterState.tahun,
        posyandu_id: filterState.posyandu_id === 'all' ? null : filterState.posyandu_id,
    }, { preserveState: true })
}

function printReport() {
    window.print()
}

function exportPdf() {
    const form = document.createElement('form')
    form.method = 'POST'
    form.action = route('laporan.export.pdf')
    const csrf = document.createElement('input')
    csrf.type = 'hidden'; csrf.name = '_token'
    csrf.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    form.appendChild(csrf)
    ;['type', 'bulan', 'tahun', 'posyandu_id'].forEach(name => {
        const input = document.createElement('input')
        input.type = 'hidden'; input.name = name
        input.value = name === 'type' ? 'bulanan' : name === 'bulan' ? filterState.bulan : name === 'tahun' ? filterState.tahun : (filterState.posyandu_id === 'all' ? '' : filterState.posyandu_id)
        form.appendChild(input)
    })
    document.body.appendChild(form); form.submit(); document.body.removeChild(form)
}

function exportExcel() {
    const form = document.createElement('form')
    form.method = 'POST'
    form.action = route('laporan.export.excel')
    const csrf = document.createElement('input')
    csrf.type = 'hidden'; csrf.name = '_token'
    csrf.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    form.appendChild(csrf)
    ;['type', 'bulan', 'tahun', 'posyandu_id'].forEach(name => {
        const input = document.createElement('input')
        input.type = 'hidden'; input.name = name
        input.value = name === 'type' ? 'bulanan' : name === 'bulan' ? filterState.bulan : name === 'tahun' ? filterState.tahun : (filterState.posyandu_id === 'all' ? '' : filterState.posyandu_id)
        form.appendChild(input)
    })
    document.body.appendChild(form); form.submit(); document.body.removeChild(form)
}

function getPesertaTypeLabel(type: string) {
    switch (type) {
        case 'ibu_hamil': return 'Ibu Hamil'
        case 'balita': return 'Balita'
        case 'lansia': return 'Lansia'
        default: return type
    }
}
</script>

<template>
    <Head title="Laporan Bulanan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Rekapitulasi Laporan</h2>
                <div class="flex gap-2 print:hidden">
                    <Button variant="outline" size="sm" @click="printReport">
                        <Printer class="mr-2 h-4 w-4" />
                        Cetak
                    </Button>
                    <Button variant="outline" size="sm" @click="exportPdf">
                        <Download class="mr-2 h-4 w-4" />
                        Export PDF
                    </Button>
                    <Button size="sm" @click="exportExcel">
                        <Download class="mr-2 h-4 w-4" />
                        Export Excel
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <!-- Filter -->
            <Card class="print:hidden">
                <CardHeader class="pb-3">
                    <CardTitle class="text-sm font-medium">Parameter Laporan</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-muted-foreground">Bulan</label>
                            <Select v-model="filterState.bulan">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Bulan" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="b in bulanOptions" :key="b.value" :value="b.value">
                                        {{ b.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-muted-foreground">Tahun</label>
                            <Select v-model="filterState.tahun">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Tahun" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="t in tahunOptions" :key="t.value" :value="t.value">
                                        {{ t.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-muted-foreground">Posyandu</label>
                            <Select v-model="filterState.posyandu_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Semua Posyandu" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">Semua Posyandu</SelectItem>
                                    <SelectItem v-for="p in posyandu" :key="p.id" :value="p.id">
                                        {{ p.nama_posyandu }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="flex items-end">
                            <Button class="w-full" @click="applyFilters">Tampilkan</Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Stats -->
            <div class="grid gap-4 md:grid-cols-4 print:hidden">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2 space-y-0">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Ibu Hamil</CardTitle>
                        <User class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_bumil }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2 space-y-0">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Balita</CardTitle>
                        <Baby class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_balita }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2 space-y-0">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Lansia</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_lansia }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2 space-y-0">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Kehadiran</CardTitle>
                        <Activity class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_hadir }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Report Table -->
            <Card class="print:shadow-none print:border-none">
                <!-- Print Header -->
                <div class="hidden print:block text-center border-b-2 border-black pb-4 mb-6 pt-4">
                    <h2 class="text-2xl font-bold uppercase tracking-wide m-0">Sistem Informasi Posyandu (SIPOS)</h2>
                    <p class="text-sm text-gray-700 mt-1 mb-3">Posyandu Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali</p>
                    <h3 class="text-lg font-semibold m-0">Laporan Rekapitulasi Pemeriksaan Kesehatan</h3>
                    <p class="text-sm text-gray-600 mt-1 mb-4">Periode: {{ bulanOptions.find(b => b.value === filterState.bulan)?.label }} {{ filterState.tahun }}</p>
                    
                    <div class="flex justify-center gap-8 text-sm font-medium">
                        <span>Ibu Hamil: {{ stats.total_bumil }}</span>
                        <span>Balita: {{ stats.total_balita }}</span>
                        <span>Lansia: {{ stats.total_lansia }}</span>
                        <span>Total Kehadiran: {{ stats.total_hadir }}</span>
                    </div>
                </div>
                
                <Table class="print:text-sm">
                    <TableHeader>
                        <TableRow>
                            <TableHead>Tgl. Periksa</TableHead>
                            <TableHead>Nama / NIK</TableHead>
                            <TableHead class="text-center">Kategori</TableHead>
                            <TableHead>Lokasi</TableHead>
                            <TableHead class="text-center">BB / TB</TableHead>
                            <TableHead class="text-center">Status</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in laporan" :key="item.id">
                            <TableCell class="text-muted-foreground">
                                {{ new Date(item.tgl_pemeriksaan).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                            </TableCell>
                            <TableCell>
                                <div>
                                    <p class="font-medium">{{ item.peserta?.nama || '-' }}</p>
                                    <p class="text-xs text-muted-foreground font-mono">{{ item.peserta?.nik || '-' }}</p>
                                </div>
                            </TableCell>
                            <TableCell class="text-center">
                                <Badge variant="outline">{{ getPesertaTypeLabel(item.peserta_type) }}</Badge>
                            </TableCell>
                            <TableCell class="text-muted-foreground">
                                {{ item.jadwal?.posyandu?.nama_posyandu || 'Posyandu' }}
                            </TableCell>
                            <TableCell class="text-center">
                                <div>
                                    <p class="text-sm font-medium">{{ item.berat_badan || '-' }} kg</p>
                                    <p class="text-xs text-muted-foreground">{{ item.tinggi_badan || '-' }} cm</p>
                                </div>
                            </TableCell>
                            <TableCell class="text-center">
                                <Badge :variant="item.hadir ? 'default' : 'secondary'">
                                    {{ item.hadir ? 'Hadir' : 'Absen' }}
                                </Badge>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="laporan.length === 0">
                            <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                                Tidak ada data untuk periode ini
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                
                <!-- Print Signature -->
                <div class="hidden print:flex justify-end mt-12 pr-12">
                    <div class="text-center">
                        <p class="mb-16">Mengetahui,</p>
                        <p class="font-bold underline">Bidan Desa Belumbang</p>
                        <p class="text-sm">NIP. ......................................</p>
                    </div>
                </div>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@media print {
    @page {
        size: landscape;
        margin: 1.5cm;
    }
    nav, button, .print\:hidden {
        display: none !important;
    }
    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        background-color: white;
    }
    .print\:shadow-none {
        box-shadow: none !important;
    }
    .print\:border-none {
        border: none !important;
    }
    :deep(th), :deep(td) {
        padding: 0.5rem !important;
        border-bottom: 1px solid #e5e7eb;
    }
    :deep(table) {
        width: 100%;
        border-collapse: collapse;
    }
}
</style>
