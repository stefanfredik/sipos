<script setup lang="ts">
import { DefineComponent } from 'vue';

defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number],
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
    icon: {
        type: Object as () => DefineComponent,
        required: false,
    },
    trend: {
        type: String,
        default: '', // e.g. "+12.5% vs last month"
    },
    trendUp: {
        type: Boolean,
        default: true,
    }
});
</script>

<template>
    <div class="rounded-xl border bg-card text-card-foreground shadow">
        <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
            <h3 class="tracking-tight text-sm font-medium">{{ title }}</h3>
            <component :is="icon" v-if="icon" class="h-4 w-4 text-muted-foreground" />
        </div>
        <div class="p-6 pt-0">
            <div class="text-2xl font-bold">{{ value }}</div>
            <p v-if="description || trend" class="text-xs text-muted-foreground mt-1">
                {{ description }}
                <span v-if="trend" :class="[trendUp ? 'text-emerald-600' : 'text-red-600', 'font-medium']">
                    {{ trend }}
                </span>
            </p>
        </div>
    </div>
</template>
