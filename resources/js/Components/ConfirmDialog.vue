<script setup lang="ts">
import { useConfirm } from '@/Composables/useConfirm';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';

const { isOpen, options, isProcessing, confirm, cancel } = useConfirm();
</script>

<template>
    <AlertDialog :open="isOpen" @update:open="cancel">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ options.title }}</AlertDialogTitle>
                <AlertDialogDescription>
                    {{ options.message }}
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel :disabled="isProcessing" @click="cancel">
                    {{ options.cancelText }}
                </AlertDialogCancel>
                <!-- Use a custom action button to support loading state and variants properly -->
                <Button 
                    :variant="options.variant" 
                    :disabled="isProcessing" 
                    @click="confirm"
                >
                    <span v-if="isProcessing" class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-primary border-t-transparent"></span>
                    {{ options.confirmText }}
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
