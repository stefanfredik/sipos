<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '@/components/ui/alert-dialog';
import { ref, watch, computed } from 'vue';
import { MoreHorizontal, Plus, Search, User, Eye, Edit, Trash2, Baby, Users, HeartPulse } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

interface Balita {
    id: string; nik: string; nama: string; foto: string | null;
    nama_orang_tua: string; tgl_lahir: string; umur_label: string;
    jenis_kelamin: string; is_active: boolean;
}

const props = defineProps<{
    balita: { data: Balita[]; links: any[]; meta: any };
    filters: { search?: string; is_active?: string };
}>();

const toast = useToast();
const search = ref(props.filters?.search ?? '');
const deleteTarget = ref<string | null>(null);
const totalAktif = computed(() => props.balita.data.filter(i => i.is_active).length);

let timer: ReturnType<typeof setTimeout> | null = null;
watch(search, () => {
    if (timer) clearTimeout(timer);
    timer = setTimeout(() => {
        router.get(route('balita.index'), { search: search.value || undefined }, { preserveState: true, replace: true });
    }, 400);
});

function confirmDelete(id: string) { deleteTarget.value = id; }

function deleteBalita() {
    const id = deleteTarget.value;
    if (!id) return;
    router.delete(route('balita.destroy', { balita: id }), {
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
    <Head title="Data Balita" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Data Balita</h2>
                    <p class="text-sm text-muted-foreground mt-0.5">Kelola peserta balita Posyandu Desa Belumbang</p>
                </div>
                <Button @click="router.get(route('balita.create'))"
                    class="bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2 rounded-lg shadow-sm shadow-blue-200">
                    <Plus class="h-4 w-4" /> Tambah Balita
                </Button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <Card class="border-none shadow-sm bg-white overflow-hidden">
                        <CardContent class="p-4 flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shrink-0"><Baby class="h-5 w-5" /></div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Total</p>
                                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ balita.meta?.total ?? balita.data.length }}</p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card class="border-none shadow-sm bg-white overflow-hidden">
                        <CardContent class="p-4 flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-emerald-100 text-emerald-600 shrink-0"><HeartPulse class="h-5 w-5" /></div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Aktif</p>
                                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ totalAktif }}</p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card class="border-none shadow-sm bg-white overflow-hidden col-span-2 sm:col-span-1">
                        <CardContent class="p-4 flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-gray-100 text-gray-500 shrink-0"><Users class="h-5 w-5" /></div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Tidak Aktif</p>
                                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ balita.data.length - totalAktif }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div class="relative max-w-sm">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" />
                    <Input v-model="search" placeholder="Cari nama, NIK..." class="pl-9" />
                </div>

                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-50/70 hover:bg-gray-50/70">
                                <TableHead class="w-12 pl-5 text-xs font-bold uppercase tracking-wider text-gray-500">Foto</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Nama / NIK</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Orang Tua</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">Umur</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">JK</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">Status</TableHead>
                                <TableHead class="w-12" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="item in balita.data" :key="item.id"
                                class="hover:bg-gray-50/50 cursor-pointer"
                                @click="router.get(route('balita.show', { balita: item.id }))">
                                <TableCell class="pl-5">
                                    <div class="h-9 w-9 rounded-full overflow-hidden bg-blue-50 flex items-center justify-center border border-blue-100">
                                        <img v-if="item.foto" :src="item.foto" :alt="item.nama" class="h-full w-full object-cover" />
                                        <Baby v-else class="h-4 w-4 text-blue-400" />
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <p class="font-semibold text-gray-800 text-sm">{{ item.nama }}</p>
                                    <p class="font-mono text-xs text-muted-foreground">{{ item.nik }}</p>
                                </TableCell>
                                <TableCell class="text-sm text-muted-foreground">{{ item.nama_orang_tua }}</TableCell>
                                <TableCell class="text-center text-sm text-muted-foreground">{{ item.umur_label }}</TableCell>
                                <TableCell class="text-center">
                                    <Badge variant="outline" :class="item.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700 border-blue-200 text-xs font-semibold' : 'bg-pink-50 text-pink-700 border-pink-200 text-xs font-semibold'">
                                        {{ item.jenis_kelamin === 'L' ? 'L' : 'P' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-center">
                                    <Badge :class="item.is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 text-xs font-semibold' : 'bg-gray-100 text-gray-500 border-gray-200 text-xs font-semibold'">
                                        {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </Badge>
                                </TableCell>
                                <TableCell @click.stop>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="icon" class="h-8 w-8"><MoreHorizontal class="h-4 w-4" /></Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="w-44">
                                            <DropdownMenuItem @click="router.get(route('balita.show', { balita: item.id }))" class="flex items-center gap-2">
                                                <Eye class="h-4 w-4 text-gray-400" /> Lihat Detail
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="router.get(route('balita.edit', { balita: item.id }))" class="flex items-center gap-2">
                                                <Edit class="h-4 w-4 text-gray-400" /> Edit Data
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem class="text-destructive flex items-center gap-2 focus:text-destructive" @click="confirmDelete(item.id)">
                                                <Trash2 class="h-4 w-4" /> Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="balita.data.length === 0">
                                <TableCell colspan="7" class="py-16">
                                    <div class="flex flex-col items-center gap-3 text-center">
                                        <div class="p-4 rounded-full bg-blue-50"><Baby class="h-8 w-8 text-blue-300" /></div>
                                        <div>
                                            <p class="font-semibold text-gray-500">Tidak ada data balita</p>
                                            <p class="text-sm text-muted-foreground mt-0.5">{{ search ? 'Coba ubah kata kunci pencarian.' : 'Mulai dengan mendaftarkan balita baru.' }}</p>
                                        </div>
                                        <Button v-if="!search" @click="router.get(route('balita.create'))" variant="outline" size="sm">
                                            <Plus class="h-4 w-4 mr-1" /> Tambah Balita
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </Card>

                <div class="flex items-center justify-between text-sm">
                    <p class="text-muted-foreground">
                        Menampilkan <span class="font-semibold text-gray-700">{{ balita.data.length }}</span>
                        dari <span class="font-semibold text-gray-700">{{ balita.meta?.total }}</span> data
                    </p>
                    <div class="flex items-center gap-1">
                        <template v-for="link in balita.meta?.links" :key="link.label">
                            <button v-if="link.url" @click="router.get(link.url, {}, { preserveState: true })" v-html="link.label"
                                :class="['inline-flex h-8 min-w-8 items-center justify-center rounded-lg px-2.5 text-sm transition-colors',
                                    link.active ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'hover:bg-gray-100 text-gray-600']" />
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
                <AlertDialogTitle>Hapus Data Balita</AlertDialogTitle>
                <AlertDialogDescription>Tindakan ini tidak dapat dibatalkan. Data balita akan dihapus secara permanen dari sistem.</AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Batal</AlertDialogCancel>
                <Button @click="deleteBalita" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">Hapus</Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
