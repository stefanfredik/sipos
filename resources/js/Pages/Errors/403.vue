<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Home, Lock, ArrowLeft } from 'lucide-vue-next'

defineProps<{
    status?: number
    title?: string
    description?: string
}>()

const goBack = () => {
    window.history.back()
}
</script>

<template>
    <Head title="403 - Akses Ditolak" />

    <AuthenticatedLayout>
        <div class="flex items-center justify-center min-h-[80vh]">
            <Card class="w-full max-w-md border-destructive/20">
                <CardContent class="pt-6 text-center">
                    <div class="mb-4">
                        <Lock class="h-24 w-24 mx-auto text-destructive/50" />
                    </div>
                    <h1 class="text-2xl font-bold mb-2">Akses Ditolak</h1>
                    <p class="text-muted-foreground mb-6">
                        Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda memerlukan akses.
                    </p>
                    <div class="flex gap-3 justify-center">
                        <Link :href="route('dashboard')">
                            <Button>
                                <Home class="mr-2 h-4 w-4" />
                                Kembali ke Dashboard
                            </Button>
                        </Link>
                        <Button variant="outline" @click="goBack">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Kembali
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
