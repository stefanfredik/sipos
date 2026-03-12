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
    ibu_hamil: {
        data: {
            id: string;
            nik: string;
            nama: string;
            foto: string | null;
            tgl_lahir: string;
            kehamilan_keberapa: number;
            jarak_anak: number | null;
            usia_kehamilan: number;
            no_telp: string;
            alamat: string;
            keterangan: string | null;
            is_active: boolean;
        };
    };
}>();

const data = props.ibu_hamil.data;
const toast = useToast();

const form = useForm({
    _method: 'PUT',
    nik: data.nik,
    nama: data.nama,
    foto: null as File | null,
    tgl_lahir: data.tgl_lahir,
    kehamilan_keberapa: data.kehamilan_keberapa,
    jarak_anak: data.jarak_anak,
    usia_kehamilan: data.usia_kehamilan,
    no_telp: data.no_telp,
    alamat: data.alamat,
    keterangan: data.keterangan || '',
    is_active: data.is_active,
});

const submit = () => {
    form.post(route('ibu-hamil.update', data.id), {
        forceFormData: true,
        onSuccess: () => {
            toast.success('Berhasil', 'Data ibu hamil berhasil diperbarui.');
        },
        onError: () => {
            toast.error('Gagal', 'Terjadi kesalahan saat memperbarui data.');
        },
    });
};
</script>

<template>
    <Head title="Edit Ibu Hamil" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('ibu-hamil.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <h2 class="text-xl leading-tight font-semibold text-gray-800">
                    Edit Ibu Hamil
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Edit Data: {{ data.nama }}</CardTitle>
                        <CardDescription
                            >Perbarui informasi ibu hamil di bawah
                            ini.</CardDescription
                        >
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <div class="mb-6 flex justify-center">
                                <div
                                    class="relative flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border-2 bg-gray-100"
                                >
                                    <img
                                        v-if="data.foto && !form.foto"
                                        :src="data.foto"
                                        :alt="data.nama"
                                        class="h-full w-full object-cover"
                                    />
                                    <User
                                        v-else-if="!form.foto"
                                        class="h-12 w-12 text-gray-400"
                                    />
                                    <span v-else class="p-1 text-center text-xs"
                                        >File Baru Terpilih</span
                                    >
                                </div>
                            </div>

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
                                    <Label for="is_active"
                                        >Status Peserta</Label
                                    >
                                    <Select
                                        v-model="form.is_active"
                                        @update:modelValue="
                                            (v) =>
                                                (form.is_active = v === 'true')
                                        "
                                    >
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Pilih Status"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="true"
                                                >Aktif</SelectItem
                                            >
                                            <SelectItem value="false"
                                                >Tidak Aktif</SelectItem
                                            >
                                        </SelectContent>
                                    </Select>
                                    <p
                                        v-if="form.errors.is_active"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.is_active }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="foto"
                                        >Foto Baru (Opsional)</Label
                                    >
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
                                Simpan Perubahan
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
