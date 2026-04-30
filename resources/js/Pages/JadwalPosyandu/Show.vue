<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';
import {
    AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent,
    AlertDialogDescription, AlertDialogFooter, AlertDialogHeader,
    AlertDialogTitle, AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import {
    ArrowLeft, Calendar, Clock, MapPin, Users, UserCheck,
    Edit, Trash2, CheckCircle2, XCircle, FileCheck, ChevronRight, Info,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

interface Jadwal {
    id: string;
    posyandu: { id: string; nama_posyandu: string; lokasi?: string } | null;
    kader: { id: string; nama: string } | null;
    bidan: { id: string; nama: string } | null;
    tanggal: string;
    waktu_mulai: string;
    waktu_selesai: string | null;
    status: string;
    status_label: string;
    catatan_bidan: string | null;
}

const props = defineProps<{ jadwal: { data: Jadwal } }>();

const page = usePage();
const userRole = computed(() => (page.props.auth as any)?.user?.role);
const canValidate = computed(() => ['admin', 'bidan'].includes(userRole.value) && props.jadwal.data.status === 'draft');
const canComplete = computed(() => ['admin', 'bidan'].includes(userRole.value) && props.jadwal.data.status === 'validated');
const canEdit = computed(() => ['admin', 'bidan', 'kader'].includes(userRole.value));

const catatanBidan = ref('');

const statusConfig: Record<string, { label: string; class: string }> = {
    draft:     { label: 'Draft',     class: 'bg-gray-50 text-gray-600 border-gray-200' },
    validated: { label: 'Disetujui', class: 'bg-green-50 text-green-700 border-green-200' },
    rejected:  { label: 'Ditolak',   class: 'bg-red-50 text-red-700 border-red-200' },
    completed: { label: 'Selesai',   class: 'bg-blue-50 text-blue-700 border-blue-200' },
};

function formatDate(d: string) {
    return new Date(d).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}

function validateJadwal() {
    router.post(route('jadwal-posyandu.validate', props.jadwal.data.id), {
        bidan_id: (page.props.auth as any)?.user?.bidan?.id || '',
        catatan_bidan: catatanBidan.value,
    });
}

function rejectJadwal() {
    if (!catatanBidan.value) return;
    router.post(route('jadwal-posyandu.reject', props.jadwal.data.id), { catatan_bidan: catatanBidan.value });
}

function completeJadwal() {
    router.post(route('jadwal-posyandu.complete', props.jadwal.data.id));
}

function deleteJadwal() {
    router.delete(route('jadwal-posyandu.destroy', props.jadwal.data.id));
}
</script>

<template>
    <Head title="Detail Jadwal Posyandu" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Button variant="ghost" size="icon" @click="router.get(route('jadwal-posyandu.index'))"
                        class="h-9 w-9 rounded-full hover:bg-white/50">
                        <ArrowLeft class="h-5 w-5 text-gray-600" />
                    </Button>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-gray-900">Detail Jadwal</h2>
                        <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                            <span>Jadwal Posyandu</span>
                            <ChevronRight class="h-3 w-3" />
                            <span class="font-medium text-gray-700">{{ jadwal.data.posyandu?.nama_posyandu }}</span>
                        </div>
                    </div>
                </div>
                <div v-if="canEdit" class="flex items-center gap-2">
                    <Button variant="outline" @click="router.get(route('jadwal-posyandu.edit', jadwal.data.id))"
                        class="flex items-center gap-2 rounded-lg">
                        <Edit class="h-4 w-4" /> Edit / Validasi
                    </Button>
                    <AlertDialog>
                        <AlertDialogTrigger as-child>
                            <Button variant="outline" class="flex items-center gap-2 rounded-lg border-red-200 text-red-600 hover:bg-red-50">
                                <Trash2 class="h-4 w-4" /> Hapus
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Hapus Jadwal?</AlertDialogTitle>
                                <AlertDialogDescription>Jadwal posyandu ini akan dihapus. Tindakan ini tidak dapat dibatalkan.</AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Batal</AlertDialogCancel>
                                <AlertDialogAction @click="deleteJadwal">Hapus</AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 space-y-5">

                <!-- Hero -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <div class="h-1.5 w-full bg-gradient-to-r from-amber-400 to-orange-500" />
                    <CardContent class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="p-3 rounded-xl bg-amber-50 shrink-0">
                                    <Calendar class="h-7 w-7 text-amber-600" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ formatDate(jadwal.data.tanggal) }}</h3>
                                    <p class="flex items-center gap-1.5 text-sm text-muted-foreground mt-1">
                                        <Clock class="h-3.5 w-3.5" />
                                        {{ jadwal.data.waktu_mulai }}{{ jadwal.data.waktu_selesai ? ' – ' + jadwal.data.waktu_selesai : '' }}
                                    </p>
                                    <p class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                                        <MapPin class="h-3.5 w-3.5" />
                                        {{ jadwal.data.posyandu?.nama_posyandu }}
                                        <span v-if="jadwal.data.posyandu?.lokasi" class="text-gray-400">· {{ jadwal.data.posyandu.lokasi }}</span>
                                    </p>
                                </div>
                            </div>
                            <Badge variant="outline"
                                :class="['text-xs font-semibold px-2.5 py-1 shrink-0', statusConfig[jadwal.data.status]?.class]">
                                {{ statusConfig[jadwal.data.status]?.label || jadwal.data.status_label }}
                            </Badge>
                        </div>
                    </CardContent>
                </Card>

                <!-- Petugas Info -->
                <Card class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-amber-50 text-amber-600"><Users class="h-4 w-4" /></div>
                            Petugas Kegiatan
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Kader Pengusul</p>
                            <div class="flex items-center gap-1.5">
                                <Users class="h-4 w-4 text-gray-400 shrink-0" />
                                <p class="text-sm font-medium text-gray-700">{{ jadwal.data.kader?.nama || '—' }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Bidan Pemeriksa</p>
                            <div class="flex items-center gap-1.5">
                                <UserCheck class="h-4 w-4 text-gray-400 shrink-0" />
                                <p class="text-sm font-medium text-gray-700">{{ jadwal.data.bidan?.nama || 'Belum ditentukan' }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Catatan Bidan -->
                <Card v-if="jadwal.data.catatan_bidan" class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600"><Info class="h-4 w-4" /></div>
                            Catatan Bidan
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5">
                        <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 border border-gray-100 rounded-lg p-3.5 whitespace-pre-wrap">
                            {{ jadwal.data.catatan_bidan }}
                        </p>
                    </CardContent>
                </Card>

                <!-- Validasi Card -->
                <Card v-if="canValidate" class="border-none shadow-sm bg-white border-l-4 border-l-amber-400">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-amber-50 text-amber-600"><CheckCircle2 class="h-4 w-4" /></div>
                            Tindakan Validasi
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5 space-y-4">
                        <div class="space-y-1.5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                Catatan <span class="font-normal normal-case">(opsional untuk validasi, wajib untuk penolakan)</span>
                            </p>
                            <Textarea v-model="catatanBidan" placeholder="Tulis catatan..." class="min-h-[80px] resize-none" />
                        </div>
                        <div class="flex gap-3">
                            <Button @click="validateJadwal" class="bg-green-600 hover:bg-green-700 text-white gap-2">
                                <CheckCircle2 class="h-4 w-4" /> Validasi Jadwal
                            </Button>
                            <Button variant="outline" @click="rejectJadwal" :disabled="!catatanBidan"
                                class="border-red-200 text-red-600 hover:bg-red-50 gap-2">
                                <XCircle class="h-4 w-4" /> Tolak Jadwal
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <!-- Selesaikan -->
                <Card v-if="canComplete" class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600"><FileCheck class="h-4 w-4" /></div>
                            Selesaikan Jadwal
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5">
                        <p class="text-sm text-muted-foreground mb-4">Tandai jadwal ini sebagai selesai setelah kegiatan dilaksanakan.</p>
                        <Button @click="completeJadwal" class="bg-blue-600 hover:bg-blue-700 text-white gap-2">
                            <FileCheck class="h-4 w-4" /> Tandai Selesai
                        </Button>
                    </CardContent>
                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
