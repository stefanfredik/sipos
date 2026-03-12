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
import { ArrowLeft, Loader, Loader2, Save, User } from 'lucide-vue-next';

const props = defineProps<{
    kader: {
        id: string;
        user_id: string;
        posyandu_id: string;
        nama_kader: string;
        foto: string | null;
        alamat: string;
        no_telp: string;
        jenis_kelamin: string;
    };
    posyandu: Array<{ id: string; nama_posyandu: string }>;
}>();

const form = useForm({
    _method: 'PUT',
    posyandu_id: props.kader.posyandu_id,
    nama_kader: props.kader.nama_kader,
    foto_kader: null as File | null,
    alamat: props.kader.alamat,
    no_telp: props.kader.no_telp,
    jenis_kelamin: props.kader.jenis_kelamin,
});

const submit = () => {
    form.post(route('kader.update', props.kader.id), {
        forceFormData: true,
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
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kader</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Edit Data: {{ kader.nama_kader }}</CardTitle>
                        <CardDescription>Perbarui informasi profil kader.</CardDescription>
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <div class="flex justify-center mb-6">
                                <div class="relative h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-2">
                                    <img v-if="kader.foto && !form.foto_kader" :src="kader.foto" :alt="kader.nama_kader" class="h-full w-full object-cover" />
                                    <User v-else-if="!form.foto_kader" class="h-12 w-12 text-gray-400" />
                                    <span v-else class="text-xs text-center p-1">File Baru Terpilih</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                    <p v-if="form.errors.posyandu_id" class="text-sm text-destructive">{{ form.errors.posyandu_id }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="nama_kader">Nama Lengkap</Label>
                                    <Input
                                        id="nama_kader"
                                        v-model="form.nama_kader"
                                        required
                                        :class="{ 'border-destructive': form.errors.nama_kader }"
                                    />
                                    <p v-if="form.errors.nama_kader" class="text-sm text-destructive">{{ form.errors.nama_kader }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="no_telp">No. Telepon</Label>
                                    <Input
                                        id="no_telp"
                                        v-model="form.no_telp"
                                        required
                                        :class="{ 'border-destructive': form.errors.no_telp }"
                                    />
                                    <p v-if="form.errors.no_telp" class="text-sm text-destructive">{{ form.errors.no_telp }}</p>
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
                                    <p v-if="form.errors.jenis_kelamin" class="text-sm text-destructive">{{ form.errors.jenis_kelamin }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="foto_kader">Update Foto Profil (Opsional)</Label>
                                    <Input
                                        id="foto_kader"
                                        type="file"
                                        accept="image/*"
                                        @input="form.foto_kader = ($event.target as HTMLInputElement).files?.[0] || null"
                                        :class="{ 'border-destructive': form.errors.foto_kader }"
                                    />
                                    <p v-if="form.errors.foto_kader" class="text-sm text-destructive">{{ form.errors.foto_kader }}</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="alamat">Alamat Lengkap</Label>
                                <Textarea
                                    id="alamat"
                                    v-model="form.alamat"
                                    required
                                    :class="{ 'border-destructive': form.errors.alamat }"
                                />
                                <p v-if="form.errors.alamat" class="text-sm text-destructive">{{ form.errors.alamat }}</p>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-end gap-4 border-t px-6 py-4">
                            <Link :href="route('kader.index')">
                                <Button variant="ghost" type="button">Batal</Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
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
