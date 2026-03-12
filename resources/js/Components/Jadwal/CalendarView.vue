<script setup lang="ts">
import { computed, ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

interface JadwalEvent {
    id: string
    tanggal: string
    posyandu: { nama: string }
    status: string
    status_label: string
    waktu_mulai: string
}

const props = defineProps<{
    events: JadwalEvent[]
}>()

const emit = defineEmits<{
    (e: 'select-date', date: string): void
    (e: 'select-event', id: string): void
}>()

const currentDate = ref(new Date())

const currentMonth = computed(() => currentDate.value.getMonth())
const currentYear = computed(() => currentDate.value.getFullYear())

const monthLabel = computed(() => {
    return currentDate.value.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })
})

const daysInMonth = computed(() => {
    return new Date(currentYear.value, currentMonth.value + 1, 0).getDate()
})

const firstDayOfWeek = computed(() => {
    const day = new Date(currentYear.value, currentMonth.value, 1).getDay()
    return day === 0 ? 6 : day - 1 // Monday = 0
})

const calendarDays = computed(() => {
    const days: Array<{ date: number; events: JadwalEvent[] }> = []
    for (let i = 1; i <= daysInMonth.value; i++) {
        const dateStr = `${currentYear.value}-${String(currentMonth.value + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`
        const dayEvents = props.events.filter(e => e.tanggal === dateStr)
        days.push({ date: i, events: dayEvents })
    }
    return days
})

const isToday = (date: number) => {
    const today = new Date()
    return date === today.getDate() && currentMonth.value === today.getMonth() && currentYear.value === today.getFullYear()
}

function prevMonth() {
    currentDate.value = new Date(currentYear.value, currentMonth.value - 1, 1)
}

function nextMonth() {
    currentDate.value = new Date(currentYear.value, currentMonth.value + 1, 1)
}

function getStatusColor(status: string) {
    switch (status) {
        case 'draft': return 'bg-muted'
        case 'validated': return 'bg-primary'
        case 'rejected': return 'bg-destructive'
        case 'completed': return 'bg-green-500'
        default: return 'bg-muted'
    }
}
</script>

<template>
    <Card>
        <CardHeader class="pb-3">
            <div class="flex items-center justify-between">
                <CardTitle class="text-base">{{ monthLabel }}</CardTitle>
                <div class="flex items-center gap-1">
                    <Button variant="ghost" size="icon" class="h-8 w-8" @click="prevMonth">
                        <ChevronLeft class="h-4 w-4" />
                    </Button>
                    <Button variant="ghost" size="icon" class="h-8 w-8" @click="nextMonth">
                        <ChevronRight class="h-4 w-4" />
                    </Button>
                </div>
            </div>
        </CardHeader>
        <CardContent>
            <!-- Day headers -->
            <div class="grid grid-cols-7 gap-px mb-1">
                <div v-for="day in ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']" :key="day"
                    class="text-center text-xs font-medium text-muted-foreground py-2">
                    {{ day }}
                </div>
            </div>

            <!-- Calendar grid -->
            <div class="grid grid-cols-7 gap-px">
                <!-- Empty cells for offset -->
                <div v-for="_ in firstDayOfWeek" :key="'empty-' + _" class="h-12" />

                <!-- Day cells -->
                <div
                    v-for="day in calendarDays"
                    :key="day.date"
                    class="h-12 p-1 rounded-md text-sm cursor-pointer hover:bg-muted/50 transition-colors"
                    :class="{ 'bg-primary/10 font-semibold': isToday(day.date) }"
                    @click="emit('select-date', `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day.date).padStart(2, '0')}`)"
                >
                    <div class="text-xs" :class="{ 'text-primary font-bold': isToday(day.date) }">
                        {{ day.date }}
                    </div>
                    <div class="flex gap-0.5 mt-0.5">
                        <div
                            v-for="event in day.events.slice(0, 3)"
                            :key="event.id"
                            class="h-1.5 w-1.5 rounded-full"
                            :class="getStatusColor(event.status)"
                            :title="event.posyandu.nama"
                            @click.stop="emit('select-event', event.id)"
                        />
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="flex items-center gap-4 mt-4 pt-3 border-t text-xs text-muted-foreground">
                <div class="flex items-center gap-1.5">
                    <div class="h-2 w-2 rounded-full bg-muted" />
                    <span>Draft</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="h-2 w-2 rounded-full bg-primary" />
                    <span>Tervalidasi</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="h-2 w-2 rounded-full bg-destructive" />
                    <span>Ditolak</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="h-2 w-2 rounded-full bg-green-500" />
                    <span>Selesai</span>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
