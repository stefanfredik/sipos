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
import { ArrowLeft, Edit, Trash, User, Phone, MapPin } from 'lucide-vue-next';

const props = defineProps<{
    bidan: {
        id: string;
        nama: string;
        foto: string | null;
        alamat: string;
        no_telp: string;
        jenis_kelamin: string;
        username: string;
        email: string;
        created_at: string;
    };
}>();

const deleteBidan = () => {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('bidan.destroy', props.bidan.id));
    }
};
</script>

<template>
    <Head :title="'Profil Bidan - ' + bidan.nama" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('bidan.index')">
                        <Button variant="outline" size="icon">
                            <ArrowLeft class="h-4 w-4" />
                        </Button>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profil Bidan</h2>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('bidan.edit', bidan.id)">
                        <Button variant="outline" class="flex items-center gap-2">
                            <Edit class="h-4 w-4" />
                            Edit
                        </Button>
                    </Link>
                    <Button variant="destructive" class="flex items-center gap-2" @click="deleteBidan">
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
                                <img v-if="bidan.foto" :src="bidan.foto" :alt="bidan.nama" class="h-full w-full object-cover" />
                                <User v-else class="h-16 w-16 text-gray-400" />
                            </div>
                            <h3 class="text-xl font-bold">{{ bidan.nama }}</h3>
                            <p class="text-sm text-muted-foreground mb-4">@{{ bidan.username }}</p>
                            <Badge variant="outline" class="mb-4">Bidan Desa</Badge>
                        </CardContent>
                    </Card>

                    <Card class="md:col-span-2">
                        <CardHeader>
                            <CardTitle>Informasi Profil</CardTitle>
                        </CardHeader>
                        <CardContent class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                            <div class="flex items-start gap-3">
                                <Phone class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">No. Telepon</p>
                                    <p class="text-sm text-muted-foreground">{{ bidan.no_telp }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <User class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Jenis Kelamin</p>
                                    <p class="text-sm text-muted-foreground">{{ bidan.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 md:col-span-2">
                                <MapPin class="h-5 w-5 text-muted-foreground mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium">Alamat</p>
                                    <p class="text-sm text-muted-foreground">{{ bidan.alamat }}</p>
                                </div>
                            </div>

                            <div class="md:col-span-2 border-t pt-4 mt-2">
                                <p class="text-xs text-muted-foreground text-center">
                                    Bergabung sejak {{ bidan.created_at }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
