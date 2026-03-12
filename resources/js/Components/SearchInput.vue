<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { SearchIcon, XIcon } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Cari...'
    },
    debounce: {
        type: Number,
        default: 300
    }
});

const emit = defineEmits(['update:modelValue', 'search']);

const localSearch = ref(props.modelValue);

const hasValue = computed(() => localSearch.value && localSearch.value.length > 0);

const performSearch = useDebounceFn(() => {
    emit('update:modelValue', localSearch.value);
    emit('search', localSearch.value);
}, props.debounce);

watch(localSearch, () => {
    performSearch();
});

watch(() => props.modelValue, (newVal) => {
    if (newVal !== localSearch.value) {
        localSearch.value = newVal;
    }
});

const clearSearch = () => {
    localSearch.value = '';
    emit('update:modelValue', '');
    emit('search', '');
};
</script>

<template>
    <div class="relative w-full max-w-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <SearchIcon class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </div>
        <Input
            type="text"
            autofocus
            autocomplete="off"
            v-model="localSearch"
            :placeholder="placeholder"
            class="pl-10 pr-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm w-full"
        />
        <div v-if="hasValue" class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <button @click="clearSearch" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                <XIcon class="h-4 w-4" aria-hidden="true" />
            </button>
        </div>
    </div>
</template>
