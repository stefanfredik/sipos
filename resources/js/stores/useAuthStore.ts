import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { usePage } from '@inertiajs/vue3';
import { PageProps } from '@inertiajs/core';

export const useAuthStore = defineStore('auth', () => {
    const page = usePage<PageProps>();
    
    const user = computed(() => page.props.auth.user);
    const roles = computed(() => [page.props.auth.user?.role].filter(Boolean) as string[]);
    // Roles map directly and permissions are unused but kept for interface compatibility
    const permissions = computed(() => [] as string[]);

    function hasRole(role: string | string[]): boolean {
        if (!roles.value) return false;
        
        if (Array.isArray(role)) {
            return role.some((r) => roles.value.includes(r));
        }
        
        return roles.value.includes(role);
    }

    function hasPermission(permission: string | string[]): boolean {
        if (!permissions.value) return false;
        
        if (hasRole('admin')) return true; // Admin has all permissions usually
        
        if (Array.isArray(permission)) {
            return permission.some((p) => permissions.value.includes(p));
        }
        
        return permissions.value.includes(permission);
    }

    return {
        user,
        roles,
        permissions,
        hasRole,
        hasPermission,
    };
});
