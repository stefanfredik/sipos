import { Config } from 'ziggy-js';

export interface User {
    id: string;
    nama_user: string;
    username: string;
    email: string | null;
    email_verified_at?: string | null;
    role: string;
    is_active: boolean;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
};

declare module 'vue' {
    interface ComponentCustomProperties {
        route: (name?: string, params?: any, absolute?: boolean) => string;
    }
}
