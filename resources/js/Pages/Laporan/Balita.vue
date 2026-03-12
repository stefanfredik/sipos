<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select'
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table'
import { Badge } from '@/components/ui/badge'
import { reactive } from 'vue'
import { Download, FileText } from 'lucide-vue-next'

interface BalitaData {
    id: string
    nik: string
    nama: string
    nama_orang_tua: string
    jenis_kelamin: string
    is_active: boolean
}

const props = defineProps<{
    data: BalitaData[]
    posyandu: Array<{ id: string; nama_posyandu: string }>
    filters: { posyandu_id?: string }
}>()

const filterState = reactive({
    posyandu_id: props.filters.posyandu_id || 'all',
})

function applyFilters() {
    router.get(route('laporan.balita'), {
        posyandu_id: filterState.posyandu_id === 'all' ? null : filterState.posyandu_id,
    }, { preserveState: true })
}

function exportPdf() {
    const form = document.createElement('form')
    form.method = 'POST'
    form.action = route('laporan.export.pdf')
    const csrf = document.createElement('input')
    csrf.type = 'hidden'; csrf.name = '_token'
    csrf.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    form.appendChild(csrf)
    const type = document.createElement('input')
    type.type = 'hidden'; type.name = 'type'; type.value = 'balita'
    form.appendChild(type)
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
    const type = document.createElement('input')
    type.type = 'hidden'; type.name = 'type'; type.value = 'balita'
    form.appendChild(type)
    document.body.appendChild(form); form.submit(); document.body.removeChild(form)
}
</script>

<template>
    <Head title="Laporan Balita" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Laporan Balita</h2>
                <div class="flex gap-2">
                    <Button variant="outline" size="sm" @click="exportPdf">
                        <FileText class="mr-2 h-4 w-4" /> Export PDF
                    </Button>
                    <Button size="sm" @click="exportExcel">
                        <Download class="mr-2 h-4 w-4" /> Export Excel
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <Card>
                <CardHeader class="pb-3"><CardTitle class="text-sm font-medium">Filter</CardTitle></CardHeader>
                <CardContent>
                    <div class="flex items-end gap-4">
                        <div class="space-y-1.5 w-64">
                            <label class="text-xs font-medium text-muted-foreground">Posyandu</label>
                            <Select v-model="filterState.posyandu_id">
                                <SelectTrigger><SelectValue placeholder="Semua Posyandu" /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">Semua Posyandu</SelectItem>
                                    <SelectItem v-for="p in posyandu" :key="p.id" :value="p.id">{{ p.nama_posyandu }}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <Button @click="applyFilters">Tampilkan</Button>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-12 text-center">No</TableHead>
                            <TableHead>NIK</TableHead>
                            <TableHead>Nama</TableHead>
                            <TableHead>Orang Tua</TableHead>
                            <TableHead class="text-center">JK</TableHead>
                            <TableHead class="text-center">Status</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(item, i) in data" :key="item.id">
                            <TableCell class="text-center text-muted-foreground">{{ i + 1 }}</TableCell>
                            <TableCell class="font-mono text-xs">{{ item.nik }}</TableCell>
                            <TableCell class="font-medium">{{ item.nama }}</TableCell>
                            <TableCell class="text-muted-foreground">{{ item.nama_orang_tua }}</TableCell>
                            <TableCell class="text-center">
                                <Badge variant="outline">{{ item.jenis_kelamin === 'L' ? 'L' : 'P' }}</Badge>
                            </TableCell>
                            <TableCell class="text-center">
                                <Badge :variant="item.is_active ? 'default' : 'secondary'">
                                    {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                                </Badge>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="data.length === 0">
                            <TableCell colspan="6" class="h-24 text-center text-muted-foreground">Tidak ada data balita</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <p class="text-sm text-muted-foreground">Total: {{ data.length }} data</p>
        </div>
    </AuthenticatedLayout>
</template>
