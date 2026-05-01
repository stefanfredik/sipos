<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    Table, TableBody, TableCell, TableHead,
    TableHeader, TableRow,
} from '@/components/ui/table';
import {
    DropdownMenu, DropdownMenuContent, DropdownMenuItem,
    DropdownMenuSeparator, DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '@/components/ui/alert-dialog';
import { ref, watch } from 'vue';
import {
    MoreHorizontal, Plus, Search, MapPin,
    Eye, Edit, Trash2, Building2, CheckCircle2, XCircle,
} from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

interface Posyandu {
    id: string;
    nama_posyandu: string;
    lokasi: string;
    is_active: boolean;
}

const props = defineProps<{
    posyandu: { data: Posyandu[]; links: any[]; meta: any };
    filters: { search?: string };
    stats?: { total: number; active: number; inactive: number };
}>();

const toast = useToast();
const search = ref(props.filters?.search ?? '');
const deleteTarget = ref<string | null>(null);

let searchTimer: ReturnType<typeof setTimeout> | null = null;
watch(search, () => {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        router.get(route('posyandu.index'), { search: search.value || undefined }, { preserveState: true, replace: true });
    }, 400);
});

function confirmDelete(id: string) { deleteTarget.value = id; }

function deletePosyandu() {
    const id = deleteTarget.value;
    if (!id) return;
    router.delete(route('posyandu.destroy', { posyandu: id }), {
        preserveScroll: true,
        onSuccess: () => {
            deleteTarget.value = null;
        },
        onError: () => {
            deleteTarget.value = null;
        }
    });
}
</script>

<template>
    <Head title="Data Posyandu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Data Posyandu</h2>
                    <p class="text-sm text-muted-foreground mt-0.5">Kelola unit posyandu di Desa Belumbang</p>
                </div>
                <Button
                    @click="router.get(route('posyandu.create'))"
                    class="bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2 rounded-lg shadow-sm shadow-blue-200"
                >
                    <Plus class="h-4 w-4" />
                    Tambah Posyandu
                </Button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <Card class="border-none shadow-sm bg-white overflow-hidden">
                        <CardContent class="p-0">
                            <div class="flex items-center gap-4 p-4">
                                <div class="p-2.5 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shrink-0">
                                    <Building2 class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Total Posyandu</p>
                                    <p class="text-2xl font-bold text-gray-900 leading-tight">{{ posyandu.meta?.total ?? 0 }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <Card class="border-none shadow-sm bg-white overflow-hidden">
                        <CardContent class="p-0">
                            <div class="flex items-center gap-4 p-4">
                                <div class="p-2.5 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 text-white shrink-0">
                                    <CheckCircle2 class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Aktif</p>
                                    <p class="text-2xl font-bold text-gray-900 leading-tight">
                                        {{ posyandu.data.filter(p => p.is_active).length }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <Card class="border-none shadow-sm bg-white overflow-hidden">
                        <CardContent class="p-0">
                            <div class="flex items-center gap-4 p-4">
                                <div class="p-2.5 rounded-xl bg-gradient-to-br from-gray-400 to-gray-500 text-white shrink-0">
                                    <XCircle class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Nonaktif</p>
                                    <p class="text-2xl font-bold text-gray-900 leading-tight">
                                        {{ posyandu.data.filter(p => !p.is_active).length }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Search -->
                <div class="relative max-w-sm">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" />
                    <Input v-model="search" placeholder="Cari nama atau lokasi..." class="pl-9" />
                </div>

                <!-- Table -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-50/70 hover:bg-gray-50/70">
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500 pl-5">Nama Posyandu</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Lokasi</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500 text-center">Status</TableHead>
                                <TableHead class="w-12" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="item in posyandu.data"
                                :key="item.id"
                                class="hover:bg-gray-50/50 cursor-pointer"
                                @click="router.get(route('posyandu.show', { posyandu: item.id }))"
                            >
                                <TableCell class="pl-5">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 rounded-lg bg-blue-50 shrink-0">
                                            <Building2 class="h-4 w-4 text-blue-600" />
                                        </div>
                                        <p class="font-semibold text-gray-800">{{ item.nama_posyandu }}</p>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-1.5 text-muted-foreground text-sm">
                                        <MapPin class="h-3.5 w-3.5 shrink-0" />
                                        {{ item.lokasi }}
                                    </div>
                                </TableCell>
                                <TableCell class="text-center">
                                    <Badge
                                        variant="outline"
                                        :class="item.is_active
                                            ? 'bg-green-50 text-green-700 border-green-200 text-xs font-semibold'
                                            : 'bg-gray-50 text-gray-500 border-gray-200 text-xs font-semibold'"
                                    >
                                        {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </Badge>
                                </TableCell>
                                <TableCell @click.stop>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="icon" class="h-8 w-8">
                                                <MoreHorizontal class="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="w-40">
                                            <DropdownMenuItem
                                                @click="router.get(route('posyandu.show', { posyandu: item.id }))"
                                                class="flex items-center gap-2"
                                            >
                                                <Eye class="h-4 w-4 text-gray-400" /> Lihat Detail
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                @click="router.get(route('posyandu.edit', { posyandu: item.id }))"
                                                class="flex items-center gap-2"
                                            >
                                                <Edit class="h-4 w-4 text-gray-400" /> Edit Data
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem
                                                class="text-destructive flex items-center gap-2 focus:text-destructive"
                                                @click="confirmDelete(item.id)"
                                            >
                                                <Trash2 class="h-4 w-4" /> Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="posyandu.data.length === 0">
                                <TableCell colspan="4" class="py-16">
                                    <div class="flex flex-col items-center gap-3 text-center">
                                        <div class="p-4 rounded-full bg-gray-100">
                                            <Building2 class="h-8 w-8 text-gray-300" />
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-500">Tidak ada data posyandu</p>
                                            <p class="text-sm text-muted-foreground mt-0.5">
                                                {{ search ? 'Coba ubah kata kunci pencarian.' : 'Mulai dengan menambahkan unit posyandu baru.' }}
                                            </p>
                                        </div>
                                        <Button v-if="!search" @click="router.get(route('posyandu.create'))" variant="outline" size="sm" class="mt-1">
                                            <Plus class="h-4 w-4 mr-1" /> Tambah Posyandu
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </Card>

                <!-- Pagination -->
                <div class="flex items-center justify-between text-sm">
                    <p class="text-muted-foreground">
                        Menampilkan <span class="font-semibold text-gray-700">{{ posyandu.data.length }}</span>
                        dari <span class="font-semibold text-gray-700">{{ posyandu.meta?.total }}</span> data
                    </p>
                    <div class="flex items-center gap-1">
                        <template v-for="link in posyandu.meta?.links" :key="link.label">
                            <button
                                v-if="link.url"
                                @click="router.get(link.url, {}, { preserveState: true })"
                                v-html="link.label"
                                :class="[
                                    'inline-flex h-8 min-w-8 items-center justify-center rounded-lg px-2.5 text-sm transition-colors',
                                    link.active ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'hover:bg-gray-100 text-gray-600'
                                ]"
                            />
                            <span v-else v-html="link.label" class="inline-flex h-8 min-w-8 items-center justify-center px-2 text-sm text-gray-300 pointer-events-none" />
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>

    <AlertDialog :open="!!deleteTarget" @update:open="(v) => { if (!v) deleteTarget = null }">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Hapus Data Posyandu</AlertDialogTitle>
                <AlertDialogDescription>Tindakan ini tidak dapat dibatalkan. Data posyandu akan dihapus secara permanen dari sistem.</AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Batal</AlertDialogCancel>
                <Button @click="deletePosyandu" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">Hapus</Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
