<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import { ref, computed } from 'vue';
import { 
    Activity, 
    ArrowLeft, 
    Save, 
    User, 
    Baby, 
    Users, 
    Scale, 
    Maximize2, 
    HeartPulse, 
    ClipboardList,
    AlertCircle
} from 'lucide-vue-next';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

interface Participant {
    id: string;
    nama: string;
    nik: string;
}

const props = defineProps<{
    jadwal: any[];
    participants: {
        ibu_hamil: Participant[];
        balita: Participant[];
        lansia: Participant[];
    };
    kader: any[];
    bidan: any[];
}>();

const form = useForm({
    jadwal_posyandu_id: '',
    peserta_type: 'ibu_hamil' as 'ibu_hamil' | 'balita' | 'lansia',
    peserta_id: '',
    tgl_pemeriksaan: new Date().toISOString().split('T')[0],
    hadir: true,
    kader_id: '',
    bidan_id: '',
    
    // Core Vitals
    berat_badan: '',
    tinggi_badan: '',
    sistole: '',
    diastole: '',

    // Ibu Hamil specific
    usia_kehamilan: '',
    lila: '',
    kek_status: false,
    mt_bumil: false,
    ttd_status: false,
    kelas_bumil: false,

    // Balita specific
    lingkar_kepala: '',
    lingkar_lengan: '',
    obat_cacing: false,
    vitamin: '',

    // Lansia specific
    lingkar_perut: '',
    jenis_keluhan: '',
    obat_vitamin: '',

    edukasi: '',
    keterangan: '',
});

const currentParticipants = computed(() => {
    return props.participants[form.peserta_type] || [];
});

const submit = () => {
    form.post(route('pemeriksaan.store'), {
        onSuccess: () => {
            router.get(route('pemeriksaan.index'));
        }
    });
};
</script>

<template>
    <Head title="Input Pemeriksaan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" @click="router.get(route('pemeriksaan.index'))">
                    <ArrowLeft class="h-4 w-4" />
                </Button>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Data Pemeriksaan</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- General Information -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <ClipboardList class="h-5 w-5 text-blue-500" />
                                Informasi Dasar
                            </CardTitle>
                            <CardDescription>Pilih jadwal posyandu dan peserta yang akan diperiksa.</CardDescription>
                        </CardHeader>
                        <CardContent class="grid gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="jadwal">Jadwal Posyandu</Label>
                                <Select v-model="form.jadwal_posyandu_id" required>
                                    <SelectTrigger id="jadwal">
                                        <SelectValue placeholder="Pilih Jadwal..." />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="j in jadwal" :key="j.id" :value="j.id">
                                            {{ j.tanggal }} - {{ j.posyandu?.nama }} ({{ j.status }})
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="space-y-2">
                                <Label for="peserta_type">Kategori Peserta</Label>
                                <Select v-model="form.peserta_type" @update:model-value="form.peserta_id = ''">
                                    <SelectTrigger id="peserta_type">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="ibu_hamil">Ibu Hamil</SelectItem>
                                        <SelectItem value="balita">Balita</SelectItem>
                                        <SelectItem value="lansia">Lansia</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="space-y-2">
                                <Label for="peserta_id">Nama Peserta</Label>
                                <Select v-model="form.peserta_id" required>
                                    <SelectTrigger id="peserta_id">
                                        <SelectValue :placeholder="`Pilih ${form.peserta_type.replace('_', ' ')}...`" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="p in currentParticipants" :key="p.id" :value="p.id">
                                            {{ p.nama }} ({{ p.nik }})
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="space-y-2">
                                <Label for="tgl_pemeriksaan">Tanggal Pemeriksaan</Label>
                                <Input type="date" id="tgl_pemeriksaan" v-model="form.tgl_pemeriksaan" required />
                            </div>

                            <div class="flex items-center space-x-2 pt-8">
                                <Switch id="hadir" v-model:checked="form.hadir" />
                                <Label for="hadir">Peserta Hadir</Label>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Health Data Grid -->
                    <div class="grid gap-6 md:grid-cols-2" v-if="form.hadir">
                        <!-- Vitals -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <HeartPulse class="h-5 w-5 text-red-500" />
                                    Tanda Vital
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="grid gap-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <Label for="berat_badan">Berat Badan (kg)</Label>
                                        <div class="relative">
                                            <Scale class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                            <Input id="berat_badan" type="number" step="0.1" v-model="form.berat_badan" class="pl-10" />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="tinggi_badan">Tinggi Badan (cm)</Label>
                                        <div class="relative">
                                            <Maximize2 class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                            <Input id="tinggi_badan" type="number" step="0.1" v-model="form.tinggi_badan" class="pl-10" />
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <Label for="sistole">Sistole (mmHg)</Label>
                                        <Input id="sistole" type="number" v-model="form.sistole" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="diastole">Diastole (mmHg)</Label>
                                        <Input id="diastole" type="number" v-model="form.diastole" />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Dynamic Categories -->
                        <Card v-if="form.peserta_type === 'ibu_hamil'">
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <User class="h-5 w-5 text-pink-500" />
                                    Data Ibu Hamil
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="grid gap-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <Label for="usia_kehamilan">Usia Kehamilan (Minggu)</Label>
                                        <Input id="usia_kehamilan" type="number" v-model="form.usia_kehamilan" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="lila">LiLA (cm)</Label>
                                        <Input id="lila" type="number" step="0.1" v-model="form.lila" />
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 pt-2">
                                    <div class="flex items-center space-x-2">
                                        <Switch id="kek_status" v-model:checked="form.kek_status" />
                                        <Label for="kek_status">Status KEK</Label>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <Switch id="ttd_status" v-model:checked="form.ttd_status" />
                                        <Label for="ttd_status">Dapat TTD</Label>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card v-if="form.peserta_type === 'balita'">
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Baby class="h-5 w-5 text-blue-500" />
                                    Pertumbuhan Balita
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="grid gap-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <Label for="lingkar_kepala">Lingkar Kepala (cm)</Label>
                                        <Input id="lingkar_kepala" type="number" step="0.1" v-model="form.lingkar_kepala" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="lingkar_lengan">Lingkar Lengan (cm)</Label>
                                        <Input id="lingkar_lengan" type="number" step="0.1" v-model="form.lingkar_lengan" />
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <Label for="vitamin">Vitamin yang Diberikan</Label>
                                    <Input id="vitamin" v-model="form.vitamin" placeholder="Contoh: Vitamin A Biru" />
                                </div>
                                <div class="flex items-center space-x-2">
                                    <Switch id="obat_cacing" v-model:checked="form.obat_cacing" />
                                    <Label for="obat_cacing">Diberikan Obat Cacing</Label>
                                </div>
                            </CardContent>
                        </Card>

                        <Card v-if="form.peserta_type === 'lansia'">
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Users class="h-5 w-5 text-green-500" />
                                    Kesehatan Lansia
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="grid gap-4">
                                <div class="space-y-2">
                                    <Label for="lingkar_perut">Lingkar Perut (cm)</Label>
                                    <Input id="lingkar_perut" type="number" step="0.1" v-model="form.lingkar_perut" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="jenis_keluhan">Keluhan Utama</Label>
                                    <Input id="jenis_keluhan" v-model="form.jenis_keluhan" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="obat_vitamin">Obat/Vitamin yang Diberikan</Label>
                                    <Input id="obat_vitamin" v-model="form.obat_vitamin" />
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Notes & Staff -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                < ClipboardList class="h-5 w-5 text-gray-500" />
                                Catatan & Petugas
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="kader">Kader Petugas</Label>
                                    <Select v-model="form.kader_id">
                                        <SelectTrigger id="kader">
                                            <SelectValue placeholder="Pilih Kader..." />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="k in kader" :key="k.id" :value="k.id">{{ k.nama }}</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div class="space-y-2">
                                    <Label for="bidan">Bidan Pendamping</Label>
                                    <Select v-model="form.bidan_id">
                                        <SelectTrigger id="bidan">
                                            <SelectValue placeholder="Pilih Bidan..." />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="b in bidan" :key="b.id" :value="b.id">{{ b.nama }}</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label for="edukasi">Edukasi yang Diberikan</Label>
                                <Textarea id="edukasi" v-model="form.edukasi" placeholder="Materi edukasi atau konseling..." />
                            </div>
                            <div class="space-y-2">
                                <Label for="keterangan">Keterangan Tambahan</Label>
                                <Textarea id="keterangan" v-model="form.keterangan" />
                            </div>
                        </CardContent>
                    </Card>

                    <div class="flex justify-end gap-4">
                        <Button type="button" variant="outline" @click="router.get(route('pemeriksaan.index'))">
                            Batal
                        </Button>
                        <Button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700">
                            <Save class="mr-2 h-4 w-4" />
                            Simpan Pemeriksaan
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
