<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent } from '@/components/ui/card';
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
import {
    AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent,
    AlertDialogDescription, AlertDialogFooter, AlertDialogHeader,
    AlertDialogTitle, AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { ref, watch } from 'vue';
import {
    MoreHorizontal,
    Plus,
    Search,
    Activity,
    User,
    Baby,
    Users,
    Eye,
    Edit,
    Trash2,
    ClipboardList,
    Heart,
} from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

interface Pemeriksaan {
    id: string;
    tgl_pemeriksaan: string;
    peserta_type: 'ibu_hamil' | 'balita' | 'lansia';
    peserta_id: string;
    berat_badan: string | null;
    tinggi_badan: string | null;
    sistole: number | null;
    diastole: number | null;
    hadir: boolean;
    peserta: { nama: string; nik: string } | null;
}

const props = defineProps<{
    pemeriksaan: { data: Pemeriksaan[]; links: any[]; meta: any };
    stats: {
        total_pemeriksaan: number;
        bumil_count: number;
        balita_count: number;
        lansia_count: number;
    };
    filters: { search?: string; type?: string };
}>();

const toast = useToast();
const search = ref(props.filters?.search ?? '');
const activeType = ref(props.filters?.type ?? '');
const deleteTarget = ref<string | null>(null);

let searchTimer: ReturnType<typeof setTimeout> | null = null;
watch(search, (val) => {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => applyFilters(), 400);
});

function applyFilters() {
    router.get(route('pemeriksaan.index'), {
        search: search.value || undefined,
        type: activeType.value || undefined,
    }, { preserveState: true, replace: true });
}

function setType(type: string) {
    activeType.value = activeType.value === type ? '' : type;
    applyFilters();
}

function confirmDelete(id: string) {
    deleteTarget.value = id;
}

function deletePemeriksaan() {
    if (!deleteTarget.value) return;
    router.delete(route('pemeriksaan.destroy', deleteTarget.value), {
        onSuccess: () => { toast.success('Berhasil', 'Data pemeriksaan berhasil dihapus.'); deleteTarget.value = null; },
        onError: () => { toast.error('Gagal', 'Terjadi kesalahan saat menghapus data.'); deleteTarget.value = null; },
    });
}

const categoryConfig = {
    ibu_hamil: {
        label: 'Ibu Hamil',
        icon: User,
        textColor: 'text-pink-700',
        bg: 'bg-pink-50',
        border: 'border-pink-200',
        badgeClass: 'bg-pink-50 text-pink-700 border-pink-200',
        statBg: 'bg-gradient-to-br from-pink-500 to-rose-500',
        count: () => props.stats.bumil_count,
    },
    balita: {
        label: 'Balita',
        icon: Baby,
        textColor: 'text-blue-700',
        bg: 'bg-blue-50',
        border: 'border-blue-200',
        badgeClass: 'bg-blue-50 text-blue-700 border-blue-200',
        statBg: 'bg-gradient-to-br from-blue-500 to-cyan-500',
        count: () => props.stats.balita_count,
    },
    lansia: {
        label: 'Lansia',
        icon: Users,
        textColor: 'text-emerald-700',
        bg: 'bg-emerald-50',
        border: 'border-emerald-200',
        badgeClass: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        statBg: 'bg-gradient-to-br from-emerald-500 to-teal-500',
        count: () => props.stats.lansia_count,
    },
};

function formatDate(dateStr: string) {
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric',
    });
}

function formatTensi(item: Pemeriksaan) {
    if (item.sistole && item.diastole) return `${item.sistole}/${item.diastole}`;
    return null;
}
</script>

<template>
    <Head title="Data Pemeriksaan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Data Pemeriksaan</h2>
                    <p class="text-sm text-muted-foreground mt-0.5">Riwayat pemeriksaan kesehatan peserta posyandu</p>
                </div>
                <Button
                    @click="router.get(route('pemeriksaan.create'))"
                    class="bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2 rounded-lg shadow-sm shadow-blue-200"
                >
                    <Plus class="h-4 w-4" />
                    Input Pemeriksaan
                </Button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Stats Cards -->
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Total -->
                    <Card class="border-none shadow-sm bg-white overflow-hidden">
                        <CardContent class="p-0">
                            <div class="flex items-center gap-4 p-4">
                                <div class="p-2.5 rounded-xl bg-gradient-to-br from-slate-600 to-slate-700 text-white shrink-0">
                                    <Activity class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Total Periksa</p>
                                    <p class="text-2xl font-bold text-gray-900 leading-tight">{{ stats.total_pemeriksaan }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Per Kategori -->
                    <Card
                        v-for="(cfg, type) in categoryConfig"
                        :key="type"
                        class="border-none shadow-sm bg-white overflow-hidden cursor-pointer transition-all hover:shadow-md"
                        :class="activeType === type ? 'ring-2 ring-offset-1 ' + cfg.border : ''"
                        @click="setType(type)"
                    >
                        <CardContent class="p-0">
                            <div class="flex items-center gap-4 p-4">
                                <div :class="['p-2.5 rounded-xl text-white shrink-0', cfg.statBg]">
                                    <component :is="cfg.icon" class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">{{ cfg.label }}</p>
                                    <p class="text-2xl font-bold text-gray-900 leading-tight">{{ cfg.count() }}</p>
                                </div>
                                <div v-if="activeType === type" class="ml-auto">
                                    <div :class="['w-2 h-2 rounded-full', cfg.statBg]" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Search & Filter Bar -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1 max-w-sm">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" />
                        <Input
                            v-model="search"
                            placeholder="Cari nama atau NIK peserta..."
                            class="pl-9"
                        />
                    </div>

                    <!-- Type Filter Pills -->
                    <div class="flex items-center gap-2">
                        <button
                            v-for="(cfg, type) in categoryConfig"
                            :key="type"
                            @click="setType(type)"
                            :class="[
                                'flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-sm font-medium transition-all',
                                activeType === type
                                    ? cfg.badgeClass + ' shadow-sm'
                                    : 'bg-white border-gray-200 text-gray-500 hover:border-gray-300'
                            ]"
                        >
                            <component :is="cfg.icon" class="h-3.5 w-3.5" />
                            {{ cfg.label }}
                        </button>
                        <button
                            v-if="activeType || search"
                            @click="search = ''; activeType = ''; applyFilters();"
                            class="px-3 py-1.5 text-sm text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-50/70 hover:bg-gray-50/70">
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500 pl-5">Tanggal</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Kategori</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Nama / NIK</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500 text-center">BB / TB</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500 text-center">Tensi</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500 text-center">Status</TableHead>
                                <TableHead class="w-12" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="item in pemeriksaan.data"
                                :key="item.id"
                                class="hover:bg-gray-50/50 cursor-pointer"
                                @click="router.get(route('pemeriksaan.show', item.id))"
                            >
                                <TableCell class="pl-5">
                                    <p class="text-sm font-semibold text-gray-700">{{ formatDate(item.tgl_pemeriksaan) }}</p>
                                </TableCell>

                                <TableCell>
                                    <Badge
                                        variant="outline"
                                        :class="['text-xs font-semibold px-2 py-0.5', categoryConfig[item.peserta_type]?.badgeClass]"
                                    >
                                        <component :is="categoryConfig[item.peserta_type]?.icon" class="h-3 w-3 mr-1" />
                                        {{ categoryConfig[item.peserta_type]?.label }}
                                    </Badge>
                                </TableCell>

                                <TableCell>
                                    <p class="font-semibold text-gray-800 text-sm">{{ item.peserta?.nama || '—' }}</p>
                                    <p class="text-xs text-muted-foreground font-mono">{{ item.peserta?.nik || '' }}</p>
                                </TableCell>

                                <TableCell class="text-center">
                                    <div v-if="item.berat_badan || item.tinggi_badan">
                                        <p class="text-sm font-semibold text-gray-700">{{ item.berat_badan || '—' }} <span class="text-xs font-normal text-gray-400">kg</span></p>
                                        <p class="text-xs text-muted-foreground">{{ item.tinggi_badan || '—' }} <span class="text-[10px]">cm</span></p>
                                    </div>
                                    <span v-else class="text-gray-300">—</span>
                                </TableCell>

                                <TableCell class="text-center">
                                    <div v-if="formatTensi(item)" class="inline-flex items-center gap-1">
                                        <Heart class="h-3 w-3 text-red-400" />
                                        <span class="font-mono text-sm font-semibold text-gray-700">{{ formatTensi(item) }}</span>
                                        <span class="text-[10px] text-gray-400">mmHg</span>
                                    </div>
                                    <span v-else class="text-gray-300">—</span>
                                </TableCell>

                                <TableCell class="text-center">
                                    <Badge
                                        variant="outline"
                                        :class="item.hadir
                                            ? 'bg-green-50 text-green-700 border-green-200 text-xs font-semibold'
                                            : 'bg-red-50 text-red-600 border-red-200 text-xs font-semibold'"
                                    >
                                        {{ item.hadir ? 'Hadir' : 'Tidak Hadir' }}
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
                                            <DropdownMenuItem
                                                @click="router.get(route('pemeriksaan.show', item.id))"
                                                class="flex items-center gap-2"
                                            >
                                                <Eye class="h-4 w-4 text-gray-400" />
                                                Lihat Detail
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                @click="router.get(route('pemeriksaan.edit', item.id))"
                                                class="flex items-center gap-2"
                                            >
                                                <Edit class="h-4 w-4 text-gray-400" />
                                                Edit Data
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem
                                                class="text-destructive flex items-center gap-2 focus:text-destructive"
                                                @click="confirmDelete(item.id)"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                                Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>

                            <!-- Empty State -->
                            <TableRow v-if="pemeriksaan.data.length === 0">
                                <TableCell colspan="7" class="py-16">
                                    <div class="flex flex-col items-center gap-3 text-center">
                                        <div class="p-4 rounded-full bg-gray-100">
                                            <ClipboardList class="h-8 w-8 text-gray-300" />
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-500">Tidak ada data pemeriksaan</p>
                                            <p class="text-sm text-muted-foreground mt-0.5">
                                                {{ search || activeType ? 'Coba ubah kata kunci atau filter pencarian.' : 'Mulai dengan menambahkan data pemeriksaan baru.' }}
                                            </p>
                                        </div>
                                        <Button
                                            v-if="!search && !activeType"
                                            @click="router.get(route('pemeriksaan.create'))"
                                            variant="outline"
                                            size="sm"
                                            class="mt-1"
                                        >
                                            <Plus class="h-4 w-4 mr-1" />
                                            Input Pemeriksaan
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
                        Menampilkan <span class="font-semibold text-gray-700">{{ pemeriksaan.data.length }}</span>
                        dari <span class="font-semibold text-gray-700">{{ pemeriksaan.meta?.total }}</span> data
                    </p>
                    <div class="flex items-center gap-1">
                        <template v-for="link in pemeriksaan.meta?.links" :key="link.label">
                            <button
                                v-if="link.url"
                                @click="router.get(link.url, {}, { preserveState: true })"
                                v-html="link.label"
                                :class="[
                                    'inline-flex h-8 min-w-8 items-center justify-center rounded-lg px-2.5 text-sm transition-colors',
                                    link.active
                                        ? 'bg-blue-600 text-white font-semibold shadow-sm'
                                        : 'hover:bg-gray-100 text-gray-600'
                                ]"
                            />
                            <span
                                v-else
                                v-html="link.label"
                                class="inline-flex h-8 min-w-8 items-center justify-center px-2 text-sm text-gray-300 pointer-events-none"
                            />
                        </template>
                    </div>
                </div>

            </div>
        </div>

        <!-- Delete Confirm Dialog -->
        <AlertDialog :open="!!deleteTarget" @update:open="(v) => { if (!v) deleteTarget = null }">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Hapus Data Pemeriksaan?</AlertDialogTitle>
                    <AlertDialogDescription>
                        Data pemeriksaan ini akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel @click="deleteTarget = null">Batal</AlertDialogCancel>
                    <AlertDialogAction @click="deletePemeriksaan" class="bg-destructive hover:bg-destructive/90">Hapus</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AuthenticatedLayout>
</template>
