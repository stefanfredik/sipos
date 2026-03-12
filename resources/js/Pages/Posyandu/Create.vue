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
import { ArrowLeft, Loader2, Save } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

const toast = useToast();

const form = useForm({
    nama_posyandu: '',
    lokasi: '',
    deskripsi: '',
});

const submit = () => {
    form.post(route('posyandu.store'), {
        onSuccess: () => {
            toast.success('Berhasil', 'Data posyandu berhasil ditambahkan.');
        },
        onError: () => {
            toast.error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        },
    });
};
</script>

<template>
    <Head title="Tambah Posyandu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('posyandu.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <h2 class="text-xl leading-tight font-semibold text-gray-800">
                    Tambah Posyandu
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Data Unit Posyandu</CardTitle>
                        <CardDescription
                            >Daftarkan unit posyandu baru di lingkungan
                            Anda.</CardDescription
                        >
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <div class="space-y-2">
                                <Label for="nama_posyandu">Nama Posyandu</Label>
                                <Input
                                    id="nama_posyandu"
                                    v-model="form.nama_posyandu"
                                    placeholder="Contoh: Posyandu Melati I"
                                    required
                                    :class="{
                                        'border-destructive':
                                            form.errors.nama_posyandu,
                                    }"
                                />
                                <p
                                    v-if="form.errors.nama_posyandu"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.nama_posyandu }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="lokasi">Lokasi / Alamat</Label>
                                <Input
                                    id="lokasi"
                                    v-model="form.lokasi"
                                    placeholder="Contoh: RW 05 Desa Makmur"
                                    required
                                    :class="{
                                        'border-destructive':
                                            form.errors.lokasi,
                                    }"
                                />
                                <p
                                    v-if="form.errors.lokasi"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.lokasi }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="deskripsi"
                                    >Keterangan (Opsional)</Label
                                >
                                <Textarea
                                    id="deskripsi"
                                    v-model="form.deskripsi"
                                    placeholder="Informasi tambahan mengenai unit posyandu ini."
                                    :class="{
                                        'border-destructive':
                                            form.errors.deskripsi,
                                    }"
                                />
                                <p
                                    v-if="form.errors.deskripsi"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.deskripsi }}
                                </p>
                            </div>
                        </CardContent>
                        <CardFooter
                            class="flex justify-end gap-4 border-t px-6 py-4"
                        >
                            <Link :href="route('posyandu.index')">
                                <Button variant="ghost" type="button"
                                    >Batal</Button
                                >
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                <Loader2
                                    v-if="form.processing"
                                    class="mr-2 h-4 w-4 animate-spin"
                                />
                                <Save v-else class="mr-2 h-4 w-4" />
                                Simpan Posyandu
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
