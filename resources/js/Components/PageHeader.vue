<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRightIcon, HomeIcon } from 'lucide-vue-next';

interface BreadcrumbItem {
    label: string;
    url?: string;
}

defineProps<{
    title: string;
    description?: string;
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <nav v-if="breadcrumbs && breadcrumbs.length > 0" class="flex mb-2" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-2 text-sm text-gray-500">
                    <li>
                        <Link :href="route('dashboard')" class="hover:text-gray-700 transition">
                            <HomeIcon class="h-4 w-4" />
                            <span class="sr-only">Home</span>
                        </Link>
                    </li>
                    <li v-for="(item, index) in breadcrumbs" :key="index">
                        <div class="flex items-center">
                            <ChevronRightIcon class="h-4 w-4 flex-shrink-0 text-gray-400" aria-hidden="true" />
                            <Link v-if="item.url && index !== breadcrumbs.length - 1" :href="item.url" class="ml-2 hover:text-gray-700 transition">
                                {{ item.label }}
                            </Link>
                            <span v-else class="ml-2 font-medium text-gray-900" aria-current="page">
                                {{ item.label }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                {{ title }}
            </h2>
            <p v-if="description" class="mt-1 text-sm text-gray-500">
                {{ description }}
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
             <slot name="actions"></slot>
        </div>
    </div>
</template>
