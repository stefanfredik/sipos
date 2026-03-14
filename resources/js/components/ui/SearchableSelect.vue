<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Check, ChevronDown, X } from 'lucide-vue-next'
import { cn } from '@/lib/utils'

interface Option {
    id: string
    label: string
}

const props = withDefaults(defineProps<{
    modelValue?: string
    options: Option[]
    placeholder?: string
    label?: string
    error?: string
    disabled?: boolean
}>(), {
    placeholder: 'Pilih...',
    disabled: false,
})

const emit = defineEmits<{
    'update:modelValue': [value: string]
}>()

const search = ref('')
const open = ref(false)

const filteredOptions = computed(() => {
    if (!search.value) return props.options
    return props.options.filter(opt => 
        opt.label.toLowerCase().includes(search.value.toLowerCase())
    )
})

const selectedOption = computed(() => {
    return props.options.find(opt => opt.id === props.modelValue)
})

function selectOption(option: Option) {
    emit('update:modelValue', option.id)
    open.value = false
    search.value = ''
}

function clearSelection() {
    emit('update:modelValue', '')
    open.value = false
    search.value = ''
}

watch(open, (isOpen) => {
    if (!isOpen) {
        search.value = ''
    }
})
</script>

<template>
    <div class="space-y-2">
        <Label v-if="label">{{ label }}</Label>
        <Popover v-model:open="open">
            <PopoverTrigger as-child>
                <Button
                    variant="outline"
                    :class="cn(
                        'w-full justify-between border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
                        error && 'border-destructive focus:ring-destructive'
                    )"
                    :disabled="disabled"
                >
                    <span v-if="selectedOption" class="truncate">
                        {{ selectedOption.label }}
                    </span>
                    <span v-else class="text-muted-foreground">
                        {{ placeholder }}
                    </span>
                    <div class="flex items-center gap-1">
                        <X 
                            v-if="selectedOption" 
                            class="h-4 w-4 opacity-50 hover:opacity-100" 
                            @click.stop="clearSelection"
                        />
                        <ChevronDown class="h-4 w-4 opacity-50" />
                    </div>
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-[--radix-popover-trigger-width] p-0" align="start">
                <div class="border-b p-2">
                    <Input
                        v-model="search"
                        placeholder="Cari..."
                        class="h-8 border-0 focus-visible:ring-0 focus-visible:ring-offset-0"
                    />
                </div>
                <div class="max-h-[200px] overflow-y-auto p-1">
                    <div
                        v-for="option in filteredOptions"
                        :key="option.id"
                        class="relative flex cursor-pointer select-none items-center rounded-sm py-1.5 pl-8 pr-2 text-sm outline-none hover:bg-accent hover:text-accent-foreground data-[active=true]:bg-accent data-[active=true]:text-accent-foreground"
                        :class="{ 'bg-accent': option.id === modelValue }"
                        @click="selectOption(option)"
                    >
                        <span class="absolute left-2 flex h-3.5 w-3.5 items-center justify-center">
                            <Check v-if="option.id === modelValue" class="h-4 w-4" />
                        </span>
                        <span class="truncate">{{ option.label }}</span>
                    </div>
                    <div v-if="filteredOptions.length === 0" class="p-2 text-center text-sm text-muted-foreground">
                        Tidak ada hasil
                    </div>
                </div>
            </PopoverContent>
        </Popover>
        <p v-if="error" class="text-sm text-destructive">{{ error }}</p>
    </div>
</template>
