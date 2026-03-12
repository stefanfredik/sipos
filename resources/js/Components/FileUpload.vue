<script setup lang="ts">
import { ref, computed } from 'vue'
import { Upload, X } from 'lucide-vue-next'
import { Button } from '@/Components/ui/button'

interface Props {
    modelValue?: File | null
    currentUrl?: string | null
    accept?: string
    maxSize?: number // KB
    label?: string
}

const props = withDefaults(defineProps<Props>(), {
    accept: 'image/jpeg,image/png,image/webp',
    maxSize: 2048,
    label: 'Upload Foto',
})

const emit = defineEmits<{
    'update:modelValue': [file: File | null]
    error: [message: string]
}>()

const fileInput = ref<HTMLInputElement>()
const previewUrl = ref<string | null>(null)
const isDragging = ref(false)

const displayUrl = computed(() => previewUrl.value || props.currentUrl)

function handleFileSelect(event: Event) {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0] || null
    processFile(file)
}

function handleDrop(event: DragEvent) {
    isDragging.value = false
    const file = event.dataTransfer?.files?.[0] || null
    processFile(file)
}

function processFile(file: File | null) {
    if (!file) return

    // Validate size
    if (file.size > props.maxSize * 1024) {
        emit('error', `Ukuran file maksimal ${props.maxSize} KB`)
        return
    }

    // Validate type
    if (!props.accept.split(',').some((type) => file.type === type.trim())) {
        emit('error', 'Format file tidak didukung')
        return
    }

    // Create preview
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value)
    }
    previewUrl.value = URL.createObjectURL(file)
    emit('update:modelValue', file)
}

function removeFile() {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value)
        previewUrl.value = null
    }
    if (fileInput.value) {
        fileInput.value.value = ''
    }
    emit('update:modelValue', null)
}

function openPicker() {
    fileInput.value?.click()
}
</script>

<template>
    <div>
        <input
            ref="fileInput"
            type="file"
            :accept="accept"
            class="hidden"
            @change="handleFileSelect"
        />

        <!-- Preview -->
        <div v-if="displayUrl" class="relative inline-block">
            <img
                :src="displayUrl"
                :alt="label"
                class="h-32 w-32 rounded-lg border object-cover"
            />
            <Button
                type="button"
                variant="destructive"
                size="icon"
                class="absolute -right-2 -top-2 h-6 w-6 rounded-full"
                @click="removeFile"
            >
                <X class="h-3 w-3" />
            </Button>
        </div>

        <!-- Upload area -->
        <div
            v-else
            class="flex h-32 w-32 cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed transition-colors"
            :class="isDragging ? 'border-primary bg-primary/5' : 'border-muted-foreground/25 hover:border-primary/50'"
            @click="openPicker"
            @dragover.prevent="isDragging = true"
            @dragleave="isDragging = false"
            @drop.prevent="handleDrop"
        >
            <Upload class="mb-2 h-6 w-6 text-muted-foreground" />
            <span class="text-xs text-muted-foreground">{{ label }}</span>
        </div>
    </div>
</template>
