<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { ArrowLeft, Loader2, Save, User } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

const props = defineProps<{
    kader: {
        id: string;
        user_id: string;
        posyandu_id: string;
        nama: string;
        foto: string | null;
        alamat: string;
        no_telp: string;
        jenis_kelamin: string;
        username: string;
    };
    posyandu: Array<{ id: string; nama_posyandu: string }>;
}>();

const toast = useToast();

const form = useForm({
    _method: 'PUT',
    username: props.kader.username,
    posyandu_id: props.kader.posyandu_id,
    nama_kader: props.kader.nama,
    foto_kader: null as File | null,
    alamat: props.kader.alamat,
    no_telp: props.kader.no_telp,
    jenis_kelamin: props.kader.jenis_kelamin,
});

const submit = () => {
    form.post(route('kader.update', props.kader.id), {
        forceFormData: true,
        onSuccess: () => {
            toast.success('Berhasil', 'Data kader berhasil diperbarui.');
        },
        onError: () => {
            toast.error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        },
    });
};
</script>

<template>
    <Head title="Edit Kader" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('kader.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <h2 class="text-lg font-semibold">Edit Kader: {{ kader.nama }}</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <!-- Profile Picture Card -->
                    <Card class="mb-6">
                        <CardHeader>
                            <CardTitle>Foto Profil</CardTitle>
                        </CardHeader>
                        <CardContent class="flex flex-col items-center gap-6">
                            <div class="h-32 w-32 rounded-full bg-muted flex items-center justify-center overflow-hidden border-4">
                                <img
                                    v-if="kader.foto && !form.foto_kader"
                                    :src="kader.foto"
                                    :alt="kader.nama"
                                    class="h-full w-full object-cover"
                                />
                                <User v-else-if="!form.foto_kader" class="h-16 w-16 text-muted-foreground" />
                                <span v-else class="text-xs text-center p-2 text-muted-foreground">File Baru</span>
                            </div>
                            <div class="w-full">
                                <Label for="foto_kader" class="text-sm">Update Foto Profil</Label>
                                <Input
                                    id="foto_kader"
                                    type="file"
                                    accept="image/*"
                                    @input="form.foto_kader = ($event.target as HTMLInputElement).files?.[0] || null"
                                    :class="{ 'border-destructive': form.errors.foto_kader }"
                                    class="mt-2"
                                />
                                <p v-if="form.errors.foto_kader" class="text-sm text-destructive mt-1">
                                    {{ form.errors.foto_kader }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Account Information Card -->
                    <Card class="mb-6">
                        <CardHeader>
                            <CardTitle>Informasi Akun</CardTitle>
                            <CardDescription>Ubah username untuk login</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="space-y-2">
                                <Label for="username">Username</Label>
                                <Input
                                    id="username"
                                    v-model="form.username"
                                    :class="{ 'border-destructive': form.errors.username }"
                                />
                                <p v-if="form.errors.username" class="text-sm text-destructive">
                                    {{ form.errors.username }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Profile Information Card -->
                    <Card class="mb-6">
                        <CardHeader>
                            <CardTitle>Informasi Profil</CardTitle>
                            <CardDescription>Data pribadi kader</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="posyandu_id">Unit Posyandu</Label>
                                    <Select v-model="form.posyandu_id">
                                        <SelectTrigger :class="{ 'border-destructive': form.errors.posyandu_id }">
                                            <SelectValue placeholder="Pilih Posyandu" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="p in posyandu" :key="p.id" :value="p.id">
                                                {{ p.nama_posyandu }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <p v-if="form.errors.posyandu_id" class="text-sm text-destructive">
                                        {{ form.errors.posyandu_id }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="nama_kader">Nama Lengkap</Label>
                                    <Input
                                        id="nama_kader"
                                        v-model="form.nama_kader"
                                        :class="{ 'border-destructive': form.errors.nama_kader }"
                                    />
                                    <p v-if="form.errors.nama_kader" class="text-sm text-destructive">
                                        {{ form.errors.nama_kader }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="jenis_kelamin">Jenis Kelamin</Label>
                                    <Select v-model="form.jenis_kelamin">
                                        <SelectTrigger :class="{ 'border-destructive': form.errors.jenis_kelamin }">
                                            <SelectValue placeholder="Pilih Jenis Kelamin" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="L">Laki-laki</SelectItem>
                                            <SelectItem value="P">Perempuan</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <p v-if="form.errors.jenis_kelamin" class="text-sm text-destructive">
                                        {{ form.errors.jenis_kelamin }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Contact Information Card -->
                    <Card class="mb-6">
                        <CardHeader>
                            <CardTitle>Informasi Kontak</CardTitle>
                            <CardDescription>Nomor telepon dan alamat</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="no_telp">No. Telepon</Label>
                                    <Input
                                        id="no_telp"
                                        v-model="form.no_telp"
                                        :class="{ 'border-destructive': form.errors.no_telp }"
                                    />
                                    <p v-if="form.errors.no_telp" class="text-sm text-destructive">
                                        {{ form.errors.no_telp }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="alamat">Alamat Lengkap</Label>
                                <Textarea
                                    id="alamat"
                                    v-model="form.alamat"
                                    rows="4"
                                    :class="{ 'border-destructive': form.errors.alamat }"
                                />
                                <p v-if="form.errors.alamat" class="text-sm text-destructive">
                                    {{ form.errors.alamat }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4">
                        <Link :href="route('kader.index')">
                            <Button variant="ghost" type="button">Batal</Button>
                        </Link>
                        <Button type="submit" :disabled="form.processing">
                            <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                            <Save v-else class="mr-2 h-4 w-4" />
                            Simpan Perubahan
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
