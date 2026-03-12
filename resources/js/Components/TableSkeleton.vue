<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Card, CardContent, CardHeader } from '@/components/ui/card';

interface Props {
    rows?: number;
    columns?: number;
    showHeader?: boolean;
}

withDefaults(defineProps<Props>(), {
    rows: 5,
    columns: 5,
    showHeader: true,
});
</script>

<template>
    <Card>
        <CardHeader v-if="showHeader">
            <Skeleton class="h-6 w-48" />
        </CardHeader>
        <CardContent>
            <Table>
                <TableHeader v-if="showHeader">
                    <TableRow>
                        <TableHead v-for="i in columns" :key="`head-${i}`">
                            <Skeleton class="h-4 w-full" />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="i in rows" :key="`row-${i}`">
                        <TableCell v-for="j in columns" :key="`cell-${i}-${j}`">
                            <Skeleton class="h-4 w-full" />
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </CardContent>
    </Card>
</template>
