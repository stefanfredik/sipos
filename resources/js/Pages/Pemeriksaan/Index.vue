<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table'
import {
    DropdownMenu, DropdownMenuContent, DropdownMenuItem,
    DropdownMenuSeparator, DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { ref } from 'vue'
import { MoreHorizontal, Plus, Search, Activity, User, Baby, Users } from 'lucide-vue-next'

interface Pemeriksaan {
    id: string
    tgl_pemeriksaan: string
    peserta_type: 'ibu_hamil' | 'balita' | 'lansia'
    peserta_id: string
    berat_badan: string
    tinggi_badan: string
    tensi_darah: string
    hadir: boolean
    peserta: {
        nama: string
        nik: string
    }
}

const props = defineProps<{
    pemeriksaan: { data: Pemeriksaan[]; links: any[]; meta: any }
    stats: {
        total_pemeriksaan: number
        bumil_count: number
        balita_count: number
        lansia_count: number
    }
}>()

const search = ref('')

function deletePemeriksaan(id: string) {
    if (confirm('Apakah Anda yakin ingin menghapus data pemeriksaan ini?')) {
        router.delete(route('pemeriksaan.destroy', id))
    }
}

function getPesertaLabel(type: string) {
    switch (type) {
        case 'ibu_hamil': return 'Ibu Hamil'
        case 'balita': return 'Balita'
        case 'lansia': return 'Lansia'
        default: return type
    }
}
</script>

<template>
    <Head title="Data Pemeriksaan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Data Pemeriksaan</h2>
                <Link :href="route('pemeriksaan.create')">
                    <Button size="sm">
                        <Plus class="mr-2 h-4 w-4" />
                        Input Pemeriksaan
                    </Button>
                </Link>
            </div>
        </template>

        <div class="space-y-4">
            <!-- Stats -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2 space-y-0">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Total Periksa</CardTitle>
                        <Activity class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_pemeriksaan }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2 space-y-0">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Ibu Hamil</CardTitle>
                        <User class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.bumil_count }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2 space-y-0">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Balita</CardTitle>
                        <Baby class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.balita_count }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2 space-y-0">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Lansia</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.lansia_count }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Search -->
            <div class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <Input v-model="search" placeholder="Cari nama atau NIK..." class="pl-9" />
                </div>
            </div>

            <!-- Table -->
            <Card>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Tgl. Periksa</TableHead>
                            <TableHead>Kategori</TableHead>
                            <TableHead>Nama / NIK</TableHead>
                            <TableHead class="text-center">BB / TB</TableHead>
                            <TableHead class="text-center">Tensi</TableHead>
                            <TableHead class="text-center">Status</TableHead>
                            <TableHead class="w-12"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in pemeriksaan.data" :key="item.id">
                            <TableCell class="text-muted-foreground">
                                {{ new Date(item.tgl_pemeriksaan).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }) }}
                            </TableCell>
                            <TableCell>
                                <Badge variant="outline">{{ getPesertaLabel(item.peserta_type) }}</Badge>
                            </TableCell>
                            <TableCell>
                                <div>
                                    <p class="font-medium">{{ item.peserta?.nama || '-' }}</p>
                                    <p class="text-xs text-muted-foreground font-mono">{{ item.peserta?.nik }}</p>
                                </div>
                            </TableCell>
                            <TableCell class="text-center">
                                <div>
                                    <p class="text-sm font-medium">{{ item.berat_badan || '-' }} kg</p>
                                    <p class="text-xs text-muted-foreground">{{ item.tinggi_badan || '-' }} cm</p>
                                </div>
                            </TableCell>
                            <TableCell class="text-center">
                                <span v-if="item.tensi_darah" class="text-sm font-mono">{{ item.tensi_darah }}</span>
                                <span v-else class="text-muted-foreground">-</span>
                            </TableCell>
                            <TableCell class="text-center">
                                <Badge :variant="item.hadir ? 'default' : 'secondary'">
                                    {{ item.hadir ? 'Hadir' : 'Absen' }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="icon" class="h-8 w-8">
                                            <MoreHorizontal class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem @click="router.get(route('pemeriksaan.show', item.id))">Lihat Detail</DropdownMenuItem>
                                        <DropdownMenuItem @click="router.get(route('pemeriksaan.edit', item.id))">Edit Data</DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem class="text-destructive" @click="deletePemeriksaan(item.id)">Hapus</DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="pemeriksaan.data.length === 0">
                            <TableCell colspan="7" class="h-24 text-center text-muted-foreground">
                                Tidak ada data pemeriksaan
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <!-- Pagination -->
            <div class="flex items-center justify-between text-sm">
                <p class="text-muted-foreground">
                    Menampilkan {{ pemeriksaan.data.length }} dari {{ pemeriksaan.meta?.total }} data
                </p>
                <div class="flex items-center gap-1">
                    <Link
                        v-for="link in pemeriksaan.meta?.links" :key="link.label" :href="link.url || '#'"
                        class="inline-flex h-8 min-w-8 items-center justify-center rounded-md px-2 text-sm"
                        :class="{
                            'bg-primary text-primary-foreground': link.active,
                            'hover:bg-muted': !link.active && link.url,
                            'pointer-events-none opacity-50': !link.url,
                        }"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
