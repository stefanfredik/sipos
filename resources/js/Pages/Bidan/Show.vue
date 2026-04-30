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
import { ArrowLeft, Edit, Trash2, User, Phone, MapPin, ChevronRight, AtSign, Calendar, UserCheck } from 'lucide-vue-next';

interface BidanDetail {
    id: string;
    nama: string;
    foto: string | null;
    alamat: string;
    no_telp: string;
    jenis_kelamin: string;
    username: string;
    email: string;
    created_at: string;
}

const props = defineProps<{ bidan: BidanDetail }>();

function deleteBidan() {
    router.delete(route('bidan.destroy', props.bidan.id));
}
</script>

<template>
    <Head :title="'Profil Bidan - ' + bidan.nama" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Button variant="ghost" size="icon" @click="router.get(route('bidan.index'))"
                        class="h-9 w-9 rounded-full hover:bg-white/50">
                        <ArrowLeft class="h-5 w-5 text-gray-600" />
                    </Button>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-gray-900">Profil Bidan</h2>
                        <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                            <span>Data Bidan</span>
                            <ChevronRight class="h-3 w-3" />
                            <span class="font-medium text-gray-700">{{ bidan.nama }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" @click="router.get(route('bidan.edit', bidan.id))"
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
                                <AlertDialogTitle>Hapus Data Bidan?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Data bidan <strong>{{ bidan.nama }}</strong> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Batal</AlertDialogCancel>
                                <AlertDialogAction @click="deleteBidan" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">Hapus</AlertDialogAction>
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
                    <div class="h-1.5 w-full bg-gradient-to-r from-teal-500 to-emerald-600" />
                    <CardContent class="p-6">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                            <div class="h-24 w-24 rounded-full overflow-hidden bg-teal-50 flex items-center justify-center border-4 border-white shadow-md shrink-0">
                                <img v-if="bidan.foto" :src="bidan.foto" :alt="bidan.nama" class="h-full w-full object-cover" />
                                <User v-else class="h-10 w-10 text-teal-400" />
                            </div>
                            <div class="text-center sm:text-left flex-1">
                                <h3 class="text-xl font-bold text-gray-900">{{ bidan.nama }}</h3>
                                <p class="font-mono text-sm text-muted-foreground mt-0.5">@{{ bidan.username }}</p>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                                    <Badge class="bg-teal-100 text-teal-700 border-teal-200 text-xs font-semibold">Bidan Desa</Badge>
                                    <Badge variant="outline" :class="bidan.jenis_kelamin === 'L'
                                        ? 'bg-blue-50 text-blue-700 border-blue-200 text-xs font-semibold'
                                        : 'bg-pink-50 text-pink-700 border-pink-200 text-xs font-semibold'">
                                        {{ bidan.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
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
                                <div class="p-1.5 rounded-lg bg-teal-50 text-teal-600"><Phone class="h-4 w-4" /></div>
                                Kontak
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-3">
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">No. Telepon</p>
                                <p class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                                    <Phone class="h-3.5 w-3.5 text-gray-400 shrink-0" />
                                    {{ bidan.no_telp || '—' }}
                                </p>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Email</p>
                                <p class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                                    <AtSign class="h-3.5 w-3.5 text-gray-400 shrink-0" />
                                    {{ bidan.email || '—' }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-teal-50 text-teal-600"><UserCheck class="h-4 w-4" /></div>
                                Data Diri
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-3">
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Jenis Kelamin</p>
                                <p class="text-sm font-medium text-gray-700">
                                    {{ bidan.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </p>
                            </div>
                            <Separator class="bg-gray-100" />
                            <div class="space-y-0.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Bergabung Sejak</p>
                                <p class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                                    <Calendar class="h-3.5 w-3.5 text-gray-400 shrink-0" />
                                    {{ bidan.created_at }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Alamat -->
                <Card class="border-none shadow-sm bg-white">
                    <CardHeader class="pb-3 border-b border-gray-100">
                        <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                            <div class="p-1.5 rounded-lg bg-teal-50 text-teal-600"><MapPin class="h-4 w-4" /></div>
                            Alamat Lengkap
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="p-5">
                        <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 border border-gray-100 rounded-lg p-3.5">
                            {{ bidan.alamat || '—' }}
                        </p>
                    </CardContent>
                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
