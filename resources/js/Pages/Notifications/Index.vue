<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Bell, CheckCheck, Calendar, ExternalLink } from 'lucide-vue-next'

interface Notification {
    id: string
    type: string
    data: {
        title: string
        message: string
        url?: string
    }
    read_at: string | null
    created_at: string
}

const props = defineProps<{
    notifications: {
        data: Notification[]
        links: any
        meta?: any
    }
    unreadCount: number
}>()

function markAsRead(id: string) {
    router.post(route('notifications.read', id))
}

function markAllAsRead() {
    router.post(route('notifications.read-all'))
}

function formatDate(date: string) {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}
</script>

<template>
    <Head title="Notifikasi" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <span class="text-foreground font-medium">Notifikasi</span>
            </div>
        </template>

        <div class="max-w-3xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Notifikasi</h1>
                    <p class="text-sm text-muted-foreground">
                        {{ unreadCount > 0 ? `${unreadCount} belum dibaca` : 'Semua sudah dibaca' }}
                    </p>
                </div>
                <Button v-if="unreadCount > 0" variant="outline" size="sm" @click="markAllAsRead" class="gap-2">
                    <CheckCheck class="h-4 w-4" />
                    Tandai Semua Dibaca
                </Button>
            </div>

            <div v-if="notifications.data.length === 0" class="text-center py-12">
                <Bell class="h-12 w-12 text-muted-foreground mx-auto mb-4" />
                <h3 class="text-lg font-medium text-muted-foreground">Tidak ada notifikasi</h3>
                <p class="text-sm text-muted-foreground">Notifikasi akan muncul di sini.</p>
            </div>

            <div v-else class="space-y-2">
                <Card
                    v-for="notif in notifications.data"
                    :key="notif.id"
                    :class="{ 'border-primary/20 bg-primary/5': !notif.read_at }"
                    class="cursor-pointer transition-colors hover:bg-muted/50"
                    @click="!notif.read_at && markAsRead(notif.id)"
                >
                    <CardContent class="flex items-start gap-4 p-4">
                        <div class="mt-0.5">
                            <div
                                class="h-2 w-2 rounded-full"
                                :class="notif.read_at ? 'bg-transparent' : 'bg-primary'"
                            />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm">{{ notif.data.title }}</p>
                            <p class="text-sm text-muted-foreground mt-0.5">{{ notif.data.message }}</p>
                            <p class="text-xs text-muted-foreground mt-2">{{ formatDate(notif.created_at) }}</p>
                        </div>
                        <Link v-if="notif.data.url" :href="notif.data.url" @click.stop>
                            <Button variant="ghost" size="icon" class="h-8 w-8">
                                <ExternalLink class="h-4 w-4" />
                            </Button>
                        </Link>
                    </CardContent>
                </Card>
            </div>

            <!-- Pagination -->
            <div v-if="notifications.links" class="flex justify-center gap-1">
                <Link
                    v-for="link in (notifications as any).links"
                    :key="link.label"
                    :href="link.url || '#'"
                    class="px-3 py-1 text-sm rounded-md"
                    :class="{
                        'bg-primary text-primary-foreground': link.active,
                        'hover:bg-muted': !link.active && link.url,
                        'text-muted-foreground': !link.url,
                    }"
                    v-html="link.label"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
