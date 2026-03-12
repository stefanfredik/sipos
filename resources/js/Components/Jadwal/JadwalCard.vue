<script setup lang="ts">
import { Badge } from '@/components/ui/badge'
import { Card, CardContent } from '@/components/ui/card'
import { Calendar, Clock, MapPin, User } from 'lucide-vue-next'

interface Jadwal {
    id: string
    posyandu: { nama: string }
    kader: { nama: string } | null
    bidan: { nama: string } | null
    tanggal: string
    waktu_mulai: string
    waktu_selesai: string | null
    status: string
    status_label: string
}

defineProps<{
    jadwal: Jadwal
}>()

function getStatusVariant(status: string) {
    switch (status) {
        case 'draft': return 'outline' as const
        case 'validated': return 'secondary' as const
        case 'rejected': return 'destructive' as const
        case 'completed': return 'default' as const
        default: return 'outline' as const
    }
}
</script>

<template>
    <Card class="hover:shadow-md transition-shadow">
        <CardContent class="p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm font-medium">
                    <Calendar class="h-4 w-4 text-muted-foreground" />
                    <span>{{ jadwal.tanggal }}</span>
                </div>
                <Badge :variant="getStatusVariant(jadwal.status)">
                    {{ jadwal.status_label }}
                </Badge>
            </div>

            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <MapPin class="h-4 w-4" />
                <span>{{ jadwal.posyandu.nama }}</span>
            </div>

            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <Clock class="h-4 w-4" />
                <span>{{ jadwal.waktu_mulai }} - {{ jadwal.waktu_selesai || 'Selesai' }}</span>
            </div>

            <div class="flex items-center gap-4 text-xs text-muted-foreground pt-1 border-t">
                <div class="flex items-center gap-1">
                    <User class="h-3 w-3" />
                    <span>Kader: {{ jadwal.kader?.nama || '-' }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <User class="h-3 w-3" />
                    <span>Bidan: {{ jadwal.bidan?.nama || '-' }}</span>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
