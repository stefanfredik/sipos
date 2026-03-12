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
    bidan: {
        id: string;
        user_id: string;
        nama_bidan: string;
        foto: string | null;
        alamat: string;
        no_telp: string;
        jenis_kelamin: string;
    };
}>();

const form = useForm({
    _method: 'PUT',
    nama_bidan: props.bidan.nama_bidan,
    foto_bidan: null as File | null,
    alamat: props.bidan.alamat,
    no_telp: props.bidan.no_telp,
    jenis_kelamin: props.bidan.jenis_kelamin,
});

const submit = () => {
    form.post(route('bidan.update', props.bidan.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Bidan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('bidan.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Bidan</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Edit Data: {{ bidan.nama_bidan }}</CardTitle>
                        <CardDescription>Perbarui informasi profil bidan.</CardDescription>
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <div class="flex justify-center mb-6">
                                <div class="relative h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-2">
                                    <img v-if="bidan.foto && !form.foto_bidan" :src="bidan.foto" :alt="bidan.nama_bidan" class="h-full w-full object-cover" />
                                    <User v-else-if="!form.foto_bidan" class="h-12 w-12 text-gray-400" />
                                    <span v-else class="text-xs text-center p-1">File Baru Terpilih</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <Label for="nama_bidan">Nama Lengkap</Label>
                                    <Input
                                        id="nama_bidan"
                                        v-model="form.nama_bidan"
                                        required
                                        :class="{ 'border-destructive': form.errors.nama_bidan }"
                                    />
                                    <p v-if="form.errors.nama_bidan" class="text-sm text-destructive">{{ form.errors.nama_bidan }}</p>
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
                                    <Label for="foto_bidan">Update Foto Profil (Opsional)</Label>
                                    <Input
                                        id="foto_bidan"
                                        type="file"
                                        accept="image/*"
                                        @input="form.foto_bidan = ($event.target as HTMLInputElement).files?.[0] || null"
                                        :class="{ 'border-destructive': form.errors.foto_bidan }"
                                    />
                                    <p v-if="form.errors.foto_bidan" class="text-sm text-destructive">{{ form.errors.foto_bidan }}</p>
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
                            <Link :href="route('bidan.index')">
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
