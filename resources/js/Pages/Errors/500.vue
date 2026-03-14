<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Home, AlertTriangle, ArrowLeft, RefreshCw } from 'lucide-vue-next'

defineProps<{
    status?: number
    title?: string
    description?: string
}>()

const reloadPage = () => {
    window.location.reload()
}
</script>

<template>
    <Head title="500 - Error Server" />

    <AuthenticatedLayout>
        <div class="flex items-center justify-center min-h-[80vh]">
            <Card class="w-full max-w-md border-destructive/20">
                <CardContent class="pt-6 text-center">
                    <div class="mb-4">
                        <AlertTriangle class="h-24 w-24 mx-auto text-destructive/50" />
                    </div>
                    <h1 class="text-2xl font-bold mb-2">Terjadi Kesalahan</h1>
                    <p class="text-muted-foreground mb-6">
                        Maaf, terjadi kesalahan pada server. Tim teknis kami telah diberitahu dan sedang menangani masalah ini.
                    </p>
                    <div class="flex gap-3 justify-center mb-4">
                        <Link :href="route('dashboard')">
                            <Button>
                                <Home class="mr-2 h-4 w-4" />
                                Kembali ke Dashboard
                            </Button>
                        </Link>
                        <Button variant="outline" @click="reloadPage">
                            <RefreshCw class="mr-2 h-4 w-4" />
                            Refresh
                        </Button>
                    </div>
                    <p class="text-xs text-muted-foreground">
                        Jika masalah berlanjut, silakan hubungi administrator sistem.
                    </p>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
