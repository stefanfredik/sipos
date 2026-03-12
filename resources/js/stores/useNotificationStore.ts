import { defineStore } from 'pinia';
import { ref } from 'vue';

export type NotificationType = 'success' | 'error' | 'warning' | 'info';

export interface AppNotification {
    id: string;
    type: NotificationType;
    message: string;
    title?: string;
    duration?: number;
}

export const useNotificationStore = defineStore('notification', () => {
    const notifications = ref<AppNotification[]>([]);

    function add(notification: Omit<AppNotification, 'id'>) {
        const id = Math.random().toString(36).substring(2, 9);
        const newNotification = { ...notification, id };
        
        notifications.value.push(newNotification);

        if (notification.duration !== 0) {
            setTimeout(() => {
                remove(id);
            }, notification.duration || 5000);
        }

        return id;
    }

    function remove(id: string) {
        const index = notifications.value.findIndex((n) => n.id === id);
        if (index > -1) {
            notifications.value.splice(index, 1);
        }
    }

    function success(message: string, title?: string, duration?: number) {
        return add({ type: 'success', message, title, duration });
    }

    function error(message: string, title?: string, duration?: number) {
        return add({ type: 'error', message, title, duration });
    }

    function warning(message: string, title?: string, duration?: number) {
        return add({ type: 'warning', message, title, duration });
    }

    function info(message: string, title?: string, duration?: number) {
        return add({ type: 'info', message, title, duration });
    }

    return {
        notifications,
        add,
        remove,
        success,
        error,
        warning,
        info,
    };
});
