<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, Edit, Trash, MapPin, Info, Calendar } from 'lucide-vue-next';

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

const deletePosyandu = () => {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('posyandu.destroy', { posyandu: props.posyandu.id }));
    }
};
</script>

<template>
    <Head :title="'Detail ' + posyandu.nama_posyandu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('posyandu.index')">
                        <Button variant="outline" size="icon">
                            <ArrowLeft class="h-4 w-4" />
                        </Button>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Posyandu</h2>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('posyandu.edit', { posyandu: posyandu.id })">
                        <Button variant="outline" class="flex items-center gap-2">
                            <Edit class="h-4 w-4" />
                            Edit
                        </Button>
                    </Link>
                    <Button variant="destructive" class="flex items-center gap-2" @click="deletePosyandu">
                        <Trash class="h-4 w-4" />
                        Hapus
                    </Button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <Card>
                    <CardHeader>
                        <div class="flex justify-between items-start">
                            <div>
                                <CardTitle class="text-2xl">{{ posyandu.nama_posyandu }}</CardTitle>
                                <CardDescription>Informasi unit posyandu.</CardDescription>
                            </div>
                            <Badge :variant="posyandu.is_active ? 'default' : 'destructive'">
                                {{ posyandu.is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <MapPin class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Lokasi / Alamat</p>
                                    <p class="text-sm text-muted-foreground">{{ posyandu.lokasi }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <Calendar class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Terdaftar Sejak</p>
                                    <p class="text-sm text-muted-foreground">{{ posyandu.created_at }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <Info class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Keterangan</p>
                                    <p class="text-sm text-muted-foreground whitespace-pre-wrap">
                                        {{ posyandu.deskripsi || 'Tidak ada keterangan tambahan.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Future: List of Kader/Bidan assigned to this Posyandu -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
