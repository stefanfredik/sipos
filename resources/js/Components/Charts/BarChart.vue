<script setup lang="ts">
import { Bar } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
    type ChartData,
    type ChartOptions,
} from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

interface Props {
    data: ChartData<'bar'>
    options?: ChartOptions<'bar'>
    height?: number
}

const props = withDefaults(defineProps<Props>(), {
    height: 300,
})

const defaultOptions: ChartOptions<'bar'> = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { position: 'top' },
    },
}
</script>

<template>
    <div :style="{ height: `${height}px` }">
        <Bar :data="data" :options="{ ...defaultOptions, ...options }" />
    </div>
</template>
