<script setup lang="ts">
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ArrowLeft, Save, Calendar, Clock, MapPin, Users, UserCheck, ChevronRight, Info } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

const props = defineProps<{
    posyandu: Array<{ id: string; nama_posyandu: string }>;
    kader: Array<{ id: string; nama_kader: string }>;
    bidan: Array<{ id: string; nama_bidan: string }>;
}>();

const toast = useToast();
const user = (usePage().props as any).auth?.user;

const form = useForm({
    posyandu_id: '',
    kader_id: '',
    bidan_id: '',
    tanggal: '',
    waktu_mulai: '08:00',
    waktu_selesai: '12:00',
    status: 'draft',
    catatan_bidan: '',
});

const currentKader = props.kader.find((k) => (k as any).user_id === user.id);
if (currentKader) form.kader_id = currentKader.id;

const submit = () => {
    form.post(route('jadwal-posyandu.store'), {
        onSuccess: () => toast.success('Berhasil', 'Jadwal posyandu berhasil dibuat.'),
        onError: () => toast.error('Gagal', 'Terjadi kesalahan saat menyimpan jadwal.'),
    });
};
</script>

<template>
    <Head title="Buat Jadwal Posyandu" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Button variant="ghost" size="icon" @click="router.get(route('jadwal-posyandu.index'))"
                    class="h-9 w-9 rounded-full hover:bg-white/50">
                    <ArrowLeft class="h-5 w-5 text-gray-600" />
                </Button>
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Buat Jadwal Baru</h2>
                    <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                        <span>Jadwal Posyandu</span>
                        <ChevronRight class="h-3 w-3" />
                        <span>Baru</span>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Lokasi & Waktu -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-amber-50 text-amber-600"><Calendar class="h-4 w-4" /></div>
                                Waktu & Lokasi
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-4">
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <MapPin class="h-3 w-3" /> Lokasi Posyandu
                                </Label>
                                <Select v-model="form.posyandu_id">
                                    <SelectTrigger :class="{ 'border-destructive': form.errors.posyandu_id }">
                                        <SelectValue placeholder="Pilih Posyandu" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="p in posyandu" :key="p.id" :value="p.id">{{ p.nama_posyandu }}</SelectItem>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.posyandu_id" class="text-sm text-destructive">{{ form.errors.posyandu_id }}</p>
                            </div>

                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Calendar class="h-3 w-3" /> Tanggal Kegiatan
                                </Label>
                                <Input id="tanggal" type="date" v-model="form.tanggal" required
                                    :class="{ 'border-destructive': form.errors.tanggal }" />
                                <p v-if="form.errors.tanggal" class="text-sm text-destructive">{{ form.errors.tanggal }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                        <Clock class="h-3 w-3" /> Waktu Mulai
                                    </Label>
                                    <Input id="waktu_mulai" type="time" v-model="form.waktu_mulai" required
                                        :class="{ 'border-destructive': form.errors.waktu_mulai }" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                        <Clock class="h-3 w-3" /> Waktu Selesai
                                    </Label>
                                    <Input id="waktu_selesai" type="time" v-model="form.waktu_selesai"
                                        :class="{ 'border-destructive': form.errors.waktu_selesai }" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Petugas -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-amber-50 text-amber-600"><Users class="h-4 w-4" /></div>
                                Petugas
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-4">
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Users class="h-3 w-3" /> Kader Pengusul
                                </Label>
                                <Select v-model="form.kader_id">
                                    <SelectTrigger :class="{ 'border-destructive': form.errors.kader_id }">
                                        <SelectValue placeholder="Pilih Kader" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="k in kader" :key="k.id" :value="k.id">{{ k.nama_kader }}</SelectItem>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.kader_id" class="text-sm text-destructive">{{ form.errors.kader_id }}</p>
                            </div>

                            <Separator class="bg-gray-100" />

                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Info class="h-3 w-3" /> Catatan <span class="font-normal normal-case">(Opsional)</span>
                                </Label>
                                <Textarea v-model="form.catatan_bidan" placeholder="Informasi tambahan terkait jadwal..."
                                    class="min-h-[80px] resize-none"
                                    :class="{ 'border-destructive': form.errors.catatan_bidan }" />
                                <p v-if="form.errors.catatan_bidan" class="text-sm text-destructive">{{ form.errors.catatan_bidan }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-2 pb-6">
                        <Button type="button" variant="ghost" @click="router.get(route('jadwal-posyandu.index'))" class="text-gray-500">
                            Batalkan
                        </Button>
                        <Button type="submit" :disabled="form.processing"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm shadow-blue-200 transition-all active:scale-95 flex items-center gap-2">
                            <div v-if="form.processing" class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent" />
                            <Save v-else class="h-4 w-4" />
                            <span class="font-semibold">{{ form.processing ? 'Menyimpan...' : 'Simpan Jadwal' }}</span>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
