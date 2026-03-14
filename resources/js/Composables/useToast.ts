import { toast as sonnerToast } from 'vue-sonner';

export function useToast() {
    return {
        success: (title: string, description?: string) => {
            sonnerToast.success(title, { description, position: 'top-right' });
        },
        error: (title: string, description?: string) => {
            sonnerToast.error(title, { description, position: 'top-right' });
        },
        warning: (title: string, description?: string) => {
            sonnerToast.warning(title, { description, position: 'top-right' });
        },
        info: (title: string, description?: string) => {
            sonnerToast.info(title, { description, position: 'top-right' });
        },
    };
}
