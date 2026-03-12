<script setup lang="ts">
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import { ArrowUpIcon, ArrowDownIcon } from 'lucide-vue-next';

interface Column {
    key: string;
    label: string;
    sortable?: boolean;
    align?: 'left' | 'center' | 'right';
}

const props = defineProps<{
    columns: Column[];
    data: any[];
    sortKey?: string;
    sortDirection?: 'asc' | 'desc' | '';
    loading?: boolean;
}>();

const emit = defineEmits(['sort']);

const handleSort = (column: Column) => {
    if (!column.sortable) return;
    
    let direction = 'asc';
    if (props.sortKey === column.key) {
        direction = props.sortDirection === 'asc' ? 'desc' : (props.sortDirection === 'desc' ? '' : 'asc');
    }
    
    emit('sort', { key: direction ? column.key : '', direction });
};
</script>

<template>
    <div class="rounded-md border bg-white">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead 
                        v-for="col in columns" 
                        :key="col.key"
                        :class="[
                            col.align === 'right' ? 'text-right' : (col.align === 'center' ? 'text-center' : 'text-left'),
                            col.sortable ? 'cursor-pointer hover:bg-gray-50 select-none' : ''
                        ]"
                        @click="handleSort(col)"
                    >
                        <div class="flex items-center" :class="[col.align === 'right' ? 'justify-end' : (col.align === 'center' ? 'justify-center' : 'justify-start')]">
                            {{ col.label }}
                            <div v-if="col.sortable" class="ml-2 flex-col flex space-y-[1px]">
                                <ArrowUpIcon 
                                    class="h-[10px] w-[10px]" 
                                    :class="sortKey === col.key && sortDirection === 'asc' ? 'text-indigo-600' : 'text-gray-300'" 
                                />
                                <ArrowDownIcon 
                                    class="h-[10px] w-[10px]" 
                                    :class="sortKey === col.key && sortDirection === 'desc' ? 'text-indigo-600' : 'text-gray-300'" 
                                />
                            </div>
                        </div>
                    </TableHead>
                    <TableHead class="text-right" v-if="$slots.actions">Aksi</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-if="loading">
                    <TableCell :colspan="columns.length + ($slots.actions ? 1 : 0)" class="h-24 text-center">
                        <div class="flex items-center justify-center">
                            <span class="h-6 w-6 animate-spin rounded-full border-2 border-indigo-600 border-t-transparent"></span>
                            <span class="ml-2 text-sm text-gray-500">Memuat data...</span>
                        </div>
                    </TableCell>
                </TableRow>
                <TableRow v-else-if="!data || data.length === 0">
                    <TableCell :colspan="columns.length + ($slots.actions ? 1 : 0)" class="h-24 text-center text-sm text-gray-500">
                        Tidak ada data yang ditemukan.
                    </TableCell>
                </TableRow>
                <template v-else>
                    <TableRow v-for="(row, i) in data" :key="row.id || i">
                        <TableCell 
                            v-for="col in columns" 
                            :key="col.key"
                            :class="[col.align === 'right' ? 'text-right' : (col.align === 'center' ? 'text-center' : 'text-left')]"
                        >
                            <slot :name="`cell-${col.key}`" :row="row">
                                {{ row[col.key] }}
                            </slot>
                        </TableCell>
                        <TableCell class="text-right" v-if="$slots.actions">
                            <slot name="actions" :row="row"></slot>
                        </TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>
    </div>
</template>
