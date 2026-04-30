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
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { MoreHorizontal, Plus, Search, User, Key } from 'lucide-vue-next';
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
const search = ref(props.filters.search || '');
const showPasswordModal = ref(false);
const selectedBidanId = ref<string | null>(null);
const selectedBidanNama = ref('');
const passwordForm = ref({
    password: '',
    password_confirmation: '',
});

watch(
    search,
    debounce((value: string) => {
        router.get(
            route('bidan.index'),
            { search: value },
            { preserveState: true, replace: true },
        );
    }, 500),
);

function deleteBidan(id: string) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('bidan.destroy', id), {
            onSuccess: () => {
                toast.success('Berhasil', 'Data bidan berhasil dihapus.');
            },
            onError: () => {
                toast.error('Gagal', 'Terjadi kesalahan saat menghapus data.');
            },
        });
    }
}

function openChangePasswordModal(id: string, nama: string) {
    selectedBidanId.value = id;
    selectedBidanNama.value = nama;
    passwordForm.value = { password: '', password_confirmation: '' };
    showPasswordModal.value = true;
}

function submitChangePassword() {
    if (!passwordForm.value.password || !passwordForm.value.password_confirmation) {
        toast.error('Error', 'Semua field harus diisi.');
        return;
    }
    if (passwordForm.value.password !== passwordForm.value.password_confirmation) {
        toast.error('Error', 'Konfirmasi password tidak cocok.');
        return;
    }
    if (passwordForm.value.password.length < 8) {
        toast.error('Error', 'Password minimal 8 karakter.');
        return;
    }

    router.post(
        route('bidan.change-password', { bidan: selectedBidanId.value || '' }),
        {
            password: passwordForm.value.password,
            password_confirmation: passwordForm.value.password_confirmation,
        },
        {
            onSuccess: () => {
                toast.success('Berhasil', 'Password bidan berhasil diubah.');
                showPasswordModal.value = false;
                passwordForm.value = { password: '', password_confirmation: '' };
            },
            onError: () => {
                toast.error('Gagal', 'Terjadi kesalahan saat mengubah password.');
            },
        }
    );
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
                <div class="relative max-w-sm flex-1">
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                    />
                    <Input
                        v-model="search"
                        placeholder="Cari nama atau username..."
                        class="pl-9"
                    />
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
                                <div
                                    class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-md bg-muted"
                                >
                                    <img
                                        v-if="item.foto"
                                        :src="item.foto"
                                        :alt="item.nama"
                                        class="h-full w-full object-cover"
                                    />
                                    <User
                                        v-else
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                </div>
                            </TableCell>
                            <TableCell>
                                <div>
                                    <p class="font-medium">{{ item.nama }}</p>
                                    <p
                                        class="font-mono text-xs text-muted-foreground"
                                    >
                                        @{{ item.username }}
                                    </p>
                                </div>
                            </TableCell>
                            <TableCell class="text-muted-foreground">{{
                                item.no_telp
                            }}</TableCell>
                            <TableCell class="text-center">
                                <Badge variant="outline">{{
                                    item.jenis_kelamin === 'L' ? 'L' : 'P'
                                }}</Badge>
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
                                                        'bidan.show',
                                                        item.id,
                                                    ),
                                                )
                                            "
                                            >Lihat Detail</DropdownMenuItem
                                        >
                                        <DropdownMenuItem
                                            @click="
                                                router.get(
                                                    route(
                                                        'bidan.edit',
                                                        item.id,
                                                    ),
                                                )
                                            "
                                            >Edit Data</DropdownMenuItem
                                        >
                                        <DropdownMenuItem
                                            @click="openChangePasswordModal(item.id, item.nama)"
                                        >
                                            <Key class="mr-2 h-4 w-4" />
                                            Ganti Password
                                        </DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem
                                            class="text-destructive"
                                            @click="deleteBidan(item.id)"
                                            >Hapus</DropdownMenuItem
                                        >
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="bidan.data.length === 0">
                            <TableCell
                                colspan="5"
                                class="h-24 text-center text-muted-foreground"
                            >
                                Tidak ada data bidan
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <div class="flex items-center justify-between text-sm">
                <p class="text-muted-foreground">
                    Menampilkan {{ bidan.data.length }} dari
                    {{ bidan.meta?.total }} data
                </p>
                <div class="flex items-center gap-1">
                    <Link
                        v-for="link in bidan.meta?.links"
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

    <!-- Change Password Modal -->
    <Dialog v-model:open="showPasswordModal">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Ganti Password Bidan</DialogTitle>
                <DialogDescription>
                    Ubah password untuk: <span class="font-medium">{{ selectedBidanNama }}</span>
                </DialogDescription>
            </DialogHeader>
            <div class="space-y-4">
                <div class="space-y-2">
                    <Label for="password">Password Baru</Label>
                    <Input
                        id="password"
                        v-model="passwordForm.password"
                        type="password"
                        placeholder="Minimal 8 karakter"
                    />
                </div>
                <div class="space-y-2">
                    <Label for="password_confirmation">Konfirmasi Password</Label>
                    <Input
                        id="password_confirmation"
                        v-model="passwordForm.password_confirmation"
                        type="password"
                        placeholder="Ulangi password"
                    />
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <Button
                    variant="outline"
                    @click="showPasswordModal = false"
                >
                    Batal
                </Button>
                <Button
                    @click="submitChangePassword"
                >
                    Ubah Password
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
