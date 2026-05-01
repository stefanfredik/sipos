<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from '@/components/ui/alert-dialog';
import { ArrowLeft, Edit, Trash2, MapPin, Info, Calendar, Building2, ChevronRight } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';

const props = defineProps<{
    posyandu: {
        id: string;
        nama_posyandu: string;
        lokasi: string;
        deskripsi: string | null;
        is_active: boolean;
        created_at: string;
    };
}>();

const toast = useToast();

const deletePosyandu = () => {
    router.delete(route('posyandu.destroy', { posyandu: props.posyandu.id }), {
        
    });
};

const formatDate = (dateStr: string) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
    });
};
</script>

<template>
    <Head :title="'Detail ' + posyandu.nama_posyandu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Button
                        variant="ghost"
                        size="icon"
                        @click="router.get(route('posyandu.index'))"
                        class="h-9 w-9 rounded-full hover:bg-white/50"
                    >
                        <ArrowLeft class="h-5 w-5 text-gray-600" />
                    </Button>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-gray-900">Detail Posyandu</h2>
                        <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                            <span>Posyandu</span>
                            <ChevronRight class="h-3 w-3" />
                            <span class="font-medium text-gray-700">{{ posyandu.nama_posyandu }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button
                        variant="outline"
                        @click="router.get(route('posyandu.edit', { posyandu: posyandu.id }))"
                        class="flex items-center gap-2 rounded-lg"
                    >
                        <Edit class="h-4 w-4" />
                        Edit Data
                    </Button>
                    <AlertDialog>
                        <AlertDialogTrigger as-child>
                            <Button
                                variant="outline"
                                class="flex items-center gap-2 rounded-lg border-red-200 text-red-600 hover:bg-red-50"
                            >
                                <Trash2 class="h-4 w-4" />
                                Hapus
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Hapus Data Posyandu</AlertDialogTitle>
                                <AlertDialogDescription>Tindakan ini tidak dapat dibatalkan. Data posyandu akan dihapus secara permanen dari sistem.</AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Batal</AlertDialogCancel>
                                <Button @click="deletePosyandu" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">Hapus</Button>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Hero Card -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 to-indigo-600" />
                    <CardContent class="p-5">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-xl bg-blue-50 shrink-0">
                                <Building2 class="h-8 w-8 text-blue-600" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ posyandu.nama_posyandu }}</h3>
                                    <Badge
                                        variant="outline"
                                        :class="posyandu.is_active
                                            ? 'bg-green-50 text-green-700 border-green-200 text-xs font-semibold'
                                            : 'bg-gray-50 text-gray-500 border-gray-200 text-xs font-semibold'"
                                    >
                                        {{ posyandu.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </Badge>
                                </div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground mt-1">
                                    <MapPin class="h-3.5 w-3.5 shrink-0" />
                                    {{ posyandu.lokasi }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Info Details -->
                <Card class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                                <Info class="h-4 w-4" />
                            </div>
                            Informasi Detail
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Lokasi / Alamat</p>
                                <div class="flex items-start gap-1.5 text-gray-700">
                                    <MapPin class="h-4 w-4 text-gray-400 mt-0.5 shrink-0" />
                                    <p class="text-sm font-medium">{{ posyandu.lokasi }}</p>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Terdaftar Sejak</p>
                                <div class="flex items-start gap-1.5 text-gray-700">
                                    <Calendar class="h-4 w-4 text-gray-400 mt-0.5 shrink-0" />
                                    <p class="text-sm font-medium">{{ formatDate(posyandu.created_at) }}</p>
                                </div>
                            </div>
                        </div>

                        <Separator class="bg-gray-100" />

                        <div class="space-y-1.5">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Keterangan</p>
                            <div class="text-sm text-gray-600 bg-gray-50 border border-gray-100 p-3.5 rounded-lg whitespace-pre-wrap min-h-[60px] leading-relaxed">
                                {{ posyandu.deskripsi || 'Tidak ada keterangan tambahan.' }}
                            </div>
                        </div>
                    </CardContent>
                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
