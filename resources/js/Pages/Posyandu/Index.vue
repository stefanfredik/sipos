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
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { MoreHorizontal, Plus, Search, MapPin } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

interface Posyandu {
    id: string;
    nama_posyandu: string;
    lokasi: string;
    is_active: boolean;
}

const props = defineProps<{
    posyandu: { data: Posyandu[]; links: any[]; meta: any };
    filters: { search?: string; is_active?: string };
}>();

const toast = useToast();
const search = ref(props.filters.search || '');

watch(
    search,
    debounce((value: string) => {
        router.get(
            route('posyandu.index'),
            { search: value },
            { preserveState: true, replace: true },
        );
    }, 500),
);

function deletePosyandu(id: string) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('posyandu.destroy', { posyandu: id }), {
            onSuccess: () => {
                toast.success('Berhasil', 'Data posyandu berhasil dihapus.');
            },
            onError: () => {
                toast.error('Gagal', 'Terjadi kesalahan saat menghapus data.');
            },
        });
    }
}
</script>

<template>
    <Head title="Data Posyandu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Data Posyandu</h2>
                <Link :href="route('posyandu.create')">
                    <Button size="sm">
                        <Plus class="mr-2 h-4 w-4" />
                        Tambah Posyandu
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
                        placeholder="Cari nama atau lokasi..."
                        class="pl-9"
                    />
                </div>
            </div>

            <Card>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Nama Posyandu</TableHead>
                            <TableHead>Lokasi</TableHead>
                            <TableHead class="text-center">Status</TableHead>
                            <TableHead class="w-12"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in posyandu.data" :key="item.id">
                            <TableCell class="font-medium">{{
                                item.nama_posyandu
                            }}</TableCell>
                            <TableCell>
                                <div
                                    class="flex items-center gap-2 text-muted-foreground"
                                >
                                    <MapPin class="h-4 w-4" />
                                    <span>{{ item.lokasi }}</span>
                                </div>
                            </TableCell>
                            <TableCell class="text-center">
                                <Badge
                                    :variant="
                                        item.is_active ? 'default' : 'secondary'
                                    "
                                >
                                    {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
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
                                                    route('posyandu.show', {
                                                        posyandu: item.id,
                                                    }),
                                                )
                                            "
                                            >Lihat Detail</DropdownMenuItem
                                        >
                                        <DropdownMenuItem
                                            @click="
                                                router.get(
                                                    route('posyandu.edit', {
                                                        posyandu: item.id,
                                                    }),
                                                )
                                            "
                                            >Edit Data</DropdownMenuItem
                                        >
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem
                                            class="text-destructive"
                                            @click="deletePosyandu(item.id)"
                                            >Hapus</DropdownMenuItem
                                        >
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="posyandu.data.length === 0">
                            <TableCell
                                colspan="4"
                                class="h-24 text-center text-muted-foreground"
                            >
                                Tidak ada data posyandu
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <div class="flex items-center justify-between text-sm">
                <p class="text-muted-foreground">
                    Menampilkan {{ posyandu.data.length }} dari
                    {{ posyandu.meta?.total }} data
                </p>
                <div class="flex items-center gap-1">
                    <Link
                        v-for="link in posyandu.meta?.links"
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
