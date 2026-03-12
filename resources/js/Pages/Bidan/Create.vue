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
import { ArrowLeft, Loader2, Save } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

defineProps<{
    available_users: Array<{ id: string; nama_user: string; email: string }>;
}>();

const toast = useToast();

const form = useForm({
    user_id: '',
    nama_bidan: '',
    foto_bidan: null as File | null,
    alamat: '',
    no_telp: '',
    jenis_kelamin: 'L',
});

const submit = () => {
    form.post(route('bidan.store'), {
        forceFormData: true,
        onSuccess: () => {
            toast.success('Berhasil', 'Data bidan berhasil ditambahkan.');
        },
        onError: () => {
            toast.error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        },
    });
};

const onUserChange = (userId: string, users: any[]) => {
    const selected = users.find((u) => u.id === userId);
    if (selected) {
        form.nama_bidan = selected.nama_user;
    }
};
</script>

<template>
    <Head title="Tambah Bidan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('bidan.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <h2 class="text-xl leading-tight font-semibold text-gray-800">
                    Tambah Bidan
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Pendaftaran Bidan</CardTitle>
                        <CardDescription
                            >Hubungkan akun user dengan data profil
                            bidan.</CardDescription
                        >
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="user_id">Pilih Akun User</Label>
                                    <Select
                                        v-model="form.user_id"
                                        @update:modelValue="
                                            (v) =>
                                                onUserChange(v, available_users)
                                        "
                                    >
                                        <SelectTrigger
                                            :class="{
                                                'border-destructive':
                                                    form.errors.user_id,
                                            }"
                                        >
                                            <SelectValue
                                                placeholder="Pilih Akun"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="u in available_users"
                                                :key="u.id"
                                                :value="u.id"
                                            >
                                                {{ u.nama_user }} ({{
                                                    u.email
                                                }})
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <p
                                        v-if="form.errors.user_id"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.user_id }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="nama_bidan"
                                        >Nama Lengkap (Display)</Label
                                    >
                                    <Input
                                        id="nama_bidan"
                                        v-model="form.nama_bidan"
                                        required
                                        :class="{
                                            'border-destructive':
                                                form.errors.nama_bidan,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.nama_bidan"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.nama_bidan }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="no_telp">No. Telepon</Label>
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
                                    <Label for="jenis_kelamin"
                                        >Jenis Kelamin</Label
                                    >
                                    <Select v-model="form.jenis_kelamin">
                                        <SelectTrigger
                                            :class="{
                                                'border-destructive':
                                                    form.errors.jenis_kelamin,
                                            }"
                                        >
                                            <SelectValue
                                                placeholder="Pilih Jenis Kelamin"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="L"
                                                >Laki-laki</SelectItem
                                            >
                                            <SelectItem value="P"
                                                >Perempuan</SelectItem
                                            >
                                        </SelectContent>
                                    </Select>
                                    <p
                                        v-if="form.errors.jenis_kelamin"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.jenis_kelamin }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="foto_bidan">Foto Profil</Label>
                                    <Input
                                        id="foto_bidan"
                                        type="file"
                                        accept="image/*"
                                        @input="
                                            form.foto_bidan =
                                                (
                                                    $event.target as HTMLInputElement
                                                ).files?.[0] || null
                                        "
                                        :class="{
                                            'border-destructive':
                                                form.errors.foto_bidan,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.foto_bidan"
                                        class="text-sm text-destructive"
                                    >
                                        {{ form.errors.foto_bidan }}
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
                        </CardContent>
                        <CardFooter
                            class="flex justify-end gap-4 border-t px-6 py-4"
                        >
                            <Link :href="route('bidan.index')">
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
                                Simpan Bidan
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
