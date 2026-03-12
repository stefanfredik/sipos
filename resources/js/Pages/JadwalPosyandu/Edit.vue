<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    ArrowLeft,
    CheckCircle,
    Loader,
    Loader2,
    Save,
    XCircle,
} from 'lucide-vue-next';

interface Jadwal {
    id: string;
    posyandu_id: string;
    kader_id: string;
    bidan_id: string | null;
    tanggal: string;
    waktu_mulai: string;
    waktu_selesai: string | null;
    status: string;
    catatan_bidan: string | null;
}

const props = defineProps<{
    jadwal: { data: Jadwal };
    posyandu: Array<{ id: string; nama_posyandu: string }>;
    kader: Array<{ id: string; nama_kader: string }>;
    bidan: Array<{ id: string; nama_bidan: string }>;
}>();

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

// Auto-fill bidan_id if validating as bidan
if (isBidan && !form.bidan_id) {
    const currentBidan = props.bidan.find(
        (b) => (b as any).user_id === user.id,
    );
    if (currentBidan) {
        form.bidan_id = currentBidan.id;
    }
}

const submit = () => {
    form.put(route('jadwal-posyandu.update', props.jadwal.data.id));
};

const validate = (newStatus: 'validated' | 'rejected') => {
    form.status = newStatus;
    submit();
};
</script>

<template>
    <Head title="Edit Jadwal Posyandu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('jadwal-posyandu.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <h2 class="text-xl leading-tight font-semibold text-gray-800">
                    Edit / Validasi Jadwal
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Detail Jadwal Posyandu</CardTitle>
                        <CardDescription
                            >Perbarui informasi atau validasi jadwal
                            kegiataan.</CardDescription
                        >
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="posyandu_id"
                                        >Lokasi Posyandu</Label
                                    >
                                    <Select
                                        v-model="form.posyandu_id"
                                        :disabled="!isAdmin && !isBidan"
                                    >
                                        <SelectTrigger
                                            :class="{
                                                'border-destructive':
                                                    form.errors.posyandu_id,
                                            }"
                                        >
                                            <SelectValue
                                                placeholder="Pilih Posyandu"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="p in posyandu"
                                                :key="p.id"
                                                :value="p.id"
                                            >
                                                {{ p.nama_posyandu }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div class="space-y-2">
                                    <Label for="kader_id">Kader Pengusul</Label>
                                    <Select v-model="form.kader_id" disabled>
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Pilih Kader"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="k in kader"
                                                :key="k.id"
                                                :value="k.id"
                                            >
                                                {{ k.nama_kader }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div class="space-y-2">
                                    <Label for="tanggal"
                                        >Tanggal Kegiatan</Label
                                    >
                                    <Input
                                        id="tanggal"
                                        type="date"
                                        v-model="form.tanggal"
                                        required
                                        :disabled="!isAdmin && !isBidan"
                                    />
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <Label for="waktu_mulai"
                                            >Waktu Mulai</Label
                                        >
                                        <Input
                                            id="waktu_mulai"
                                            type="time"
                                            v-model="form.waktu_mulai"
                                            required
                                            :disabled="!isAdmin && !isBidan"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="waktu_selesai"
                                            >Waktu Selesai</Label
                                        >
                                        <Input
                                            id="waktu_selesai"
                                            type="time"
                                            v-model="form.waktu_selesai"
                                            :disabled="!isAdmin && !isBidan"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4 border-t pt-4">
                                <h3 class="text-lg font-medium">
                                    Validasi Bidan
                                </h3>
                                <div class="space-y-2">
                                    <Label for="bidan_id"
                                        >Bidan Pemeriksa</Label
                                    >
                                    <Select
                                        v-model="form.bidan_id"
                                        :disabled="!isBidan && !isAdmin"
                                    >
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Pilih Bidan"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="b in bidan"
                                                :key="b.id"
                                                :value="b.id"
                                            >
                                                {{ b.nama_bidan }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div class="space-y-2">
                                    <Label for="status">Status Jadwal</Label>
                                    <Select
                                        v-model="form.status"
                                        :disabled="!isBidan && !isAdmin"
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Status" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="draft"
                                                >Draft</SelectItem
                                            >
                                            <SelectItem value="validated"
                                                >Divalidasi
                                                (Disetujui)</SelectItem
                                            >
                                            <SelectItem value="rejected"
                                                >Ditolak</SelectItem
                                            >
                                            <SelectItem value="completed"
                                                >Selesai
                                                dilaksanakan</SelectItem
                                            >
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div class="space-y-2">
                                    <Label for="catatan_bidan"
                                        >Catatan Bidan / Alasan Penolakan</Label
                                    >
                                    <Textarea
                                        id="catatan_bidan"
                                        v-model="form.catatan_bidan"
                                        placeholder="Berikan catatan terkait validasi..."
                                        :disabled="!isBidan && !isAdmin"
                                    />
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter
                            class="flex justify-between border-t px-6 py-4"
                        >
                            <Link :href="route('jadwal-posyandu.index')">
                                <Button variant="ghost" type="button"
                                    >Batal</Button
                                >
                            </Link>

                            <div class="flex gap-2">
                                <template v-if="isBidan || isAdmin">
                                    <Button
                                        variant="destructive"
                                        type="button"
                                        @click="validate('rejected')"
                                        :disabled="form.processing"
                                    >
                                        Tolak
                                    </Button>
                                    <Button
                                        variant="default"
                                        type="button"
                                        class="bg-green-600 hover:bg-green-700"
                                        @click="validate('validated')"
                                        :disabled="form.processing"
                                    >
                                        Setujui
                                    </Button>
                                </template>
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                >
                                    <Loader2
                                        v-if="form.processing"
                                        class="mr-2 h-4 w-4 animate-spin"
                                    />
                                    <Save v-else class="mr-2 h-4 w-4" />
                                    Simpan Perubahan
                                </Button>
                            </div>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
