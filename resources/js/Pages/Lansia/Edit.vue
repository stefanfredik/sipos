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
import { ArrowLeft, Save, User, Phone, MapPin, ChevronRight, CreditCard } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

const props = defineProps<{
    lansia: {
        data: {
            id: string; nik: string; nama: string; foto: string | null;
            tgl_lahir: string; jenis_kelamin: string; no_telp: string;
            alamat: string; keterangan: string | null; is_active: boolean;
        }
    }
}>();

const d = props.lansia.data;
const toast = useToast();

const form = useForm({
    _method: 'PUT',
    nik: d.nik, nama: d.nama, foto: null as File | null,
    tgl_lahir: d.tgl_lahir, jenis_kelamin: d.jenis_kelamin,
    no_telp: d.no_telp, alamat: d.alamat,
    keterangan: d.keterangan || '', is_active: d.is_active,
});

const submit = () => {
    form.post(route('lansia.update', { lansia: d.id }), {
        forceFormData: true,
        
        
    });
};
</script>

<template>
    <Head title="Edit Lansia" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Button variant="ghost" size="icon" @click="router.get(route('lansia.index'))" class="h-9 w-9 rounded-full hover:bg-white/50">
                    <ArrowLeft class="h-5 w-5 text-gray-600" />
                </Button>
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Edit Lansia</h2>
                    <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                        <span>Data Lansia</span>
                        <ChevronRight class="h-3 w-3" />
                        <span class="font-medium text-gray-700">{{ d.nama }}</span>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Foto -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-orange-50 text-orange-500"><User class="h-4 w-4" /></div>
                                Foto Profil
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 flex flex-col sm:flex-row items-center gap-5">
                            <div class="h-20 w-20 rounded-full overflow-hidden bg-orange-50 flex items-center justify-center border-4 border-white shadow shrink-0">
                                <img v-if="d.foto && !form.foto" :src="d.foto" :alt="d.nama" class="h-full w-full object-cover" />
                                <User v-else class="h-8 w-8 text-orange-400" />
                            </div>
                            <div class="flex-1 w-full space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Ganti Foto</Label>
                                <Input type="file" accept="image/*" @input="form.foto = ($event.target as HTMLInputElement).files?.[0] || null"
                                    :class="{ 'border-destructive': form.errors.foto }" />
                                <p v-if="form.errors.foto" class="text-sm text-destructive">{{ form.errors.foto }}</p>
                                <p class="text-xs text-muted-foreground">Biarkan kosong untuk tidak mengubah foto.</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Identitas -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-orange-50 text-orange-500"><CreditCard class="h-4 w-4" /></div>
                                Identitas Diri
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">NIK (16 Digit)</Label>
                                <Input v-model="form.nik" maxlength="16" required :class="{ 'border-destructive': form.errors.nik }" />
                                <p v-if="form.errors.nik" class="text-sm text-destructive">{{ form.errors.nik }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Nama Lengkap</Label>
                                <Input v-model="form.nama" required :class="{ 'border-destructive': form.errors.nama }" />
                                <p v-if="form.errors.nama" class="text-sm text-destructive">{{ form.errors.nama }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Tanggal Lahir</Label>
                                <Input type="date" v-model="form.tgl_lahir" required :class="{ 'border-destructive': form.errors.tgl_lahir }" />
                                <p v-if="form.errors.tgl_lahir" class="text-sm text-destructive">{{ form.errors.tgl_lahir }}</p>
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
                            <div class="sm:col-span-2 space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Status Peserta</Label>
                                <Select :model-value="String(form.is_active)" @update:modelValue="(v: any) => form.is_active = v === 'true'">
                                    <SelectTrigger :class="{ 'border-destructive': form.errors.is_active }">
                                        <SelectValue placeholder="Pilih Status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="true">Aktif</SelectItem>
                                        <SelectItem value="false">Tidak Aktif</SelectItem>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.is_active" class="text-sm text-destructive">{{ form.errors.is_active }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Kontak & Alamat -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-orange-50 text-orange-500"><Phone class="h-4 w-4" /></div>
                                Kontak & Alamat
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-4">
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Phone class="h-3 w-3" /> No. Telepon
                                </Label>
                                <Input v-model="form.no_telp" required :class="{ 'border-destructive': form.errors.no_telp }" />
                                <p v-if="form.errors.no_telp" class="text-sm text-destructive">{{ form.errors.no_telp }}</p>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <MapPin class="h-3 w-3" /> Alamat Lengkap
                                </Label>
                                <Textarea v-model="form.alamat" required rows="3" class="resize-none" :class="{ 'border-destructive': form.errors.alamat }" />
                                <p v-if="form.errors.alamat" class="text-sm text-destructive">{{ form.errors.alamat }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Keterangan -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="text-base font-semibold text-gray-700">Keterangan Tambahan (Opsional)</CardTitle>
                        </CardHeader>
                        <CardContent class="p-5">
                            <Textarea v-model="form.keterangan" rows="3" class="resize-none"
                                placeholder="Contoh: Riwayat penyakit, alergi obat, dll."
                                :class="{ 'border-destructive': form.errors.keterangan }" />
                            <p v-if="form.errors.keterangan" class="text-sm text-destructive mt-1">{{ form.errors.keterangan }}</p>
                        </CardContent>
                    </Card>

                    <div class="flex items-center justify-between pt-2 pb-6">
                        <Button type="button" variant="ghost" @click="router.get(route('lansia.index'))" class="text-gray-500">Batalkan</Button>
                        <Button type="submit" :disabled="form.processing"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-lg shadow-sm shadow-orange-200 flex items-center gap-2">
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
