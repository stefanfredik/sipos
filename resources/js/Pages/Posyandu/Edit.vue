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
import { ArrowLeft, Loader, Loader2, Save } from 'lucide-vue-next';

const props = defineProps<{
    posyandu: {
        id: string;
        nama_posyandu: string;
        lokasi: string;
        deskripsi: string | null;
        is_active: boolean;
    };
}>();

const form = useForm({
    nama_posyandu: props.posyandu?.nama_posyandu ?? '',
    lokasi: props.posyandu?.lokasi ?? '',
    deskripsi: props.posyandu?.deskripsi ?? '',
    is_active: props.posyandu?.is_active ? 'true' : 'false',
});

const submit = () => {
    form.put(route('posyandu.update', props.posyandu.id), {
        preserveScroll: true,
        data: {
            nama_posyandu: form.nama_posyandu,
            lokasi: form.lokasi,
            deskripsi: form.deskripsi,
            is_active: form.is_active === 'true',
        },
    });
};
</script>

<template>
    <Head title="Edit Posyandu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('posyandu.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Posyandu</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <Card v-if="props.posyandu?.id">
                    <CardHeader>
                        <CardTitle>Edit Unit: {{ props.posyandu.nama_posyandu }}</CardTitle>
                        <CardDescription>Perbarui informasi unit posyandu di bawah ini.</CardDescription>
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <div class="space-y-2">
                                <Label for="nama_posyandu">Nama Posyandu</Label>
                                <Input
                                    id="nama_posyandu"
                                    v-model="form.nama_posyandu"
                                    required
                                    :class="{ 'border-destructive': form.errors.nama_posyandu }"
                                />
                                <p v-if="form.errors.nama_posyandu" class="text-sm text-destructive">{{ form.errors.nama_posyandu }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="lokasi">Lokasi / Alamat</Label>
                                <Input
                                    id="lokasi"
                                    v-model="form.lokasi"
                                    required
                                    :class="{ 'border-destructive': form.errors.lokasi }"
                                />
                                <p v-if="form.errors.lokasi" class="text-sm text-destructive">{{ form.errors.lokasi }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="is_active">Status Aktif</Label>
                                <Select v-model="form.is_active" @update:modelValue="(v) => form.is_active = v === 'true'">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih Status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="true">Aktif</SelectItem>
                                        <SelectItem value="false">Tidak Aktif</SelectItem>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.is_active" class="text-sm text-destructive">{{ form.errors.is_active }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="deskripsi">Keterangan (Opsional)</Label>
                                <Textarea
                                    id="deskripsi"
                                    v-model="form.deskripsi"
                                    :class="{ 'border-destructive': form.errors.deskripsi }"
                                />
                                <p v-if="form.errors.deskripsi" class="text-sm text-destructive">{{ form.errors.deskripsi }}</p>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-end gap-4 border-t px-6 py-4">
                            <Link :href="route('posyandu.index')">
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
