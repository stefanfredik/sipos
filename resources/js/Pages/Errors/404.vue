<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Home, ArrowLeft } from 'lucide-vue-next'

defineProps<{
    status?: number
    title?: string
    description?: string
}>()
</script>

<template>
    <Head title="404 - Halaman Tidak Ditemukan" />

    <AuthenticatedLayout>
        <div class="flex items-center justify-center min-h-[80vh]">
            <Card class="w-full max-w-md">
                <CardContent class="pt-6 text-center">
                    <div class="mb-4">
                        <span class="text-9xl font-bold text-primary">404</span>
                    </div>
                    <h1 class="text-2xl font-bold mb-2">Halaman Tidak Ditemukan</h1>
                    <p class="text-muted-foreground mb-6">
                        Maaf, halaman yang Anda cari tidak dapat ditemukan atau mungkin sudah dipindahkan.
                    </p>
                    <div class="flex gap-3 justify-center">
                        <Link :href="route('dashboard')">
                            <Button>
                                <Home class="mr-2 h-4 w-4" />
                                Kembali ke Dashboard
                            </Button>
                        </Link>
                        <Button variant="outline" @click="$router.back()">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Kembali
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
