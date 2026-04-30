<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Badge } from '@/components/ui/badge'
import { reactive, ref, computed } from 'vue'
import { Download, FileText, Loader2, Filter, Users } from 'lucide-vue-next'

interface LansiaData {
    id: string; nik: string; nama: string; jenis_kelamin: string; is_active: boolean
}

const props = defineProps<{
    data: LansiaData[]
    posyandu: Array<{ id: string; nama_posyandu: string }>
    filters: { posyandu_id?: string }
}>()

const exporting = ref(false)
const exportType = ref<'pdf' | 'excel' | null>(null)
const filterState = reactive({ posyandu_id: props.filters.posyandu_id || 'all' })

const totalAktif = computed(() => props.data.filter(d => d.is_active).length)
const totalLaki = computed(() => props.data.filter(d => d.jenis_kelamin === 'L').length)

function applyFilters() {
    router.get(route('laporan.lansia'), {
        posyandu_id: filterState.posyandu_id === 'all' ? null : filterState.posyandu_id,
    }, { preserveState: true })
}

function doExport(action: string, type: string) {
    exporting.value = true
    exportType.value = action === 'laporan.export.pdf' ? 'pdf' : 'excel'
    const form = document.createElement('form')
    form.method = 'POST'; form.action = route(action)
    const csrf = document.createElement('input')
    csrf.type = 'hidden'; csrf.name = '_token'
    csrf.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    form.appendChild(csrf)
    const t = document.createElement('input')
    t.type = 'hidden'; t.name = 'type'; t.value = type
    form.appendChild(t)
    document.body.appendChild(form); form.submit(); document.body.removeChild(form)
    setTimeout(() => { exporting.value = false; exportType.value = null }, 3000)
}
</script>

<template>
    <Head title="Laporan Lansia" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Laporan Lansia</h2>
                    <p class="text-sm text-muted-foreground mt-0.5">Data lansia terdaftar posyandu</p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" size="sm" @click="doExport('laporan.export.pdf', 'lansia')"
                        :disabled="exporting" class="flex items-center gap-2 rounded-lg border-orange-200 text-orange-700 hover:bg-orange-50">
                        <Loader2 v-if="exporting && exportType === 'pdf'" class="h-4 w-4 animate-spin" />
                        <FileText v-else class="h-4 w-4" />
                        {{ exporting && exportType === 'pdf' ? 'Exporting...' : 'Export PDF' }}
                    </Button>
                    <Button size="sm" @click="doExport('laporan.export.excel', 'lansia')"
                        :disabled="exporting" class="flex items-center gap-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
                        <Loader2 v-if="exporting && exportType === 'excel'" class="h-4 w-4 animate-spin" />
                        <Download v-else class="h-4 w-4" />
                        {{ exporting && exportType === 'excel' ? 'Exporting...' : 'Export Excel' }}
                    </Button>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-5">

                <!-- Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <Card class="border-none shadow-sm bg-white overflow-hidden">
                        <CardContent class="p-4 flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-gradient-to-br from-orange-400 to-amber-500 text-white shrink-0"><Users class="h-5 w-5" /></div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Total</p>
                                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ data.length }}</p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card class="border-none shadow-sm bg-white overflow-hidden">
                        <CardContent class="p-4 flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-emerald-100 text-emerald-600 shrink-0"><Users class="h-5 w-5" /></div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Aktif</p>
                                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ totalAktif }}</p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card class="border-none shadow-sm bg-white overflow-hidden col-span-2 sm:col-span-1">
                        <CardContent class="p-4 flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-blue-50 text-blue-500 shrink-0"><Users class="h-5 w-5" /></div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Laki-laki</p>
                                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ totalLaki }}<span class="text-sm font-normal text-muted-foreground"> / {{ data.length - totalLaki }}P</span></p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Filter -->
                <Card class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-orange-50 text-orange-500"><Filter class="h-4 w-4" /></div>
                            Filter
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5">
                        <div class="flex items-end gap-4">
                            <div class="space-y-1.5 w-64">
                                <label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Posyandu</label>
                                <Select v-model="filterState.posyandu_id">
                                    <SelectTrigger><SelectValue placeholder="Semua Posyandu" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">Semua Posyandu</SelectItem>
                                        <SelectItem v-for="p in posyandu" :key="p.id" :value="p.id">{{ p.nama_posyandu }}</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <Button @click="applyFilters" class="bg-orange-500 hover:bg-orange-600 text-white rounded-lg">Tampilkan</Button>
                        </div>
                    </CardContent>
                </Card>

                <!-- Table -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-50/70 hover:bg-gray-50/70">
                                <TableHead class="w-12 text-center pl-5 text-xs font-bold uppercase tracking-wider text-gray-500">No</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">NIK</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Nama</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">JK</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">Status</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(item, i) in data" :key="item.id" class="hover:bg-gray-50/50">
                                <TableCell class="text-center pl-5 text-muted-foreground text-sm">{{ i + 1 }}</TableCell>
                                <TableCell class="font-mono text-xs text-muted-foreground">{{ item.nik }}</TableCell>
                                <TableCell class="font-semibold text-sm text-gray-800">{{ item.nama }}</TableCell>
                                <TableCell class="text-center">
                                    <Badge :class="item.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700 border-blue-200 text-xs font-semibold' : 'bg-pink-50 text-pink-700 border-pink-200 text-xs font-semibold'">
                                        {{ item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-center">
                                    <Badge :class="item.is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 text-xs font-semibold' : 'bg-gray-100 text-gray-500 border-gray-200 text-xs font-semibold'">
                                        {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </Badge>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="data.length === 0">
                                <TableCell colspan="5" class="py-16">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-3 rounded-full bg-orange-50"><Users class="h-6 w-6 text-orange-300" /></div>
                                        <p class="text-sm font-medium text-gray-400">Tidak ada data lansia</p>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
                        <p class="text-xs text-muted-foreground">Total: <span class="font-semibold text-gray-700">{{ data.length }}</span> data</p>
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
