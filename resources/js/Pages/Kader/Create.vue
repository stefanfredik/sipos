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
import SearchableSelect from '@/components/ui/SearchableSelect.vue';
import { ArrowLeft, Loader2, Save } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

const props = defineProps<{
    posyandu: Array<{ id: string; nama_posyandu: string }>;
}>();

const toast = useToast();

const posyanduOptions = props.posyandu.map((p) => ({
    id: p.id,
    label: p.nama_posyandu,
}));

const form = useForm({
    username: '',
    password: '',
    password_confirmation: '',
    posyandu_id: '',
    nama_kader: '',
    foto_kader: null as File | null,
    alamat: '',
    no_telp: '',
    jenis_kelamin: 'L',
});

const submit = () => {
    form.post(route('kader.store'), {
        forceFormData: true,
        onSuccess: () => {
            toast.success('Berhasil', 'Data kader berhasil ditambahkan.');
        },
        onError: () => {
            toast.error('Gagal', 'Terjadi kesalahan saat menyimpan data.');
        },
    });
};
</script>

<template>
    <Head title="Tambah Kader" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('kader.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <h2 class="text-xl leading-tight font-semibold text-gray-800">
                    Tambah Kader
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Pendaftaran Kader</CardTitle>
                        <CardDescription
                            >Buat akun user dan data kader baru beserta unit
                            posyandu.</CardDescription
                        >
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <!-- Account Information -->
                            <div>
                                <h3 class="mb-4 font-semibold text-sm">Informasi Akun</h3>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="username">Username</Label>
                                        <Input
                                            id="username"
                                            v-model="form.username"
                                            required
                                            :class="{
                                                'border-destructive':
                                                    form.errors.username,
                                            }"
                                        />
                                        <p
                                            v-if="form.errors.username"
                                            class="text-sm text-destructive"
                                        >
                                            {{ form.errors.username }}
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="password">Password</Label>
                                        <Input
                                            id="password"
                                            v-model="form.password"
                                            type="password"
                                            required
                                            :class="{
                                                'border-destructive':
                                                    form.errors.password,
                                            }"
                                        />
                                        <p
                                            v-if="form.errors.password"
                                            class="text-sm text-destructive"
                                        >
                                            {{ form.errors.password }}
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="password_confirmation">Konfirmasi Password</Label>
                                        <Input
                                            id="password_confirmation"
                                            v-model="form.password_confirmation"
                                            type="password"
                                            required
                                            :class="{
                                                'border-destructive':
                                                    form.errors.password_confirmation,
                                            }"
                                        />
                                        <p
                                            v-if="form.errors.password_confirmation"
                                            class="text-sm text-destructive"
                                        >
                                            {{ form.errors.password_confirmation }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Information -->
                            <div>
                                <h3 class="mb-4 font-semibold text-sm">Informasi Profil</h3>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="posyandu_id"
                                            >Unit Posyandu</Label
                                        >
                                        <Select v-model="form.posyandu_id">
                                            <SelectTrigger
                                                :class="{
                                                    'border-destructive':
                                                        form.errors.posyandu_id,
                                                }"
                                            >
                                                <SelectValue
                                                    placeholder="Pilih Posyandu"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="p in posyandu"
                                                    :key="p.id"
                                                    :value="p.id"
                                                >
                                                    {{ p.nama_posyandu }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <p
                                            v-if="form.errors.posyandu_id"
                                            class="text-sm text-destructive"
                                        >
                                            {{ form.errors.posyandu_id }}
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="nama_kader"
                                            >Nama Lengkap</Label
                                        >
                                        <Input
                                            id="nama_kader"
                                            v-model="form.nama_kader"
                                            required
                                            :class="{
                                                'border-destructive':
                                                    form.errors.nama_kader,
                                            }"
                                        />
                                        <p
                                            v-if="form.errors.nama_kader"
                                            class="text-sm text-destructive"
                                        >
                                            {{ form.errors.nama_kader }}
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
                                        <Label for="foto_kader">Foto Profil</Label>
                                        <Input
                                            id="foto_kader"
                                            type="file"
                                            accept="image/*"
                                            @change="
                                                form.foto_kader =
                                                    (
                                                        $event.target as HTMLInputElement
                                                    ).files?.[0] || null
                                            "
                                            :class="{
                                                'border-destructive':
                                                    form.errors.foto_kader,
                                            }"
                                        />
                                        <p
                                            v-if="form.errors.foto_kader"
                                            class="text-sm text-destructive"
                                        >
                                            {{ form.errors.foto_kader }}
                                        </p>
                                    </div>
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
                            <Link :href="route('kader.index')">
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
                                Simpan Kader
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
