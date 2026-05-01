<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ArrowLeft, Save, User, Phone, MapPin, Lock, ChevronRight, AtSign } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

interface BidanEdit {
    id: string;
    user_id: string;
    nama: string;
    foto: string | null;
    alamat: string;
    no_telp: string;
    jenis_kelamin: string;
    username: string;
}

const props = defineProps<{ bidan: BidanEdit }>();

const toast = useToast();

const form = useForm({
    _method: 'PUT',
    username: props.bidan.username,
    nama_bidan: props.bidan.nama,
    foto_bidan: null as File | null,
    alamat: props.bidan.alamat,
    no_telp: props.bidan.no_telp,
    jenis_kelamin: props.bidan.jenis_kelamin,
});

function handleFoto(e: Event) {
    form.foto_bidan = (e.target as HTMLInputElement).files?.[0] || null;
}

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
            <div class="flex items-center gap-3">
                <Button variant="ghost" size="icon" @click="router.get(route('bidan.index'))"
                    class="h-9 w-9 rounded-full hover:bg-white/50">
                    <ArrowLeft class="h-5 w-5 text-gray-600" />
                </Button>
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Edit Bidan</h2>
                    <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                        <span>Data Bidan</span>
                        <ChevronRight class="h-3 w-3" />
                        <span class="font-medium text-gray-700">{{ bidan.nama }}</span>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Foto Profil -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-teal-50 text-teal-600"><User class="h-4 w-4" /></div>
                                Foto Profil
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 flex flex-col sm:flex-row items-center gap-5">
                            <div class="h-20 w-20 rounded-full overflow-hidden bg-teal-50 flex items-center justify-center border-4 border-white shadow shrink-0">
                                <img v-if="bidan.foto && !form.foto_bidan" :src="bidan.foto" :alt="bidan.nama" class="h-full w-full object-cover" />
                                <User v-else class="h-8 w-8 text-teal-400" />
                            </div>
                            <div class="flex-1 w-full space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Ganti Foto</Label>
                                <Input type="file" accept="image/*" @input="handleFoto"
                                    :class="{ 'border-destructive': form.errors.foto_bidan }" />
                                <p v-if="form.errors.foto_bidan" class="text-sm text-destructive">{{ form.errors.foto_bidan }}</p>
                                <p class="text-xs text-muted-foreground">Biarkan kosong untuk tidak mengubah foto.</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Akun -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-teal-50 text-teal-600"><Lock class="h-4 w-4" /></div>
                                Informasi Akun
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-1.5">
                            <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                <AtSign class="h-3 w-3" /> Username
                            </Label>
                            <Input v-model="form.username" :class="{ 'border-destructive': form.errors.username }" />
                            <p v-if="form.errors.username" class="text-sm text-destructive">{{ form.errors.username }}</p>
                        </CardContent>
                    </Card>

                    <!-- Profil -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-teal-50 text-teal-600"><User class="h-4 w-4" /></div>
                                Informasi Profil
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <User class="h-3 w-3" /> Nama Lengkap
                                </Label>
                                <Input v-model="form.nama_bidan" :class="{ 'border-destructive': form.errors.nama_bidan }" />
                                <p v-if="form.errors.nama_bidan" class="text-sm text-destructive">{{ form.errors.nama_bidan }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Jenis Kelamin</Label>
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
                        </CardContent>
                    </Card>

                    <!-- Kontak & Alamat -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-teal-50 text-teal-600"><Phone class="h-4 w-4" /></div>
                                Kontak & Alamat
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-4">
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Phone class="h-3 w-3" /> No. Telepon
                                </Label>
                                <Input v-model="form.no_telp" :class="{ 'border-destructive': form.errors.no_telp }" />
                                <p v-if="form.errors.no_telp" class="text-sm text-destructive">{{ form.errors.no_telp }}</p>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <MapPin class="h-3 w-3" /> Alamat Lengkap
                                </Label>
                                <Textarea v-model="form.alamat" rows="3" class="resize-none"
                                    :class="{ 'border-destructive': form.errors.alamat }" />
                                <p v-if="form.errors.alamat" class="text-sm text-destructive">{{ form.errors.alamat }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-2 pb-6">
                        <Button type="button" variant="ghost" @click="router.get(route('bidan.index'))" class="text-gray-500">Batalkan</Button>
                        <Button type="submit" :disabled="form.processing"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm shadow-blue-200 flex items-center gap-2">
                            <div v-if="form.processing" class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent" />
                            <Save v-else class="h-4 w-4" />
                            <span class="font-semibold">{{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
