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
import { ArrowLeft, Save, User, Phone, MapPin, ChevronRight, CreditCard, Baby } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

const toast = useToast();

const form = useForm({
    nik: '', nama: '', foto: null as File | null,
    nama_orang_tua: '', tgl_lahir: '',
    jenis_kelamin: 'L', no_telp: '', alamat: '', keterangan: '',
});

const submit = () => {
    form.post(route('balita.store'), {
        forceFormData: true,
        onSuccess: () => toast.success('Berhasil', 'Data balita berhasil ditambahkan.'),
        onError: () => toast.error('Gagal', 'Terjadi kesalahan saat menyimpan data.'),
    });
};
</script>

<template>
    <Head title="Tambah Balita" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Button variant="ghost" size="icon" @click="router.get(route('balita.index'))" class="h-9 w-9 rounded-full hover:bg-white/50">
                    <ArrowLeft class="h-5 w-5 text-gray-600" />
                </Button>
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Tambah Balita</h2>
                    <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                        <span>Data Balita</span>
                        <ChevronRight class="h-3 w-3" />
                        <span>Pendaftaran Baru</span>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Identitas Balita -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600"><Baby class="h-4 w-4" /></div>
                                Identitas Balita
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">NIK Balita (16 Digit)</Label>
                                <Input v-model="form.nik" maxlength="16" required :class="{ 'border-destructive': form.errors.nik }" />
                                <p v-if="form.errors.nik" class="text-sm text-destructive">{{ form.errors.nik }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Nama Lengkap Balita</Label>
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
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Foto Balita</Label>
                                <Input type="file" accept="image/*" @input="form.foto = ($event.target as HTMLInputElement).files?.[0] || null"
                                    :class="{ 'border-destructive': form.errors.foto }" />
                                <p v-if="form.errors.foto" class="text-sm text-destructive">{{ form.errors.foto }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Data Orang Tua -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600"><CreditCard class="h-4 w-4" /></div>
                                Data Orang Tua / Wali
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-4">
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <User class="h-3 w-3" /> Nama Orang Tua / Wali
                                </Label>
                                <Input v-model="form.nama_orang_tua" required :class="{ 'border-destructive': form.errors.nama_orang_tua }" />
                                <p v-if="form.errors.nama_orang_tua" class="text-sm text-destructive">{{ form.errors.nama_orang_tua }}</p>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Phone class="h-3 w-3" /> No. Telepon Orang Tua
                                </Label>
                                <Input v-model="form.no_telp" required :class="{ 'border-destructive': form.errors.no_telp }" />
                                <p v-if="form.errors.no_telp" class="text-sm text-destructive">{{ form.errors.no_telp }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Alamat -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600"><MapPin class="h-4 w-4" /></div>
                                Alamat
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-4">
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Alamat Lengkap</Label>
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
                                placeholder="Contoh: Riwayat imunisasi, alergi, kondisi khusus, dll."
                                :class="{ 'border-destructive': form.errors.keterangan }" />
                            <p v-if="form.errors.keterangan" class="text-sm text-destructive mt-1">{{ form.errors.keterangan }}</p>
                        </CardContent>
                    </Card>

                    <div class="flex items-center justify-between pt-2 pb-6">
                        <Button type="button" variant="ghost" @click="router.get(route('balita.index'))" class="text-gray-500">Batalkan</Button>
                        <Button type="submit" :disabled="form.processing"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm shadow-blue-200 flex items-center gap-2">
                            <div v-if="form.processing" class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent" />
                            <Save v-else class="h-4 w-4" />
                            <span class="font-semibold">{{ form.processing ? 'Menyimpan...' : 'Simpan Data' }}</span>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
