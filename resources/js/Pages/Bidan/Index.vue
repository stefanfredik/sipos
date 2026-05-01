<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '@/components/ui/alert-dialog';
import { Label } from '@/components/ui/label';
import { ref, watch } from 'vue';
import { MoreHorizontal, Plus, Search, User, Eye, Edit, Trash2, Key, UserCheck } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

interface Bidan {
    id: string;
    nama: string;
    foto: string | null;
    no_telp: string;
    username: string;
    jenis_kelamin: string;
}

const props = defineProps<{
    bidan: { data: Bidan[]; links: any[]; meta: any };
    filters: { search?: string };
}>();

const toast = useToast();
const search = ref(props.filters?.search ?? '');
const deleteTarget = ref<string | null>(null);
const showPasswordModal = ref(false);
const selectedBidanId = ref<string | null>(null);
const selectedBidanNama = ref('');
const passwordForm = ref({ password: '', password_confirmation: '' });

let timer: ReturnType<typeof setTimeout> | null = null;
watch(search, () => {
    if (timer) clearTimeout(timer);
    timer = setTimeout(() => {
        router.get(route('bidan.index'), { search: search.value || undefined }, { preserveState: true, replace: true });
    }, 400);
});

function confirmDelete(id: string) { deleteTarget.value = id; }

function deleteBidan() {
    const id = deleteTarget.value;
    if (!id) return;
    router.delete(route('bidan.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            deleteTarget.value = null;
        },
        onError: () => {
            deleteTarget.value = null;
        }
    });
}

function openPasswordModal(id: string, nama: string) {
    selectedBidanId.value = id;
    selectedBidanNama.value = nama;
    passwordForm.value = { password: '', password_confirmation: '' };
    showPasswordModal.value = true;
}

function submitChangePassword() {
    if (!passwordForm.value.password || !passwordForm.value.password_confirmation) {
        toast.error('Error', 'Semua field harus diisi.'); return;
    }
    if (passwordForm.value.password !== passwordForm.value.password_confirmation) {
        toast.error('Error', 'Konfirmasi password tidak cocok.'); return;
    }
    if (passwordForm.value.password.length < 8) {
        toast.error('Error', 'Password minimal 8 karakter.'); return;
    }
    router.post(route('bidan.change-password', { bidan: selectedBidanId.value || '' }), {
        password: passwordForm.value.password,
        password_confirmation: passwordForm.value.password_confirmation,
    }, {
        onSuccess: () => {
            toast.success('Berhasil', 'Password bidan berhasil diubah.');
            showPasswordModal.value = false;
        },
        onError: () => toast.error('Gagal', 'Terjadi kesalahan saat mengubah password.'),
    });
}
</script>

<template>
    <Head title="Data Bidan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Data Bidan</h2>
                    <p class="text-sm text-muted-foreground mt-0.5">Kelola tenaga bidan Posyandu Desa Belumbang</p>
                </div>
                <Button @click="router.get(route('bidan.create'))"
                    class="bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2 rounded-lg shadow-sm shadow-blue-200">
                    <Plus class="h-4 w-4" /> Tambah Bidan
                </Button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Stats -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <CardContent class="p-4">
                        <div class="flex items-center gap-4">
                            <div class="p-2.5 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shrink-0">
                                <UserCheck class="h-5 w-5" />
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Total Bidan</p>
                                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ bidan.meta?.total ?? bidan.data.length }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Search -->
                <div class="relative max-w-sm">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" />
                    <Input v-model="search" placeholder="Cari nama atau username..." class="pl-9" />
                </div>

                <!-- Table -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-50/70 hover:bg-gray-50/70">
                                <TableHead class="w-12 text-xs font-bold uppercase tracking-wider text-gray-500 pl-5">Foto</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Nama / Username</TableHead>
                                <TableHead class="text-xs font-bold uppercase tracking-wider text-gray-500">Kontak</TableHead>
                                <TableHead class="text-center text-xs font-bold uppercase tracking-wider text-gray-500">JK</TableHead>
                                <TableHead class="w-12" />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="item in bidan.data" :key="item.id"
                                class="hover:bg-gray-50/50 cursor-pointer"
                                @click="router.get(route('bidan.show', item.id))">
                                <TableCell class="pl-5">
                                    <div class="h-9 w-9 rounded-full overflow-hidden bg-teal-50 flex items-center justify-center border border-teal-100">
                                        <img v-if="item.foto" :src="item.foto" :alt="item.nama" class="h-full w-full object-cover" />
                                        <User v-else class="h-4 w-4 text-teal-400" />
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <p class="font-semibold text-gray-800 text-sm">{{ item.nama }}</p>
                                    <p class="font-mono text-xs text-muted-foreground">@{{ item.username }}</p>
                                </TableCell>
                                <TableCell class="text-sm text-muted-foreground">{{ item.no_telp }}</TableCell>
                                <TableCell class="text-center">
                                    <Badge variant="outline" :class="item.jenis_kelamin === 'L'
                                        ? 'bg-blue-50 text-blue-700 border-blue-200 text-xs font-semibold'
                                        : 'bg-pink-50 text-pink-700 border-pink-200 text-xs font-semibold'">
                                        {{ item.jenis_kelamin === 'L' ? 'L' : 'P' }}
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
                                            <DropdownMenuItem @click="router.get(route('bidan.show', item.id))" class="flex items-center gap-2">
                                                <Eye class="h-4 w-4 text-gray-400" /> Lihat Detail
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="router.get(route('bidan.edit', item.id))" class="flex items-center gap-2">
                                                <Edit class="h-4 w-4 text-gray-400" /> Edit Data
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="openPasswordModal(item.id, item.nama)" class="flex items-center gap-2">
                                                <Key class="h-4 w-4 text-gray-400" /> Ganti Password
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem class="text-destructive flex items-center gap-2 focus:text-destructive" @click="confirmDelete(item.id)">
                                                <Trash2 class="h-4 w-4" /> Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="bidan.data.length === 0">
                                <TableCell colspan="5" class="py-16">
                                    <div class="flex flex-col items-center gap-3 text-center">
                                        <div class="p-4 rounded-full bg-gray-100">
                                            <UserCheck class="h-8 w-8 text-gray-300" />
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-500">Tidak ada data bidan</p>
                                            <p class="text-sm text-muted-foreground mt-0.5">
                                                {{ search ? 'Coba ubah kata kunci pencarian.' : 'Mulai dengan menambahkan bidan baru.' }}
                                            </p>
                                        </div>
                                        <Button v-if="!search" @click="router.get(route('bidan.create'))" variant="outline" size="sm">
                                            <Plus class="h-4 w-4 mr-1" /> Tambah Bidan
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
                        Menampilkan <span class="font-semibold text-gray-700">{{ bidan.data.length }}</span>
                        dari <span class="font-semibold text-gray-700">{{ bidan.meta?.total }}</span> data
                    </p>
                    <div class="flex items-center gap-1">
                        <template v-for="link in bidan.meta?.links" :key="link.label">
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

    <!-- Ganti Password Modal -->
    <Dialog v-model:open="showPasswordModal">
        <DialogContent class="max-w-sm">
            <DialogHeader>
                <DialogTitle>Ganti Password Bidan</DialogTitle>
                <DialogDescription>
                    Ubah password untuk: <span class="font-semibold text-gray-700">{{ selectedBidanNama }}</span>
                </DialogDescription>
            </DialogHeader>
            <div class="space-y-4 py-2">
                <div class="space-y-1.5">
                    <Label for="bidan_password" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Password Baru</Label>
                    <Input id="bidan_password" v-model="passwordForm.password" type="password" placeholder="Minimal 8 karakter" />
                </div>
                <div class="space-y-1.5">
                    <Label for="bidan_password_confirmation" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Konfirmasi Password</Label>
                    <Input id="bidan_password_confirmation" v-model="passwordForm.password_confirmation" type="password" placeholder="Ulangi password" />
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <Button variant="outline" @click="showPasswordModal = false">Batal</Button>
                <Button @click="submitChangePassword" class="bg-blue-600 hover:bg-blue-700 text-white">Ubah Password</Button>
            </div>
        </DialogContent>
    </Dialog>

    <AlertDialog :open="!!deleteTarget" @update:open="(v) => { if (!v) deleteTarget = null }">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Hapus Data Bidan</AlertDialogTitle>
                <AlertDialogDescription>Tindakan ini tidak dapat dibatalkan. Data bidan akan dihapus secara permanen dari sistem.</AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Batal</AlertDialogCancel>
                <Button @click="deleteBidan" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">Hapus</Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
