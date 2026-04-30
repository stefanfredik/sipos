<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { ref } from 'vue';
import { MoreHorizontal, Plus, Search, Calendar } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

interface Jadwal {
    id: string;
    posyandu: { nama_posyandu: string };
    kader: { nama_kader: string } | null;
    bidan: { nama_bidan: string } | null;
    kader: { nama: string } | null;
    bidan: { nama: string } | null;
    tanggal: string;
    waktu_mulai: string;
    waktu_selesai: string | null;
    status: string;
    status_label: string;
    catatan_bidan: string | null;
}

const props = defineProps<{
    jadwal: { data: Jadwal[]; links: any[]; meta: any };
}>();

const toast = useToast();
const search = ref('');

function deleteJadwal(id: string) {
    if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
        router.delete(route('jadwal-posyandu.destroy', id), {
            onSuccess: () => {
                toast.success('Berhasil', 'Jadwal berhasil dihapus.');
            },
            onError: () => {
                toast.error(
                    'Gagal',
                    'Terjadi kesalahan saat menghapus jadwal.',
                );
            },
        });
    }
}

function getStatusVariant(status: string) {
    switch (status) {
        case 'draft':
            return 'outline' as const;
        case 'validated':
            return 'secondary' as const;
        case 'rejected':
            return 'destructive' as const;
        case 'completed':
            return 'default' as const;
        default:
            return 'outline' as const;
    }
}
</script>

<template>
    <Head title="Jadwal Posyandu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Jadwal Posyandu</h2>
                <Link :href="route('jadwal-posyandu.create')">
                    <Button size="sm">
                        <Plus class="mr-2 h-4 w-4" />
                        Tambah Jadwal
                    </Button>
                </Link>
            </div>
        </template>

        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="relative max-w-sm flex-1">
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                    />
                    <Input
                        v-model="search"
                        placeholder="Cari posyandu atau petugas..."
                        class="pl-9"
                    />
                </div>
            </div>

            <Card>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Tanggal & Waktu</TableHead>
                            <TableHead>Posyandu</TableHead>
                            <TableHead>Kader</TableHead>
                            <TableHead>Bidan</TableHead>
                            <TableHead class="text-center">Status</TableHead>
                            <TableHead class="w-12"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in jadwal.data" :key="item.id">
                            <TableCell>
                                <div>
                                    <p class="font-medium">
                                        {{ item.tanggal }}
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        {{ item.waktu_mulai }} -
                                        {{ item.waktu_selesai || 'Selesai' }}
                                    </p>
                                </div>
                            </TableCell>
                            <TableCell class="font-medium">{{
                                item.posyandu.nama_posyandu
                            }}</TableCell>
                            <TableCell class="text-muted-foreground">{{
                                item.kader?.nama_kader || '-'
                            }}</TableCell>
                            <TableCell class="text-muted-foreground">{{
                                item.bidan?.nama_bidan || '-'
                            }}</TableCell>
                            <TableCell class="text-center">
                                <Badge :variant="getStatusVariant(item.status)">
                                    {{ item.status_label }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-8 w-8"
                                        >
                                            <MoreHorizontal class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem
                                            @click="
                                                router.get(
                                                    route(
                                                        'jadwal-posyandu.edit',
                                                        item.id,
                                                    ),
                                                )
                                            "
                                            >Edit / Validasi</DropdownMenuItem
                                        >
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem
                                            class="text-destructive"
                                            @click="deleteJadwal(item.id)"
                                            >Hapus</DropdownMenuItem
                                        >
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="jadwal.data.length === 0">
                            <TableCell
                                colspan="6"
                                class="h-24 text-center text-muted-foreground"
                            >
                                Belum ada jadwal posyandu
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <div class="flex items-center justify-between text-sm">
                <p class="text-muted-foreground">
                    Menampilkan {{ jadwal.data.length }} dari
                    {{ jadwal.meta?.total }} data
                </p>
                <div class="flex items-center gap-1">
                    <Link
                        v-for="link in jadwal.meta?.links"
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
