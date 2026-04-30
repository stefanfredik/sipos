<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import {
    AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent,
    AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { ArrowLeft, Edit, Trash2, User, Phone, MapPin, Home, ChevronRight, AtSign, Calendar } from 'lucide-vue-next';

interface KaderDetail {
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
}

const props = defineProps<{ kader: KaderDetail }>();

function deleteKader() {
    router.delete(route('kader.destroy', props.kader.id));
}
</script>

<template>
    <Head :title="'Profil Kader - ' + kader.nama" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Button variant="ghost" size="icon" @click="router.get(route('kader.index'))"
                        class="h-9 w-9 rounded-full hover:bg-white/50">
                        <ArrowLeft class="h-5 w-5 text-gray-600" />
                    </Button>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-gray-900">Profil Kader</h2>
                        <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                            <span>Data Kader</span>
                            <ChevronRight class="h-3 w-3" />
                            <span class="font-medium text-gray-700">{{ kader.nama }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" @click="router.get(route('kader.edit', kader.id))"
                        class="flex items-center gap-2 rounded-lg">
                        <Edit class="h-4 w-4" /> Edit
                    </Button>
                    <AlertDialog>
                        <AlertDialogTrigger as-child>
                            <Button variant="outline" class="flex items-center gap-2 rounded-lg border-red-200 text-red-600 hover:bg-red-50">
                                <Trash2 class="h-4 w-4" /> Hapus
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Hapus Data Kader?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Data kader <strong>{{ kader.nama }}</strong> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Batal</AlertDialogCancel>
                                <AlertDialogAction @click="deleteKader" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">Hapus</AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-5">

                <!-- Profile Hero -->
                <Card class="border-none shadow-sm bg-white overflow-hidden">
                    <div class="h-1.5 w-full bg-gradient-to-r from-violet-500 to-purple-600" />
                    <CardContent class="p-6">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                            <div class="h-24 w-24 rounded-full overflow-hidden bg-violet-50 flex items-center justify-center border-4 border-white shadow-md shrink-0">
                                <img v-if="kader.foto" :src="kader.foto" :alt="kader.nama" class="h-full w-full object-cover" />
                                <User v-else class="h-10 w-10 text-violet-400" />
                            </div>
                            <div class="text-center sm:text-left flex-1">
                                <h3 class="text-xl font-bold text-gray-900">{{ kader.nama }}</h3>
                                <p class="font-mono text-sm text-muted-foreground mt-0.5">@{{ kader.username }}</p>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                                    <Badge class="bg-violet-100 text-violet-700 border-violet-200 text-xs font-semibold">Kader Posyandu</Badge>
                                    <Badge variant="outline" :class="kader.jenis_kelamin === 'L'
                                        ? 'bg-blue-50 text-blue-700 border-blue-200 text-xs font-semibold'
                                        : 'bg-pink-50 text-pink-700 border-pink-200 text-xs font-semibold'">
                                        {{ kader.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Info Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><Home class="h-4 w-4" /></div>
                                Unit Tugas
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-3">
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Posyandu</p>
                                <p class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                                    <MapPin class="h-3.5 w-3.5 text-gray-400 shrink-0" />
                                    {{ kader.posyandu_nama || '—' }}
                                </p>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Bergabung Sejak</p>
                                <p class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                                    <Calendar class="h-3.5 w-3.5 text-gray-400 shrink-0" />
                                    {{ kader.created_at }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><Phone class="h-4 w-4" /></div>
                                Kontak
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-3">
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">No. Telepon</p>
                                <p class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                                    <Phone class="h-3.5 w-3.5 text-gray-400 shrink-0" />
                                    {{ kader.no_telp || '—' }}
                                </p>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Email</p>
                                <p class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                                    <AtSign class="h-3.5 w-3.5 text-gray-400 shrink-0" />
                                    {{ kader.email || '—' }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Alamat -->
                <Card class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-violet-50 text-violet-600"><MapPin class="h-4 w-4" /></div>
                            Alamat Lengkap
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5">
                        <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 border border-gray-100 rounded-lg p-3.5">
                            {{ kader.alamat || '—' }}
                        </p>
                    </CardContent>
                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
