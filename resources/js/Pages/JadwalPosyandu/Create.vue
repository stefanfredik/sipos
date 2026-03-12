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
import { ArrowLeft, Loader, Loader2, Save } from 'lucide-vue-next';

const props = defineProps<{
    posyandu: Array<{ id: string; nama: string }>;
    kader: Array<{ id: string; nama: string }>;
    bidan: Array<{ id: string; nama: string }>;
}>();

const user = usePage().props.auth.user;

const form = useForm({
    posyandu_id: '',
    kader_id: '',
    bidan_id: '',
    tanggal: '',
    waktu_mulai: '08:00',
    waktu_selesai: '12:00',
    status: 'draft',
    catatan_bidan: '',
});

// Auto-select kader if the logged in user is a kader
const currentKader = props.kader.find(k => (k as any).user_id === user.id);
if (currentKader) {
    form.kader_id = currentKader.id;
}

const submit = () => {
    form.post(route('jadwal-posyandu.store'));
};
</script>

<template>
    <Head title="Buat Jadwal Posyandu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('jadwal-posyandu.index')">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Jadwal Baru</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Usulan Jadwal Posyandu</CardTitle>
                        <CardDescription>Tentukan waktu dan lokasi kegiatan Posyandu berikutnya.</CardDescription>
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <Label for="posyandu_id">Lokasi Posyandu</Label>
                                    <Select v-model="form.posyandu_id">
                                        <SelectTrigger :class="{ 'border-destructive': form.errors.posyandu_id }">
                                            <SelectValue placeholder="Pilih Posyandu" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="p in posyandu" :key="p.id" :value="p.id">
                                                {{ p.nama }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <p v-if="form.errors.posyandu_id" class="text-sm text-destructive">{{ form.errors.posyandu_id }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="kader_id">Kader Pengusul</Label>
                                    <Select v-model="form.kader_id">
                                        <SelectTrigger :class="{ 'border-destructive': form.errors.kader_id }">
                                            <SelectValue placeholder="Pilih Kader" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="k in kader" :key="k.id" :value="k.id">
                                                {{ k.nama }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <p v-if="form.errors.kader_id" class="text-sm text-destructive">{{ form.errors.kader_id }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="tanggal">Tanggal Kegiatan</Label>
                                    <Input
                                        id="tanggal"
                                        type="date"
                                        v-model="form.tanggal"
                                        required
                                        :class="{ 'border-destructive': form.errors.tanggal }"
                                    />
                                    <p v-if="form.errors.tanggal" class="text-sm text-destructive">{{ form.errors.tanggal }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <Label for="waktu_mulai">Waktu Mulai</Label>
                                        <Input
                                            id="waktu_mulai"
                                            type="time"
                                            v-model="form.waktu_mulai"
                                            required
                                            :class="{ 'border-destructive': form.errors.waktu_mulai }"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="waktu_selesai">Waktu Selesai</Label>
                                        <Input
                                            id="waktu_selesai"
                                            type="time"
                                            v-model="form.waktu_selesai"
                                            :class="{ 'border-destructive': form.errors.waktu_selesai }"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="catatan_bidan">Catatan (Optional)</Label>
                                <Textarea
                                    id="catatan_bidan"
                                    v-model="form.catatan_bidan"
                                    placeholder="Informasi tambahan terkait jadwal..."
                                    :class="{ 'border-destructive': form.errors.catatan_bidan }"
                                />
                                <p v-if="form.errors.catatan_bidan" class="text-sm text-destructive">{{ form.errors.catatan_bidan }}</p>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-end gap-4 border-t px-6 py-4">
                            <Link :href="route('jadwal-posyandu.index')">
                                <Button variant="ghost" type="button">Batal</Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                                <Save v-else class="mr-2 h-4 w-4" />
                                Simpan Jadwal
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
