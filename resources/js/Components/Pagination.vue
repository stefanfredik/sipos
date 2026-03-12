<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

const props = defineProps<{
    links: PaginationLink[];
    from?: number;
    to?: number;
    total?: number;
}>();

const showPagination = computed(() => {
    return props.links && props.links.length > 3;
});
</script>

<template>
    <div v-if="showPagination" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <Link v-if="links[0].url" :href="links[0].url as string" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Previous
            </Link>
            <span v-else class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
                Previous
            </span>

            <Link v-if="links[links.length - 1].url" :href="links[links.length - 1].url as string" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Next
            </Link>
             <span v-else class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
                Next
            </span>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p v-if="total !== undefined" class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ from }}</span> sampai <span class="font-medium">{{ to }}</span> dari <span class="font-medium">{{ total }}</span> hasil
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <template v-for="(link, key) in links" :key="key">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            v-html="link.label"
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold border"
                            :class="[
                                link.active ? 'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0',
                                key === 0 ? 'rounded-l-md' : '',
                                key === links.length - 1 ? 'rounded-r-md' : '',
                            ]"
                        />
                         <span
                            v-else
                            v-html="link.label"
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold border text-gray-400 cursor-not-allowed"
                            :class="[
                                key === 0 ? 'rounded-l-md' : '',
                                key === links.length - 1 ? 'rounded-r-md' : '',
                            ]"
                        />
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>
