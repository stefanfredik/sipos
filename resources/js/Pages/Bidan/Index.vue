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

interface Bidan {
    id: string
    nama: string
    foto: string | null
    no_telp: string
    username: string
    jenis_kelamin: string
}

const props = defineProps<{
    bidan: { data: Bidan[]; links: any[]; meta: any }
    filters: { search?: string }
}>()

const search = ref(props.filters.search || '')

watch(search, debounce((value: string) => {
    router.get(route('bidan.index'), { search: value }, { preserveState: true, replace: true })
}, 500))

function deleteBidan(id: string) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('bidan.destroy', id))
    }
}
</script>

<template>
    <Head title="Data Bidan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Data Bidan</h2>
                <Link :href="route('bidan.create')">
                    <Button size="sm">
                        <Plus class="mr-2 h-4 w-4" />
                        Tambah Bidan
                    </Button>
                </Link>
            </div>
        </template>

        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <Input v-model="search" placeholder="Cari nama atau username..." class="pl-9" />
                </div>
            </div>

            <Card>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-12">Foto</TableHead>
                            <TableHead>Nama / Username</TableHead>
                            <TableHead>Kontak</TableHead>
                            <TableHead class="text-center">JK</TableHead>
                            <TableHead class="w-12"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in bidan.data" :key="item.id">
                            <TableCell>
                                <div class="flex h-9 w-9 items-center justify-center rounded-md bg-muted overflow-hidden">
                                    <img v-if="item.foto" :src="item.foto" :alt="item.nama" class="h-full w-full object-cover" />
                                    <User v-else class="h-4 w-4 text-muted-foreground" />
                                </div>
                            </TableCell>
                            <TableCell>
                                <div>
                                    <p class="font-medium">{{ item.nama }}</p>
                                    <p class="text-xs text-muted-foreground font-mono">@{{ item.username }}</p>
                                </div>
                            </TableCell>
                            <TableCell class="text-muted-foreground">{{ item.no_telp }}</TableCell>
                            <TableCell class="text-center">
                                <Badge variant="outline">{{ item.jenis_kelamin === 'L' ? 'L' : 'P' }}</Badge>
                            </TableCell>
                            <TableCell>
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="icon" class="h-8 w-8">
                                            <MoreHorizontal class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem @click="router.get(route('bidan.show', item.id))">Lihat Detail</DropdownMenuItem>
                                        <DropdownMenuItem @click="router.get(route('bidan.edit', item.id))">Edit Data</DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem class="text-destructive" @click="deleteBidan(item.id)">Hapus</DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="bidan.data.length === 0">
                            <TableCell colspan="5" class="h-24 text-center text-muted-foreground">
                                Tidak ada data bidan
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <div class="flex items-center justify-between text-sm">
                <p class="text-muted-foreground">
                    Menampilkan {{ bidan.data.length }} dari {{ bidan.meta?.total }} data
                </p>
                <div class="flex items-center gap-1">
                    <Link
                        v-for="link in bidan.meta?.links" :key="link.label" :href="link.url || '#'"
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
