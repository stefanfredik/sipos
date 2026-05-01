<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import {
    AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent,
    AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { ref, watch } from 'vue';
import { MoreHorizontal, Plus, Search, Calendar, Eye, Edit, Trash2, MapPin, Clock, ClipboardList, CheckCircle2, XCircle, AlertCircle } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

interface Jadwal {
    id: string;
    posyandu: { nama_posyandu: string };
    kader: { nama: string } | null;
    bidan: { nama: string } | null;
    tanggal: string;
    waktu_mulai: string;
    waktu_selesai: string | null;
    status: string;
    status_label: string;
}

const props = defineProps<{
    jadwal: { data: Jadwal[]; links: any[]; meta: any };
    filters?: { search?: string; status?: string };
}>();

const toast = useToast();
const search = ref(props.filters?.search ?? '');
const activeStatus = ref(props.filters?.status ?? '');
const deleteTarget = ref<string | null>(null);

let timer: ReturnType<typeof setTimeout> | null = null;
watch(search, () => {
    if (timer) clearTimeout(timer);
    timer = setTimeout(applyFilters, 400);
});

function applyFilters() {
    router.get(route('jadwal-posyandu.index'), {
        search: search.value || undefined,
        status: activeStatus.value || undefined,
    }, { preserveState: true, replace: true });
}

function setStatus(s: string) {
    activeStatus.value = activeStatus.value === s ? '' : s;
    applyFilters();
}

function openDeleteDialog(id: string) {
    deleteTarget.value = id;
}

function deleteJadwal() {
    if (!deleteTarget.value) return;
    router.delete(route('jadwal-posyandu.destroy', deleteTarget.value), {
        onSuccess: () => {  deleteTarget.value = null; },
        onError: () => {  deleteTarget.value = null; },
    });
}

function formatDate(d: string) {
    return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

const statusConfig: Record<string, { label: string; badgeClass: string; icon: any }> = {
    draft:     { label: 'Draft',     badgeClass: 'bg-gray-50 text-gray-600 border-gray-200',    icon: AlertCircle },
    validated: { label: 'Disetujui', badgeClass: 'bg-green-50 text-green-700 border-green-200', icon: CheckCircle2 },
    rejected:  { label: 'Ditolak',   badgeClass: 'bg-red-50 text-red-700 border-red-200',       icon: XCircle },
    completed: { label: 'Selesai',   badgeClass: 'bg-blue-50 text-blue-700 border-blue-200',    icon: CheckCircle2 },
};

function countStatus(s: string) {
    return props.jadwal.data.filter(j => j.status === s).length;
}
</script>

<template>
    <Head title="Jadwal Posyandu" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Jadwal Posyandu</h2>
                    <p class="text-sm text-muted-foreground mt-0.5">Kelola jadwal kegiatan posyandu</p>
                </div>
                <Button @click="router.get(route('jadwal-posyandu.create'))"
                    class="bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2 rounded-lg shadow-sm shadow-blue-200">
                    <Plus class="h-4 w-4" /> Buat Jadwal
                </Button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <Card v-for="(cfg, key) in statusConfig" :key="key"
                        class="border-none shadow-sm bg-white overflow-hidden cursor-pointer transition-all hover:shadow-md"
                        :class="activeStatus === key ? 'ring-2 ring-offset-1 ring-gray-300' : ''"
                        @click="setStatus(key)">
                        <CardContent class="p-4">
                            <div class="flex items-center gap-3">
                                <div :class="['p-2 rounded-lg border shrink-0', cfg.badgeClass]">
                                    <component :is="cfg.icon" class="h-4 w-4" />
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">{{ cfg.label }}</p>
                                    <p class="text-xl font-bold text-gray-900 leading-tight">{{ countStatus(key) }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Search & Status Filter -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1 max-w-sm">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" />
                        <Input v-model="search" placeholder="Cari posyandu atau petugas..." class="pl-9" />
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button v-for="(cfg, key) in statusConfig" :key="key" @click="setStatus(key)"
                            :class="['flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-semibold transition-all',
                                activeStatus === key ? cfg.badgeClass + ' shadow-sm' : 'bg-white border-gray-200 text-gray-500 hover:border-gray-300']">
                            {{ cfg.label }}
                        </button>
                        <button v-if="activeStatus || search" @click="search = ''; activeStatus = ''; applyFilters();"
                            class="px-3 py-1.5 text-sm text-gray-400 hover:text-gray-600 transition-colors">
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-50/70 hover:bg-gray-50/70">
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500 pl-5">Tanggal & Waktu</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Posyandu</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Kader</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Bidan</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">Status</TableHead>
                                <TableHead class="w-12" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="item in jadwal.data" :key="item.id"
                                class="hover:bg-gray-50/50 cursor-pointer"
                                @click="router.get(route('jadwal-posyandu.show', item.id))">
                                <TableCell class="pl-5">
                                    <div class="flex items-start gap-2">
                                        <div class="p-1.5 rounded-lg bg-amber-50 shrink-0 mt-0.5">
                                            <Calendar class="h-3.5 w-3.5 text-amber-600" />
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">{{ formatDate(item.tanggal) }}</p>
                                            <p class="text-xs text-muted-foreground flex items-center gap-1">
                                                <Clock class="h-3 w-3" />
                                                {{ item.waktu_mulai }}{{ item.waktu_selesai ? ' – ' + item.waktu_selesai : '' }}
                                            </p>
                                        </div>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-1.5">
                                        <MapPin class="h-3.5 w-3.5 text-gray-400 shrink-0" />
                                        <span class="font-medium text-gray-700 text-sm">{{ item.posyandu.nama_posyandu }}</span>
                                    </div>
                                </TableCell>
                                <TableCell class="text-sm text-muted-foreground">{{ item.kader?.nama || '—' }}</TableCell>
                                <TableCell class="text-sm text-muted-foreground">{{ item.bidan?.nama || '—' }}</TableCell>
                                <TableCell class="text-center">
                                    <Badge variant="outline"
                                        :class="['text-xs font-semibold px-2 py-0.5', statusConfig[item.status]?.badgeClass]">
                                        {{ statusConfig[item.status]?.label || item.status_label }}
                                    </Badge>
                                </TableCell>
                                <TableCell @click.stop>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="icon" class="h-8 w-8">
                                                <MoreHorizontal class="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="w-44">
                                            <DropdownMenuItem @click="router.get(route('jadwal-posyandu.show', item.id))" class="flex items-center gap-2">
                                                <Eye class="h-4 w-4 text-gray-400" /> Lihat Detail
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="router.get(route('jadwal-posyandu.edit', item.id))" class="flex items-center gap-2">
                                                <Edit class="h-4 w-4 text-gray-400" /> Edit / Validasi
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem class="text-destructive flex items-center gap-2 focus:text-destructive" @click="openDeleteDialog(item.id)">
                                                <Trash2 class="h-4 w-4" /> Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="jadwal.data.length === 0">
                                <TableCell colspan="6" class="py-16">
                                    <div class="flex flex-col items-center gap-3 text-center">
                                        <div class="p-4 rounded-full bg-gray-100">
                                            <ClipboardList class="h-8 w-8 text-gray-300" />
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-500">Belum ada jadwal posyandu</p>
                                            <p class="text-sm text-muted-foreground mt-0.5">
                                                {{ search || activeStatus ? 'Coba ubah filter pencarian.' : 'Mulai dengan membuat jadwal baru.' }}
                                            </p>
                                        </div>
                                        <Button v-if="!search && !activeStatus" @click="router.get(route('jadwal-posyandu.create'))" variant="outline" size="sm">
                                            <Plus class="h-4 w-4 mr-1" /> Buat Jadwal
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
                        Menampilkan <span class="font-semibold text-gray-700">{{ jadwal.data.length }}</span>
                        dari <span class="font-semibold text-gray-700">{{ jadwal.meta?.total }}</span> data
                    </p>
                    <div class="flex items-center gap-1">
                        <template v-for="link in jadwal.meta?.links" :key="link.label">
                            <button v-if="link.url" @click="router.get(link.url, {}, { preserveState: true })" v-html="link.label"
                                :class="['inline-flex h-8 min-w-8 items-center justify-center rounded-lg px-2.5 text-sm transition-colors',
                                    link.active ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'hover:bg-gray-100 text-gray-600']" />
                            <span v-else v-html="link.label" class="inline-flex h-8 min-w-8 items-center justify-center px-2 text-sm text-gray-300 pointer-events-none" />
                        </template>
                    </div>
                </div>

            </div>
        </div>

        <!-- Delete Confirm Dialog -->
        <AlertDialog :open="!!deleteTarget" @update:open="(v) => { if (!v) deleteTarget = null }">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Hapus Jadwal Posyandu?</AlertDialogTitle>
                    <AlertDialogDescription>
                        Jadwal posyandu ini akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel @click="deleteTarget = null">Batal</AlertDialogCancel>
                    <Button @click="deleteJadwal" class="bg-destructive hover:bg-destructive/90">Hapus</Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AuthenticatedLayout>
</template>
