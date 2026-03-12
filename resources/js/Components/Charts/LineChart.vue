<script setup lang="ts">
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    type ChartData,
    type ChartOptions,
} from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend)

interface Props {
    data: ChartData<'line'>
    options?: ChartOptions<'line'>
    height?: number
}

const props = withDefaults(defineProps<Props>(), {
    height: 300,
})

const defaultOptions: ChartOptions<'line'> = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { position: 'top' },
    },
}
</script>

<template>
    <div :style="{ height: `${height}px` }">
        <Line :data="data" :options="{ ...defaultOptions, ...options }" />
    </div>
</template>
