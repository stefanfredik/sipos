<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Switch } from '@/components/ui/switch';
import { ArrowLeft, Save, Building2, MapPin, Info, ChevronRight } from 'lucide-vue-next';
import { useToast } from '@/Composables/useToast';
import { computed } from 'vue';

const props = defineProps<{
    posyandu: {
        id: string;
        nama_posyandu: string;
        lokasi: string;
        deskripsi: string | null;
        is_active: boolean;
    };
}>();

const toast = useToast();

const form = useForm({
    nama_posyandu: props.posyandu?.nama_posyandu ?? '',
    lokasi: props.posyandu?.lokasi ?? '',
    deskripsi: props.posyandu?.deskripsi ?? '',
    is_active: props.posyandu?.is_active ?? true,
});

const submit = () => {
    form.put(route('posyandu.update', props.posyandu.id), {
        preserveScroll: true,
        onSuccess: () => toast.success('Berhasil', 'Data posyandu berhasil diperbarui.'),
        onError: () => toast.error('Gagal', 'Terjadi kesalahan saat menyimpan perubahan.'),
    });
};
</script>

<template>
    <Head title="Edit Posyandu" />

    <AuthenticatedLayout>
        <template #header>
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
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Edit Posyandu</h2>
                    <div class="flex items-center gap-1.5 text-sm text-muted-foreground mt-0.5">
                        <span>Posyandu</span>
                        <ChevronRight class="h-3 w-3" />
                        <span class="font-medium text-gray-700">{{ posyandu.nama_posyandu }}</span>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6">

                    <Card class="border-none shadow-sm bg-white">
                        <CardHeader class="pb-3 border-b border-gray-100">
                            <CardTitle class="flex items-center gap-2 text-base font-semibold text-gray-700">
                                <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                                    <Building2 class="h-4 w-4" />
                                </div>
                                Data Unit Posyandu
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-5 space-y-4">
                            <div class="space-y-1.5">
                                <Label for="nama_posyandu" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Building2 class="h-3 w-3" /> Nama Posyandu
                                </Label>
                                <Input
                                    id="nama_posyandu"
                                    v-model="form.nama_posyandu"
                                    required
                                    :class="{ 'border-destructive': form.errors.nama_posyandu }"
                                />
                                <p v-if="form.errors.nama_posyandu" class="text-sm text-destructive">{{ form.errors.nama_posyandu }}</p>
                            </div>

                            <div class="space-y-1.5">
                                <Label for="lokasi" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <MapPin class="h-3 w-3" /> Lokasi / Alamat
                                </Label>
                                <Input
                                    id="lokasi"
                                    v-model="form.lokasi"
                                    required
                                    :class="{ 'border-destructive': form.errors.lokasi }"
                                />
                                <p v-if="form.errors.lokasi" class="text-sm text-destructive">{{ form.errors.lokasi }}</p>
                            </div>

                            <!-- Status Aktif -->
                            <div class="flex items-center justify-between p-3 rounded-xl border border-gray-200 bg-gray-50">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">Status Posyandu</p>
                                    <p class="text-xs text-muted-foreground">Posyandu {{ form.is_active ? 'sedang aktif beroperasi' : 'tidak aktif / ditangguhkan' }}</p>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="text-sm font-medium" :class="form.is_active ? 'text-green-600' : 'text-gray-400'">
                                        {{ form.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <Switch id="is_active" v-model:checked="form.is_active" class="data-[state=checked]:bg-green-500" />
                                </div>
                            </div>

                            <Separator class="bg-gray-100" />

                            <div class="space-y-1.5">
                                <Label for="deskripsi" class="text-xs font-semibold uppercase tracking-wider text-muted-foreground flex items-center gap-1.5">
                                    <Info class="h-3 w-3" /> Keterangan <span class="font-normal normal-case">(Opsional)</span>
                                </Label>
                                <Textarea
                                    id="deskripsi"
                                    v-model="form.deskripsi"
                                    placeholder="Informasi tambahan mengenai unit posyandu ini."
                                    class="min-h-[90px] resize-none"
                                    :class="{ 'border-destructive': form.errors.deskripsi }"
                                />
                                <p v-if="form.errors.deskripsi" class="text-sm text-destructive">{{ form.errors.deskripsi }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-2 pb-6">
                        <Button type="button" variant="ghost" @click="router.get(route('posyandu.index'))" class="text-gray-500">
                            Batalkan Perubahan
                        </Button>
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm shadow-blue-200 transition-all active:scale-95 flex items-center gap-2"
                        >
                            <div v-if="form.processing" class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent" />
                            <Save v-else class="h-4 w-4" />
                            <span class="font-semibold">{{ form.processing ? 'Memperbarui...' : 'Perbarui Data' }}</span>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
