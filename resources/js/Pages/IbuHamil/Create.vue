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
    nik: '',
    nama: '',
    foto: null as File | null,
    tgl_lahir: '',
    kehamilan_keberapa: 1,
    jarak_anak: null as number | null,
    usia_kehamilan: 0,
    no_telp: '',
    alamat: '',
    keterangan: '',
});

const submit = () => {
    form.post(route('ibu-hamil.store'), {
        forceFormData: true,
        onSuccess: () => {
            toast.success('Berhasil', 'Data ibu hamil berhasil ditambahkan.');
        },
        onError: () => {
            toast.error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        },
    });
};
</script>

<template>
    <Head title="Tambah Ibu Hamil" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('ibu-hamil.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <h2 class="text-xl leading-tight font-semibold text-gray-800">
                    Tambah Ibu Hamil
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Formulir Pendaftaran</CardTitle>
                        <CardDescription
                            >Lengkapi data ibu hamil di bawah
                            ini.</CardDescription
                        >
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="nik">NIK (16 Digit)</Label>
                                    <Input
                                        id="nik"
                                        v-model="form.nik"
                                        maxlength="16"
                                        required
                                        :class="{
                                            'border-destructive':
                                                form.errors.nik,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.nik"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.nik }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="nama">Nama Lengkap</Label>
                                    <Input
                                        id="nama"
                                        v-model="form.nama"
                                        required
                                        :class="{
                                            'border-destructive':
                                                form.errors.nama,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.nama"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.nama }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="tgl_lahir">Tanggal Lahir</Label>
                                    <Input
                                        id="tgl_lahir"
                                        type="date"
                                        v-model="form.tgl_lahir"
                                        required
                                        :class="{
                                            'border-destructive':
                                                form.errors.tgl_lahir,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.tgl_lahir"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.tgl_lahir }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="no_telp"
                                        >No. Telepon / WhatsApp</Label
                                    >
                                    <Input
                                        id="no_telp"
                                        v-model="form.no_telp"
                                        required
                                        :class="{
                                            'border-destructive':
                                                form.errors.no_telp,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.no_telp"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.no_telp }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="kehamilan_keberapa"
                                        >Kehamilan Ke-</Label
                                    >
                                    <Input
                                        id="kehamilan_keberapa"
                                        type="number"
                                        v-model="form.kehamilan_keberapa"
                                        min="1"
                                        required
                                        :class="{
                                            'border-destructive':
                                                form.errors.kehamilan_keberapa,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.kehamilan_keberapa"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.kehamilan_keberapa }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="usia_kehamilan"
                                        >Usia Kehamilan (Minggu)</Label
                                    >
                                    <Input
                                        id="usia_kehamilan"
                                        type="number"
                                        v-model="form.usia_kehamilan"
                                        min="0"
                                        max="42"
                                        required
                                        :class="{
                                            'border-destructive':
                                                form.errors.usia_kehamilan,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.usia_kehamilan"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.usia_kehamilan }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="jarak_anak"
                                        >Jarak Anak Sebelumnya (Tahun)</Label
                                    >
                                    <Input
                                        id="jarak_anak"
                                        type="number"
                                        v-model="form.jarak_anak"
                                        min="0"
                                        placeholder="Kosongkan jika anak pertama"
                                        :class="{
                                            'border-destructive':
                                                form.errors.jarak_anak,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.jarak_anak"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.jarak_anak }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="foto">Foto</Label>
                                    <Input
                                        id="foto"
                                        type="file"
                                        accept="image/*"
                                        @input="
                                            form.foto =
                                                (
                                                    $event.target as HTMLInputElement
                                                ).files?.[0] || null
                                        "
                                        :class="{
                                            'border-destructive':
                                                form.errors.foto,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.foto"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.foto }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="alamat">Alamat Lengkap</Label>
                                <Textarea
                                    id="alamat"
                                    v-model="form.alamat"
                                    required
                                    :class="{
                                        'border-destructive':
                                            form.errors.alamat,
                                    }"
                                />
                                <p
                                    v-if="form.errors.alamat"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.alamat }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="keterangan"
                                    >Keterangan Tambahan (Opsional)</Label
                                >
                                <Textarea
                                    id="keterangan"
                                    v-model="form.keterangan"
                                    placeholder="Contoh: Golongan darah, alergi, atau riwayat medis lainnya."
                                    :class="{
                                        'border-destructive':
                                            form.errors.keterangan,
                                    }"
                                />
                                <p
                                    v-if="form.errors.keterangan"
                                    class="text-sm text-destructive"
                                >
                                    {{ form.errors.keterangan }}
                                </p>
                            </div>
                        </CardContent>
                        <CardFooter
                            class="flex justify-end gap-4 border-t px-6 py-4"
                        >
                            <Link :href="route('ibu-hamil.index')">
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
                                Simpan Data
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
