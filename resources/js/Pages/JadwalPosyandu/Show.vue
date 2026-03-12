<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Separator } from '@/components/ui/separator'
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog'
import { Textarea } from '@/components/ui/textarea'
import {
    ArrowLeft,
    Calendar,
    Clock,
    MapPin,
    Users,
    UserCheck,
    Edit,
    Trash2,
    CheckCircle2,
    XCircle,
    FileCheck,
} from 'lucide-vue-next'
import { ref, computed } from 'vue'

interface Jadwal {
    id: string
    posyandu: { id: string; nama: string; lokasi?: string } | null
    kader: { id: string; nama: string } | null
    bidan: { id: string; nama: string } | null
    tanggal: string
    waktu_mulai: string
    waktu_selesai: string | null
    status: string
    status_label: string
    catatan_bidan: string | null
}

const props = defineProps<{
    jadwal: { data: Jadwal }
}>()

const page = usePage()
const userRole = computed(() => (page.props.auth as any)?.user?.role)
const canValidate = computed(() => ['admin', 'bidan'].includes(userRole.value) && props.jadwal.data.status === 'draft')
const canComplete = computed(() => ['admin', 'bidan'].includes(userRole.value) && props.jadwal.data.status === 'validated')
const canEdit = computed(() => ['admin', 'bidan', 'kader'].includes(userRole.value))

const catatanBidan = ref('')

const statusVariant = computed(() => {
    const map: Record<string, string> = {
        draft: 'secondary',
        validated: 'default',
        rejected: 'destructive',
        completed: 'outline',
    }
    return map[props.jadwal.data.status] || 'secondary'
})

function formatDate(date: string) {
    return new Date(date).toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    })
}

function validateJadwal() {
    router.post(route('jadwal-posyandu.validate', props.jadwal.data.id), {
        bidan_id: (page.props.auth as any)?.user?.bidan?.id || '',
        catatan_bidan: catatanBidan.value,
    })
}

function rejectJadwal() {
    if (!catatanBidan.value) return
    router.post(route('jadwal-posyandu.reject', props.jadwal.data.id), {
        catatan_bidan: catatanBidan.value,
    })
}

function completeJadwal() {
    router.post(route('jadwal-posyandu.complete', props.jadwal.data.id))
}

function deleteJadwal() {
    router.delete(route('jadwal-posyandu.destroy', props.jadwal.data.id))
}
</script>

<template>
    <Head title="Detail Jadwal Posyandu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <Link :href="route('jadwal-posyandu.index')" class="hover:text-foreground">Jadwal Posyandu</Link>
                <span>/</span>
                <span class="text-foreground">Detail</span>
            </div>
        </template>

        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('jadwal-posyandu.index')">
                        <Button variant="outline" size="icon">
                            <ArrowLeft class="h-4 w-4" />
                        </Button>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold">Detail Jadwal Posyandu</h1>
                        <p class="text-sm text-muted-foreground">{{ jadwal.data.posyandu?.nama }}</p>
                    </div>
                </div>
                <Badge :variant="statusVariant as any">{{ jadwal.data.status_label }}</Badge>
            </div>

            <!-- Detail Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Informasi Jadwal</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3">
                            <MapPin class="h-5 w-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Posyandu</p>
                                <p class="font-semibold">{{ jadwal.data.posyandu?.nama }}</p>
                                <p v-if="jadwal.data.posyandu?.lokasi" class="text-sm text-muted-foreground">{{ jadwal.data.posyandu.lokasi }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <Calendar class="h-5 w-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Tanggal</p>
                                <p class="font-semibold">{{ formatDate(jadwal.data.tanggal) }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <Clock class="h-5 w-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Waktu</p>
                                <p class="font-semibold">{{ jadwal.data.waktu_mulai }} {{ jadwal.data.waktu_selesai ? `- ${jadwal.data.waktu_selesai}` : '' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <Users class="h-5 w-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Kader</p>
                                <p class="font-semibold">{{ jadwal.data.kader?.nama || '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <UserCheck class="h-5 w-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Bidan</p>
                                <p class="font-semibold">{{ jadwal.data.bidan?.nama || 'Belum ditentukan' }}</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="jadwal.data.catatan_bidan">
                        <Separator class="my-4" />
                        <p class="text-sm font-medium text-muted-foreground mb-1">Catatan Bidan</p>
                        <p>{{ jadwal.data.catatan_bidan }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Actions Card (Bidan: validate/reject) -->
            <Card v-if="canValidate">
                <CardHeader>
                    <CardTitle>Tindakan Validasi</CardTitle>
                    <CardDescription>Jadwal ini menunggu validasi dari bidan.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div>
                        <label class="text-sm font-medium">Catatan (opsional untuk validasi, wajib untuk penolakan)</label>
                        <Textarea v-model="catatanBidan" placeholder="Tulis catatan..." class="mt-1" />
                    </div>
                    <div class="flex gap-3">
                        <Button @click="validateJadwal" class="gap-2">
                            <CheckCircle2 class="h-4 w-4" />
                            Validasi Jadwal
                        </Button>
                        <Button variant="destructive" @click="rejectJadwal" :disabled="!catatanBidan" class="gap-2">
                            <XCircle class="h-4 w-4" />
                            Tolak Jadwal
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Complete Action -->
            <Card v-if="canComplete">
                <CardHeader>
                    <CardTitle>Selesaikan Jadwal</CardTitle>
                    <CardDescription>Tandai jadwal ini sebagai selesai setelah kegiatan dilaksanakan.</CardDescription>
                </CardHeader>
                <CardContent>
                    <Button @click="completeJadwal" class="gap-2">
                        <FileCheck class="h-4 w-4" />
                        Tandai Selesai
                    </Button>
                </CardContent>
            </Card>

            <!-- Footer Actions -->
            <div class="flex justify-between">
                <Link :href="route('jadwal-posyandu.index')">
                    <Button variant="outline">Kembali</Button>
                </Link>
                <div v-if="canEdit" class="flex gap-2">
                    <Link :href="route('jadwal-posyandu.edit', jadwal.data.id)">
                        <Button variant="outline" class="gap-2">
                            <Edit class="h-4 w-4" />
                            Edit
                        </Button>
                    </Link>
                    <AlertDialog>
                        <AlertDialogTrigger as-child>
                            <Button variant="destructive" class="gap-2">
                                <Trash2 class="h-4 w-4" />
                                Hapus
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Hapus Jadwal?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Jadwal posyandu ini akan dihapus. Tindakan ini tidak dapat dibatalkan.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Batal</AlertDialogCancel>
                                <AlertDialogAction @click="deleteJadwal">Hapus</AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
