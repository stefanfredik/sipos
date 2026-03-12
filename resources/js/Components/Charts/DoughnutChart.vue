<script setup lang="ts">
import { Doughnut } from 'vue-chartjs'
import {
    Chart as ChartJS,
    ArcElement,
    Tooltip,
    Legend,
    type ChartData,
    type ChartOptions,
} from 'chart.js'

ChartJS.register(ArcElement, Tooltip, Legend)

interface Props {
    data: ChartData<'doughnut'>
    options?: ChartOptions<'doughnut'>
    height?: number
}

const props = withDefaults(defineProps<Props>(), {
    height: 300,
})

const defaultOptions: ChartOptions<'doughnut'> = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { position: 'bottom' },
    },
}
</script>

<template>
    <div :style="{ height: `${height}px` }">
        <Doughnut :data="data" :options="{ ...defaultOptions, ...options }" />
    </div>
</template>
