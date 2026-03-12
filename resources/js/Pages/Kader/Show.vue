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
import { ArrowLeft, Edit, Trash, User, Phone, MapPin, Home } from 'lucide-vue-next';

const props = defineProps<{
    kader: {
        id: string;
        nama: string;
        foto: string | null;
        alamat: string;
        no_telp: string;
        jenis_kelamin: string;
        posyandu_nama: string;
        username: string;
        email: string;
        created_at: string;
    };
}>();

const deleteKader = () => {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('kader.destroy', props.kader.id));
    }
};
</script>

<template>
    <Head :title="'Profil Kader - ' + kader.nama_kader" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('kader.index')">
                        <Button variant="outline" size="icon">
                            <ArrowLeft class="h-4 w-4" />
                        </Button>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profil Kader</h2>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('kader.edit', kader.id)">
                        <Button variant="outline" class="flex items-center gap-2">
                            <Edit class="h-4 w-4" />
                            Edit
                        </Button>
                    </Link>
                    <Button variant="destructive" class="flex items-center gap-2" @click="deleteKader">
                        <Trash class="h-4 w-4" />
                        Hapus
                    </Button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <Card class="md:col-span-1">
                        <CardContent class="pt-6 flex flex-col items-center text-center">
                            <div class="h-32 w-32 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-4 mb-4">
                                <img v-if="kader.foto" :src="kader.foto" :alt="kader.nama_kader" class="h-full w-full object-cover" />
                                <User v-else class="h-16 w-16 text-gray-400" />
                            </div>
                            <h3 class="text-xl font-bold">{{ kader.nama_kader }}</h3>
                            <p class="text-sm text-muted-foreground mb-4">@{{ kader.username }}</p>
                            <Badge variant="outline" class="mb-4">Kader Posyandu</Badge>
                        </CardContent>
                    </Card>

                    <Card class="md:col-span-2">
                        <CardHeader>
                            <CardTitle>Informasi Profil</CardTitle>
                        </CardHeader>
                        <CardContent class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                            <div class="flex items-start gap-3">
                                <Home class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Unit Tugas</p>
                                    <p class="text-sm text-muted-foreground">{{ kader.posyandu_nama }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <Phone class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">No. Telepon</p>
                                    <p class="text-sm text-muted-foreground">{{ kader.no_telp }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <User class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Jenis Kelamin</p>
                                    <p class="text-sm text-muted-foreground">{{ kader.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <MapPin class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Alamat</p>
                                    <p class="text-sm text-muted-foreground">{{ kader.alamat }}</p>
                                </div>
                            </div>

                            <div class="md:col-span-2 border-t pt-4 mt-2">
                                <p class="text-xs text-muted-foreground text-center">
                                    Bergabung sejak {{ kader.created_at }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
