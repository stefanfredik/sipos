<script setup lang="ts">
import { Badge } from '@/components/ui/badge';

const props = defineProps({
    status: {
        type: String,
        required: true,
    },
});

const statusVariants: Record<string, "default" | "secondary" | "destructive" | "outline"> = {
    draft: 'secondary',
    validated: 'default',
    rejected: 'destructive',
    completed: 'default',
    active: 'default',
    inactive: 'destructive',
    L: 'default',
    P: 'secondary',
};

const labels: Record<string, string> = {
    draft: 'Draft',
    validated: 'Divalidasi',
    rejected: 'Ditolak',
    completed: 'Selesai',
    active: 'Aktif',
    inactive: 'Nonaktif',
    L: 'Laki-Laki',
    P: 'Perempuan',
};

</script>

<template>
    <Badge :variant="statusVariants[status] || 'outline'">
        {{ labels[status] || status }}
    </Badge>
</template>
