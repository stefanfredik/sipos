import { useAuthStore } from '@/stores/useAuthStore';

export function usePermission() {
    const authStore = useAuthStore();

    return {
        can: (permission: string | string[]) => authStore.hasPermission(permission),
        isRole: (role: string | string[]) => authStore.hasRole(role),
    };
}
