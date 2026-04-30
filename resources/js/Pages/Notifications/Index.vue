<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Bell, CheckCheck, ExternalLink, Clock } from 'lucide-vue-next'

interface Notification {
    id: string
    type: string
    data: { title: string; message: string; url?: string }
    read_at: string | null
    created_at: string
}

const props = defineProps<{
    notifications: { data: Notification[]; links: any; meta?: any }
    unreadCount: number
}>()

function markAsRead(id: string) {
    router.post(route('notifications.read', id), {}, { preserveScroll: true })
}

function markAllAsRead() {
    router.post(route('notifications.read-all'), {}, { preserveScroll: true })
}

function formatDate(date: string) {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    })
}

function handleClick(notif: Notification) {
    if (!notif.read_at) markAsRead(notif.id)
    if (notif.data.url) router.visit(notif.data.url)
}
</script>

<template>
    <Head title="Notifikasi" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Notifikasi</h2>
                    <p class="text-sm text-muted-foreground mt-0.5">
                        {{ unreadCount > 0 ? `${unreadCount} belum dibaca` : 'Semua sudah dibaca' }}
                    </p>
                </div>
                <Button v-if="unreadCount > 0" variant="outline" size="sm" @click="markAllAsRead"
                    class="flex items-center gap-2 rounded-lg border-violet-200 text-violet-700 hover:bg-violet-50">
                    <CheckCheck class="h-4 w-4" />
                    Tandai Semua Dibaca
                </Button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

                <!-- Empty State -->
                <div v-if="notifications.data.length === 0"
                    class="flex flex-col items-center justify-center py-20 gap-4">
                    <div class="p-5 rounded-full bg-gray-100">
                        <Bell class="h-10 w-10 text-gray-300" />
                    </div>
                    <div class="text-center">
                        <p class="font-semibold text-gray-500 text-base">Tidak ada notifikasi</p>
                        <p class="text-sm text-muted-foreground mt-1">Notifikasi jadwal dan aktivitas akan muncul di sini.</p>
                    </div>
                </div>

                <!-- Notifications List -->
                <div v-else class="space-y-2">
                    <Card v-for="notif in notifications.data" :key="notif.id"
                        class="border-none shadow-sm bg-white overflow-hidden cursor-pointer hover:shadow-md transition-all"
                        :class="!notif.read_at ? 'ring-1 ring-violet-200' : ''"
                        @click="handleClick(notif)">
                        <div v-if="!notif.read_at" class="h-0.5 w-full bg-gradient-to-r from-violet-500 to-indigo-500" />
                        <CardContent class="p-4 flex items-start gap-4">
                            <!-- Unread dot -->
                            <div class="mt-1 shrink-0">
                                <div class="h-2.5 w-2.5 rounded-full transition-colors"
                                    :class="notif.read_at ? 'bg-gray-200' : 'bg-violet-500'" />
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-gray-800" :class="!notif.read_at ? '' : 'text-gray-600'">
                                    {{ notif.data.title }}
                                </p>
                                <p class="text-sm text-muted-foreground mt-0.5 leading-relaxed">
                                    {{ notif.data.message }}
                                </p>
                                <p class="text-xs text-muted-foreground mt-2 flex items-center gap-1.5">
                                    <Clock class="h-3 w-3" />
                                    {{ formatDate(notif.created_at) }}
                                </p>
                            </div>

                            <!-- Status badge + link -->
                            <div class="flex items-center gap-2 shrink-0">
                                <Badge v-if="!notif.read_at"
                                    class="bg-violet-50 text-violet-700 border-violet-200 text-xs font-semibold">
                                    Baru
                                </Badge>
                                <Link v-if="notif.data.url" :href="notif.data.url" @click.stop>
                                    <Button variant="ghost" size="icon" class="h-8 w-8 text-gray-400 hover:text-violet-600 hover:bg-violet-50">
                                        <ExternalLink class="h-4 w-4" />
                                    </Button>
                                </Link>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Pagination -->
                <div v-if="(notifications as any).links?.length > 3" class="flex justify-center gap-1 mt-6">
                    <Link v-for="link in (notifications as any).links" :key="link.label"
                        :href="link.url || '#'"
                        class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg px-2.5 text-sm transition-colors"
                        :class="{
                            'bg-violet-600 text-white font-semibold shadow-sm': link.active,
                            'hover:bg-gray-100 text-gray-600': !link.active && link.url,
                            'text-gray-300 pointer-events-none': !link.url,
                        }"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
