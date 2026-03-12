<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
import { Card, CardContent, CardHeader } from '@/components/ui/card';

interface Props {
    count?: number;
}

withDefaults(defineProps<Props>(), {
    count: 4,
});
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <Card v-for="i in count" :key="i">
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                <Skeleton class="h-4 w-24" />
                <Skeleton class="h-4 w-4 rounded" />
            </CardHeader>
            <CardContent>
                <Skeleton class="h-8 w-16 mb-2" />
                <Skeleton class="h-3 w-32" />
            </CardContent>
        </Card>
    </div>
</template>
