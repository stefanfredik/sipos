<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Badge } from '@/components/ui/badge'
import { reactive, computed } from 'vue'
import { Printer, Download, Activity, User, Baby, Users, FileText, Filter } from 'lucide-vue-next'

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
    stats: { total_bumil: number; total_balita: number; total_lansia: number; total_hadir: number }
    posyandu: Array<{ id: string; nama_posyandu: string }>
    filters: { bulan: string; tahun: string; posyandu_id: string | null }
}>()

const bulanOptions = [
    { value: '1', label: 'Januari' }, { value: '2', label: 'Februari' },
    { value: '3', label: 'Maret' }, { value: '4', label: 'April' },
    { value: '5', label: 'Mei' }, { value: '6', label: 'Juni' },
    { value: '7', label: 'Juli' }, { value: '8', label: 'Agustus' },
    { value: '9', label: 'September' }, { value: '10', label: 'Oktober' },
    { value: '11', label: 'November' }, { value: '12', label: 'Desember' },
]

const currentYear = new Date().getFullYear()
const tahunOptions = Array.from({ length: 5 }, (_, i) => ({ value: String(currentYear - i), label: String(currentYear - i) }))

const filterState = reactive({
    bulan: props.filters.bulan,
    tahun: props.filters.tahun,
    posyandu_id: props.filters.posyandu_id || 'all',
})

const selectedBulanLabel = computed(() => bulanOptions.find(b => b.value === filterState.bulan)?.label ?? '-')

function applyFilters() {
    router.get(route('laporan.index'), {
        bulan: filterState.bulan,
        tahun: filterState.tahun,
        posyandu_id: filterState.posyandu_id === 'all' ? null : filterState.posyandu_id,
    }, { preserveState: true })
}

function doExport(action: string, extra: Record<string, string> = {}) {
    const form = document.createElement('form')
    form.method = 'POST'
    form.action = route(action)
    const csrf = document.createElement('input')
    csrf.type = 'hidden'; csrf.name = '_token'
    csrf.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    form.appendChild(csrf)
    const fields: Record<string, string> = { type: 'bulanan', bulan: filterState.bulan, tahun: filterState.tahun, posyandu_id: filterState.posyandu_id === 'all' ? '' : filterState.posyandu_id, ...extra }
    Object.entries(fields).forEach(([name, value]) => {
        const input = document.createElement('input')
        input.type = 'hidden'; input.name = name; input.value = value
        form.appendChild(input)
    })
    document.body.appendChild(form); form.submit(); document.body.removeChild(form)
}

function printPage() {
    if (typeof window !== 'undefined') {
        (window as any).print();
    }
}

function getPesertaTypeLabel(type: string) {
    return { ibu_hamil: 'Ibu Hamil', balita: 'Balita', lansia: 'Lansia' }[type] ?? type
}
function typeBadgeClass(type: string) {
    return { ibu_hamil: 'bg-pink-50 text-pink-700 border-pink-200', balita: 'bg-blue-50 text-blue-700 border-blue-200', lansia: 'bg-orange-50 text-orange-700 border-orange-200' }[type] ?? 'bg-gray-100 text-gray-600'
}

const statCards = [
    { label: 'Ibu Hamil', key: 'total_bumil' as const, icon: User, color: 'bg-gradient-to-br from-pink-500 to-rose-600 text-white' },
    { label: 'Balita', key: 'total_balita' as const, icon: Baby, color: 'bg-gradient-to-br from-blue-500 to-cyan-600 text-white' },
    { label: 'Lansia', key: 'total_lansia' as const, icon: Users, color: 'bg-gradient-to-br from-orange-400 to-amber-500 text-white' },
    { label: 'Kehadiran', key: 'total_hadir' as const, icon: Activity, color: 'bg-gradient-to-br from-emerald-500 to-teal-600 text-white' },
]
</script>

<template>
    <Head title="Laporan Bulanan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Rekapitulasi Laporan</h2>
                    <p class="text-sm text-muted-foreground mt-0.5">{{ selectedBulanLabel }} {{ filterState.tahun }}</p>
                </div>
                <div class="flex gap-2 print:hidden">
                    <Button variant="outline" size="sm" @click="printPage()" class="flex items-center gap-2 rounded-lg">
                        <Printer class="h-4 w-4" /> Cetak
                    </Button>
                    <Button variant="outline" size="sm" @click="doExport('laporan.export.pdf')" class="flex items-center gap-2 rounded-lg">
                        <FileText class="h-4 w-4" /> PDF
                    </Button>
                    <Button size="sm" @click="doExport('laporan.export.excel')" class="flex items-center gap-2 rounded-lg">
                        <Download class="h-4 w-4" /> Excel
                    </Button>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-5">

                <!-- Filter Card -->
                <Card class="border-none shadow-sm bg-white print:hidden">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><Filter class="h-4 w-4" /></div>
                            Parameter Laporan
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Bulan</label>
                                <Select v-model="filterState.bulan">
                                    <SelectTrigger><SelectValue placeholder="Pilih Bulan" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="b in bulanOptions" :key="b.value" :value="b.value">{{ b.label }}</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Tahun</label>
                                <Select v-model="filterState.tahun">
                                    <SelectTrigger><SelectValue placeholder="Pilih Tahun" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="t in tahunOptions" :key="t.value" :value="t.value">{{ t.label }}</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Posyandu</label>
                                <Select v-model="filterState.posyandu_id">
                                    <SelectTrigger><SelectValue placeholder="Semua Posyandu" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">Semua Posyandu</SelectItem>
                                        <SelectItem v-for="p in posyandu" :key="p.id" :value="p.id">{{ p.nama_posyandu }}</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="flex items-end">
                                <Button class="w-full bg-violet-600 hover:bg-violet-700 text-white rounded-lg" @click="applyFilters">Tampilkan</Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Stats -->
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 print:hidden">
                    <Card v-for="s in statCards" :key="s.label" class="border-none shadow-sm bg-white overflow-hidden">
                        <CardContent class="p-4 flex items-center gap-3">
                            <div :class="['p-2.5 rounded-xl shrink-0', s.color]">
                                <component :is="s.icon" class="h-5 w-5" />
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">{{ s.label }}</p>
                                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ stats[s.key] }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Table -->
                <Card class="border-none shadow-sm bg-white overflow-hidden print:shadow-none print:border-none">
                    <!-- Print header -->
                    <div class="hidden print:block text-center border-b-2 border-black pb-4 mb-6 pt-4 px-6">
                        <h2 class="text-2xl font-bold uppercase tracking-wide">Sistem Informasi Posyandu (SIPOS)</h2>
                        <p class="text-sm text-gray-700 mt-1 mb-3">Posyandu Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali</p>
                        <h3 class="text-lg font-semibold">Laporan Rekapitulasi Pemeriksaan Kesehatan</h3>
                        <p class="text-sm text-gray-600 mt-1 mb-4">Periode: {{ selectedBulanLabel }} {{ filterState.tahun }}</p>
                        <div class="flex justify-center gap-8 text-sm font-medium">
                            <span>Ibu Hamil: {{ stats.total_bumil }}</span>
                            <span>Balita: {{ stats.total_balita }}</span>
                            <span>Lansia: {{ stats.total_lansia }}</span>
                            <span>Total Kehadiran: {{ stats.total_hadir }}</span>
                        </div>
                    </div>

                    <Table class="print:text-sm">
                        <TableHeader>
                            <TableRow class="bg-gray-50/70 hover:bg-gray-50/70">
                                <TableHead class="pl-5 text-xs font-bold uppercase tracking-wider text-gray-500">Tgl. Periksa</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Nama / NIK</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">Kategori</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Lokasi</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">BB / TB</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">Status</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="item in laporan" :key="item.id" class="hover:bg-gray-50/50">
                                <TableCell class="pl-5 text-sm text-muted-foreground">
                                    {{ new Date(item.tgl_pemeriksaan).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                                </TableCell>
                                <TableCell>
                                    <p class="font-semibold text-sm text-gray-800">{{ item.peserta?.nama || '-' }}</p>
                                    <p class="text-xs text-muted-foreground font-mono">{{ item.peserta?.nik || '-' }}</p>
                                </TableCell>
                                <TableCell class="text-center">
                                    <Badge class="text-xs font-semibold" :class="typeBadgeClass(item.peserta_type)">
                                        {{ getPesertaTypeLabel(item.peserta_type) }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-sm text-muted-foreground">{{ item.jadwal?.posyandu?.nama_posyandu || 'Posyandu' }}</TableCell>
                                <TableCell class="text-center">
                                    <p class="text-sm font-medium text-gray-700">{{ item.berat_badan || '-' }} kg</p>
                                    <p class="text-xs text-muted-foreground">{{ item.tinggi_badan || '-' }} cm</p>
                                </TableCell>
                                <TableCell class="text-center">
                                    <Badge :class="item.hadir ? 'bg-emerald-50 text-emerald-700 border-emerald-200 text-xs font-semibold' : 'bg-gray-100 text-gray-500 border-gray-200 text-xs font-semibold'">
                                        {{ item.hadir ? 'Hadir' : 'Absen' }}
                                    </Badge>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="laporan.length === 0">
                                <TableCell colspan="6" class="py-16">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-3 rounded-full bg-gray-100"><FileText class="h-6 w-6 text-gray-300" /></div>
                                        <p class="text-sm font-medium text-gray-400">Tidak ada data untuk periode ini</p>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Print Signature -->
                    <div class="hidden print:flex justify-end mt-12 pr-12 pb-6">
                        <div class="text-center">
                            <p class="mb-16">Mengetahui,</p>
                            <p class="font-bold underline">Bidan Desa Belumbang</p>
                            <p class="text-sm">NIP. ......................................</p>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@media print {
    @page { size: landscape; margin: 1.5cm; }
    nav, button, .print\:hidden { display: none !important; }
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: white; }
    :deep(th), :deep(td) { padding: 0.5rem !important; border-bottom: 1px solid #e5e7eb; }
    :deep(table) { width: 100%; border-collapse: collapse; }
}
</style>
