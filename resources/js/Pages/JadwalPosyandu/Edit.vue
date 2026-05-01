<script setup lang="ts">
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Badge } from '@/components/ui/badge';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ArrowLeft, Save, Calendar, Clock, MapPin, Users, UserCheck, ChevronRight, CheckCircle2, XCircle, Info } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

interface Jadwal {
    id: string; posyandu_id: string; kader_id: string; bidan_id: string | null;
    tanggal: string; waktu_mulai: string; waktu_selesai: string | null;
    status: string; catatan_bidan: string | null;
}

const props = defineProps<{
    jadwal: { data: Jadwal };
    posyandu: Array<{ id: string; nama_posyandu: string }>;
    kader: Array<{ id: string; nama_kader: string }>;
    bidan: Array<{ id: string; nama_bidan: string }>;
}>();

const toast = useToast();
const user = (usePage().props.auth as any).user;
const isBidan = user.role === 'bidan';
const isAdmin = user.role === 'admin';

const form = useForm({
    posyandu_id: props.jadwal.data.posyandu_id,
    kader_id: props.jadwal.data.kader_id,
    bidan_id: props.jadwal.data.bidan_id || '',
    tanggal: props.jadwal.data.tanggal,
    waktu_mulai: props.jadwal.data.waktu_mulai,
    waktu_selesai: props.jadwal.data.waktu_selesai || '',
    status: props.jadwal.data.status,
    catatan_bidan: props.jadwal.data.catatan_bidan || '',
});

if (isBidan && !form.bidan_id) {
    const currentBidan = props.bidan.find((b) => (b as any).user_id === user.id);
    if (currentBidan) form.bidan_id = currentBidan.id;
}

const submit = () => {
    form.put(route('jadwal-posyandu.update', props.jadwal.data.id), {
        
        
    });
};

const validate = (newStatus: 'validated' | 'rejected') => {
    form.status = newStatus;
    submit();
};

const statusConfig: Record<string, { label: string; class: string }> = {
    draft:     { label: 'Draft',     class: 'bg-gray-50 text-gray-600 border-gray-200' },
    validated: { label: 'Disetujui', class: 'bg-green-50 text-green-700 border-green-200' },
    rejected:  { label: 'Ditolak',   class: 'bg-red-50 text-red-700 border-red-200' },
    completed: { label: 'Selesai',   class: 'bg-blue-50 text-blue-700 border-blue-200' },
};
</script>

<template>
    <Head title="Edit Jadwal Posyandu" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Button variant="ghost" size="icon" @click="router.get(route('jadwal-posyandu.index'))"
                    class="h-9 w-9 rounded-full hover:bg-white/50">
                    <ArrowLeft class="h-5 w-5 text-gray-600" />
                </Button>
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Edit / Validasi Jadwal</h2>
                    <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                        <span>Jadwal Posyandu</span>
                        <ChevronRight class="h-3 w-3" />
                        <Badge variant="outline" :class="['text-xs font-semibold', statusConfig[form.status]?.class]">
                            {{ statusConfig[form.status]?.label || form.status }}
                        </Badge>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Waktu & Lokasi -->
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
                                <Select v-model="form.posyandu_id" :disabled="!isAdmin && !isBidan">
                                    <SelectTrigger :class="{ 'border-destructive': form.errors.posyandu_id }">
                                        <SelectValue placeholder="Pilih Posyandu" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="p in posyandu" :key="p.id" :value="p.id">{{ p.nama_posyandu }}</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Calendar class="h-3 w-3" /> Tanggal Kegiatan
                                </Label>
                                <Input id="tanggal" type="date" v-model="form.tanggal" required :disabled="!isAdmin && !isBidan" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                        <Clock class="h-3 w-3" /> Waktu Mulai
                                    </Label>
                                    <Input id="waktu_mulai" type="time" v-model="form.waktu_mulai" required :disabled="!isAdmin && !isBidan" />
                                </div>
                                <div class="space-y-1.5">
                                    <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                        <Clock class="h-3 w-3" /> Waktu Selesai
                                    </Label>
                                    <Input id="waktu_selesai" type="time" v-model="form.waktu_selesai" :disabled="!isAdmin && !isBidan" />
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
                                <Select v-model="form.kader_id" disabled>
                                    <SelectTrigger><SelectValue placeholder="Pilih Kader" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="k in kader" :key="k.id" :value="k.id">{{ k.nama_kader }}</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Validasi Bidan -->
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-green-50 text-green-600"><UserCheck class="h-4 w-4" /></div>
                                Validasi Bidan
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-4">
                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <UserCheck class="h-3 w-3" /> Bidan Pemeriksa
                                </Label>
                                <Select v-model="form.bidan_id" :disabled="!isBidan && !isAdmin">
                                    <SelectTrigger><SelectValue placeholder="Pilih Bidan" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="b in bidan" :key="b.id" :value="b.id">{{ b.nama_bidan }}</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Info class="h-3 w-3" /> Status Jadwal
                                </Label>
                                <Select v-model="form.status" :disabled="!isBidan && !isAdmin">
                                    <SelectTrigger><SelectValue placeholder="Status" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="draft">Draft</SelectItem>
                                        <SelectItem value="validated">Disetujui</SelectItem>
                                        <SelectItem value="rejected">Ditolak</SelectItem>
                                        <SelectItem value="completed">Selesai Dilaksanakan</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <Separator class="bg-gray-100" />

                            <div class="space-y-1.5">
                                <Label class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Info class="h-3 w-3" /> Catatan Bidan / Alasan Penolakan
                                </Label>
                                <Textarea v-model="form.catatan_bidan" placeholder="Berikan catatan terkait validasi..."
                                    class="min-h-[80px] resize-none" :disabled="!isBidan && !isAdmin" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-2 pb-6">
                        <Button type="button" variant="ghost" @click="router.get(route('jadwal-posyandu.index'))" class="text-gray-500">
                            Batalkan
                        </Button>
                        <div class="flex gap-2">
                            <template v-if="isBidan || isAdmin">
                                <Button type="button" variant="outline" @click="validate('rejected')" :disabled="form.processing"
                                    class="border-red-200 text-red-600 hover:bg-red-50 gap-2">
                                    <XCircle class="h-4 w-4" /> Tolak
                                </Button>
                                <Button type="button" @click="validate('validated')" :disabled="form.processing"
                                    class="bg-green-600 hover:bg-green-700 text-white gap-2">
                                    <CheckCircle2 class="h-4 w-4" /> Setujui
                                </Button>
                            </template>
                            <Button type="submit" :disabled="form.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 rounded-lg shadow-sm shadow-blue-200 transition-all active:scale-95 flex items-center gap-2">
                                <div v-if="form.processing" class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent" />
                                <Save v-else class="h-4 w-4" />
                                <span class="font-semibold">{{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
                            </Button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
