<script setup lang="ts">
import { computed, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useToast } from '@/Composables/useToast';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarInset,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    SidebarProvider,
    SidebarRail,
    SidebarSeparator,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import {
    LayoutDashboard,
    Stethoscope,
    CalendarDays,
    Baby,
    HeartPulse,
    PersonStanding,
    Building2,
    Users,
    UserCheck,
    FileBarChart,
    ChevronRight,
    ChevronsUpDown,
    LogOut,
    Settings,
    User,
    Package2,
    Bell,
} from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Toaster } from '@/components/ui/sonner';

const page = usePage();
const user = computed(() => page.props.auth?.user as any);
const role = computed(() => user.value?.role);
const unreadNotifications = computed(
    () => (page.props as any).unreadNotifications || 0,
);
const toast = useToast();

// Flash message handling
const flash = computed(() => page.props.flash as any);

watch(
    () => flash.value?.success,
    (message) => {
        if (message) {
            toast.success('Berhasil', message);
        }
    },
    { immediate: true },
);

watch(
    () => flash.value?.error,
    (message) => {
        if (message) {
            toast.error('Gagal', message);
        }
    },
    { immediate: true },
);

// Helper to get user initials
const userInitials = computed(() => {
    const name = user.value?.nama_user || 'U';
    return name
        .split(' ')
        .map((n: string) => n[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();
});

// Helper to get role label
const roleLabel = computed(() => {
    const labels: Record<string, string> = {
        admin: 'Administrator',
        bidan: 'Bidan',
        kader: 'Kader',
        peserta: 'Peserta',
    };
    return labels[role.value] || role.value;
});

// Check if current route matches
function isActive(routeName: string): boolean {
    try {
        return route().current(routeName) || route().current(routeName + '.*');
    } catch {
        return false;
    }
}

function isActiveAny(...routeNames: string[]): boolean {
    return routeNames.some((name) => isActive(name));
}

// Determine if user has access to a menu
function hasRole(...roles: string[]): boolean {
    return roles.includes(role.value);
}

// Navigation structure
interface NavItem {
    title: string;
    icon: any;
    route?: string;
    active?: boolean;
    roles: string[];
    children?: {
        title: string;
        route: string;
        active?: boolean;
        roles: string[];
    }[];
}

const mainNavItems = computed<NavItem[]>(() => [
    {
        title: 'Dashboard',
        icon: LayoutDashboard,
        route: 'dashboard',
        active: isActive('dashboard'),
        roles: ['admin', 'bidan', 'kader', 'peserta'],
    },
    {
        title: 'Pemeriksaan',
        icon: Stethoscope,
        route: 'pemeriksaan.index',
        active: isActive('pemeriksaan'),
        roles: ['admin', 'bidan', 'kader'],
    },
    {
        title: 'Jadwal Posyandu',
        icon: CalendarDays,
        route: 'jadwal-posyandu.index',
        active: isActive('jadwal-posyandu'),
        roles: ['admin', 'bidan', 'kader', 'peserta'],
    },
]);

const pesertaNavItems = computed<NavItem[]>(() => [
    {
        title: 'Ibu Hamil',
        icon: HeartPulse,
        route: 'ibu-hamil.index',
        active: isActive('ibu-hamil'),
        roles: ['admin', 'bidan', 'kader'],
    },
    {
        title: 'Balita',
        icon: Baby,
        route: 'balita.index',
        active: isActive('balita'),
        roles: ['admin', 'bidan', 'kader'],
    },
    {
        title: 'Lansia',
        icon: PersonStanding,
        route: 'lansia.index',
        active: isActive('lansia'),
        roles: ['admin', 'bidan', 'kader'],
    },
]);

const masterNavItems = computed<NavItem[]>(() => [
    {
        title: 'Posyandu',
        icon: Building2,
        route: 'posyandu.index',
        active: isActive('posyandu'),
        roles: ['admin'],
    },
    {
        title: 'Kader',
        icon: Users,
        route: 'kader.index',
        active: isActive('kader'),
        roles: ['admin'],
    },
    {
        title: 'Bidan',
        icon: UserCheck,
        route: 'bidan.index',
        active: isActive('bidan'),
        roles: ['admin'],
    },
]);

const laporanNavItems = computed<NavItem[]>(() => [
    {
        title: 'Laporan',
        icon: FileBarChart,
        route: 'laporan.index',
        active: isActive('laporan'),
        roles: ['admin', 'bidan', 'kader'],
    },
]);

// Filter items by role
function filterByRole(items: NavItem[]): NavItem[] {
    return items.filter((item) => hasRole(...item.roles));
}
</script>

<template>
    <SidebarProvider>
        <Sidebar collapsible="icon">
            <!-- Header -->
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" as-child>
                            <Link :href="route('dashboard')">
                                <div
                                    class="flex aspect-square size-8 items-center justify-center rounded-lg bg-primary text-primary-foreground"
                                >
                                    <Package2 class="size-4" />
                                </div>
                                <div class="flex flex-col gap-0.5 leading-none">
                                    <span class="font-semibold">SIPOS</span>
                                    <span class="text-xs text-muted-foreground"
                                        >Posyandu Belumbang</span
                                    >
                                </div>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <!-- Menu Utama -->
                <SidebarGroup>
                    <SidebarGroupLabel>Menu Utama</SidebarGroupLabel>
                    <SidebarGroupContent>
                        <SidebarMenu>
                            <SidebarMenuItem
                                v-for="item in filterByRole(mainNavItems)"
                                :key="item.title"
                            >
                                <SidebarMenuButton
                                    as-child
                                    :data-active="item.active"
                                >
                                    <Link :href="route(item.route!)">
                                        <component :is="item.icon" />
                                        <span>{{ item.title }}</span>
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </SidebarGroupContent>
                </SidebarGroup>

                <!-- Data Peserta -->
                <SidebarGroup v-if="filterByRole(pesertaNavItems).length > 0">
                    <SidebarGroupLabel>Data Peserta</SidebarGroupLabel>
                    <SidebarGroupContent>
                        <SidebarMenu>
                            <SidebarMenuItem
                                v-for="item in filterByRole(pesertaNavItems)"
                                :key="item.title"
                            >
                                <SidebarMenuButton
                                    as-child
                                    :data-active="item.active"
                                >
                                    <Link :href="route(item.route!)">
                                        <component :is="item.icon" />
                                        <span>{{ item.title }}</span>
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </SidebarGroupContent>
                </SidebarGroup>

                <!-- Master Data (Admin only) -->
                <SidebarGroup v-if="filterByRole(masterNavItems).length > 0">
                    <SidebarGroupLabel>Master Data</SidebarGroupLabel>
                    <SidebarGroupContent>
                        <SidebarMenu>
                            <SidebarMenuItem
                                v-for="item in filterByRole(masterNavItems)"
                                :key="item.title"
                            >
                                <SidebarMenuButton
                                    as-child
                                    :data-active="item.active"
                                >
                                    <Link :href="route(item.route!)">
                                        <component :is="item.icon" />
                                        <span>{{ item.title }}</span>
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </SidebarGroupContent>
                </SidebarGroup>

                <!-- Laporan -->
                <SidebarGroup v-if="filterByRole(laporanNavItems).length > 0">
                    <SidebarGroupLabel>Laporan</SidebarGroupLabel>
                    <SidebarGroupContent>
                        <SidebarMenu>
                            <SidebarMenuItem
                                v-for="item in filterByRole(laporanNavItems)"
                                :key="item.title"
                            >
                                <SidebarMenuButton
                                    as-child
                                    :data-active="item.active"
                                >
                                    <Link :href="route(item.route!)">
                                        <component :is="item.icon" />
                                        <span>{{ item.title }}</span>
                                    </Link>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </SidebarGroupContent>
                </SidebarGroup>
            </SidebarContent>

            <!-- Footer — User Menu -->
            <SidebarFooter>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <SidebarMenuButton
                                    size="lg"
                                    class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                                >
                                    <Avatar class="h-8 w-8 rounded-lg">
                                        <AvatarFallback
                                            class="rounded-lg bg-primary text-xs text-primary-foreground"
                                        >
                                            {{ userInitials }}
                                        </AvatarFallback>
                                    </Avatar>
                                    <div
                                        class="grid flex-1 text-left text-sm leading-tight"
                                    >
                                        <span class="truncate font-semibold">{{
                                            user?.nama_user
                                        }}</span>
                                        <span
                                            class="truncate text-xs text-muted-foreground"
                                            >{{ roleLabel }}</span
                                        >
                                    </div>
                                    <ChevronsUpDown class="ml-auto size-4" />
                                </SidebarMenuButton>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent
                                class="w-[--reka-popper-anchor-width] min-w-56 rounded-lg"
                                side="bottom"
                                align="end"
                                :side-offset="4"
                            >
                                <DropdownMenuLabel class="p-0 font-normal">
                                    <div
                                        class="flex items-center gap-2 px-1 py-1.5 text-left text-sm"
                                    >
                                        <Avatar class="h-8 w-8 rounded-lg">
                                            <AvatarFallback
                                                class="rounded-lg bg-primary text-xs text-primary-foreground"
                                            >
                                                {{ userInitials }}
                                            </AvatarFallback>
                                        </Avatar>
                                        <div
                                            class="grid flex-1 text-left text-sm leading-tight"
                                        >
                                            <span
                                                class="truncate font-semibold"
                                                >{{ user?.nama_user }}</span
                                            >
                                            <span
                                                class="truncate text-xs text-muted-foreground"
                                                >{{ user?.email }}</span
                                            >
                                        </div>
                                    </div>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem as-child>
                                    <Link :href="route('profile.edit')">
                                        <User class="mr-2 h-4 w-4" />
                                        <span>Profil</span>
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuItem as-child>
                                    <Link :href="route('profile.edit')">
                                        <Settings class="mr-2 h-4 w-4" />
                                        <span>Pengaturan</span>
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem as-child>
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="w-full"
                                    >
                                        <LogOut class="mr-2 h-4 w-4" />
                                        <span>Keluar</span>
                                    </Link>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarFooter>

            <SidebarRail />
        </Sidebar>

        <SidebarInset>
            <!-- Top bar -->
            <header class="flex h-14 shrink-0 items-center gap-2 border-b px-4">
                <SidebarTrigger class="-ml-1" />
                <Separator orientation="vertical" class="mr-2 h-4" />
                <div class="flex-1">
                    <slot name="header" />
                </div>
                <Link :href="route('notifications.index')" class="relative">
                    <Button variant="ghost" size="icon" class="h-8 w-8">
                        <Bell class="h-4 w-4" />
                        <span
                            v-if="unreadNotifications > 0"
                            class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-destructive text-[10px] font-bold text-destructive-foreground"
                        >
                            {{
                                unreadNotifications > 9
                                    ? '9+'
                                    : unreadNotifications
                            }}
                        </span>
                    </Button>
                </Link>
            </header>

            <!-- Page content -->
            <main class="flex-1 p-4 lg:p-6">
                <slot />
            </main>
        </SidebarInset>
    </SidebarProvider>
    <Toaster position="top-right" />
</template>
