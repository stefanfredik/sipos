<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Card } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table'
import {
    DropdownMenu, DropdownMenuContent, DropdownMenuItem,
    DropdownMenuSeparator, DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { ref, watch } from 'vue'
import { debounce } from 'lodash'
import { MoreHorizontal, Plus, Search, User } from 'lucide-vue-next'

interface Balita {
    id: string
    nik: string
    nama: string
    foto: string | null
    nama_orang_tua: string
    tgl_lahir: string
    umur_label: string
    jenis_kelamin: string
    is_active: boolean
}

const props = defineProps<{
    balita: { data: Balita[]; links: any[]; meta: any }
    filters: { search?: string; is_active?: string }
}>()

const search = ref(props.filters.search || '')

watch(search, debounce((value: string) => {
    router.get(route('balita.index'), { search: value }, { preserveState: true, replace: true })
}, 500))

function deleteBalita(id: string) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('balita.destroy', { balita: id }))
    }
}
</script>

<template>
    <Head title="Data Balita" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Data Balita</h2>
                <Link :href="route('balita.create')">
                    <Button size="sm">
                        <Plus class="mr-2 h-4 w-4" />
                        Tambah Balita
                    </Button>
                </Link>
            </div>
        </template>

        <div class="space-y-4">
            <!-- Search -->
            <div class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <Input v-model="search" placeholder="Cari nama, NIK..." class="pl-9" />
                </div>
            </div>

            <!-- Table -->
            <Card>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-12">Foto</TableHead>
                            <TableHead>Nama / NIK</TableHead>
                            <TableHead>Orang Tua</TableHead>
                            <TableHead class="text-center">Umur</TableHead>
                            <TableHead class="text-center">JK</TableHead>
                            <TableHead class="text-center">Status</TableHead>
                            <TableHead class="w-12"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in balita.data" :key="item.id">
                            <TableCell>
                                <div class="flex h-9 w-9 items-center justify-center rounded-md bg-muted overflow-hidden">
                                    <img v-if="item.foto" :src="item.foto" :alt="item.nama" class="h-full w-full object-cover" />
                                    <User v-else class="h-4 w-4 text-muted-foreground" />
                                </div>
                            </TableCell>
                            <TableCell>
                                <div>
                                    <p class="font-medium">{{ item.nama }}</p>
                                    <p class="text-xs text-muted-foreground font-mono">{{ item.nik }}</p>
                                </div>
                            </TableCell>
                            <TableCell class="text-muted-foreground">{{ item.nama_orang_tua }}</TableCell>
                            <TableCell class="text-center text-sm">{{ item.umur_label }}</TableCell>
                            <TableCell class="text-center">
                                <Badge variant="outline">{{ item.jenis_kelamin === 'L' ? 'L' : 'P' }}</Badge>
                            </TableCell>
                            <TableCell class="text-center">
                                <Badge :variant="item.is_active ? 'default' : 'secondary'">
                                    {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
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
                                        <DropdownMenuItem @click="router.get(route('balita.show', { balita: item.id }))">
                                            Lihat Detail
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="router.get(route('balita.edit', { balita: item.id }))">
                                            Edit Data
                                        </DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem class="text-destructive" @click="deleteBalita(item.id)">
                                            Hapus
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="balita.data.length === 0">
                            <TableCell colspan="7" class="h-24 text-center text-muted-foreground">
                                Tidak ada data balita
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <!-- Pagination -->
            <div class="flex items-center justify-between text-sm">
                <p class="text-muted-foreground">
                    Menampilkan {{ balita.data.length }} dari {{ balita.meta?.total }} data
                </p>
                <div class="flex items-center gap-1">
                    <Link
                        v-for="link in balita.meta?.links"
                        :key="link.label"
                        :href="link.url || '#'"
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
