import { ref } from 'vue';

interface ConfirmOptions {
    title?: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
    variant?: 'default' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link';
    onConfirm: () => void | Promise<void>;
    onCancel?: () => void;
}

export function useConfirm() {
    const isOpen = ref(false);
    const options = ref<ConfirmOptions>({
        title: 'Konfirmasi',
        message: 'Apakah Anda yakin?',
        confirmText: 'Ya',
        cancelText: 'Batal',
        variant: 'destructive',
        onConfirm: () => {},
        onCancel: () => {},
    });

    const isProcessing = ref(false);

    function requireConfirmation(newOptions: ConfirmOptions) {
        options.value = {
            title: 'Konfirmasi',
            confirmText: 'Ya',
            cancelText: 'Batal',
            variant: 'destructive',
            ...newOptions,
        };
        isOpen.value = true;
    }

    async function confirm() {
        if (isProcessing.value) return;
        
        isProcessing.value = true;
        try {
            await options.value.onConfirm();
        } finally {
            isProcessing.value = false;
            isOpen.value = false;
        }
    }

    function cancel() {
        if (options.value.onCancel) {
            options.value.onCancel();
        }
        isOpen.value = false;
    }

    return {
        isOpen,
        options,
        isProcessing,
        requireConfirmation,
        confirm,
        cancel,
    };
}
