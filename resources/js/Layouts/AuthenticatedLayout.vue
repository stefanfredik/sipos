<script setup lang="ts">
import { computed, watch, ref, h } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useToast } from '@/Composables/useToast';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
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
const user = computed(() => (page.props as any).auth?.user);
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
);

watch(
    () => flash.value?.error,
    (message) => {
        if (message) {
            toast.error('Gagal', message);
        }
    },
);

watch(
    () => (page.props as any).status,
    (status) => {
        if (status) {
            toast.info('Info', status);
        }
    },
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

// Mobile menu state
const mobileMenuOpen = ref(false);

// Render menu items for mobile
function renderMenuItem(item: NavItem) {
    return h(
        'a',
        {
            href: route(item.route!),
            class: `flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors hover:bg-slate-900 hover:text-white ${item.active ? 'bg-slate-900 text-white font-medium' : 'text-slate-400'}`,
        },
        [
            h(item.icon, { class: 'h-4 w-4' }),
            item.title,
        ]
    );
}
</script>

<template>
    <SidebarProvider>
        <Sidebar collapsible="icon" class="bg-slate-950 text-white border-r-slate-800">
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
                                    <span class="font-semibold text-white">SIPOS</span>
                                    <span class="text-xs text-slate-400"
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
                    <SidebarGroupLabel class="text-slate-500 font-bold uppercase tracking-wider">Menu Utama</SidebarGroupLabel>
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
                    <SidebarGroupLabel class="text-slate-500 font-bold uppercase tracking-wider">Data Peserta</SidebarGroupLabel>
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
                    <SidebarGroupLabel class="text-slate-500 font-bold uppercase tracking-wider">Master Data</SidebarGroupLabel>
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
                    <SidebarGroupLabel class="text-slate-500 font-bold uppercase tracking-wider">Laporan</SidebarGroupLabel>
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
                                    class="data-[state=open]:bg-slate-900 data-[state=open]:text-white hover:bg-slate-900 hover:text-white"
                                >
                                    <Avatar class="h-8 w-8 rounded-lg">
                                        <AvatarFallback
                                            class="rounded-lg bg-primary text-xs text-primary-foreground"
                                        >
                                            {{ userInitials }}
                                        </AvatarFallback>
                                    </Avatar>
                                    <div
                                        class="grid flex-1 text-left text-sm leading-tight text-white"
                                    >
                                        <span class="truncate font-semibold">{{
                                            user?.nama_user
                                        }}</span>
                                        <span
                                            class="truncate text-xs text-slate-400"
                                            >{{ roleLabel }}</span
                                        >
                                    </div>
                                    <ChevronsUpDown class="ml-auto size-4 text-slate-400" />
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
                <!-- Mobile menu trigger -->
                <Sheet v-model:open="mobileMenuOpen">
                    <SheetTrigger as-child>
                        <Button variant="ghost" size="icon" class="md:hidden h-8 w-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="left" class="w-72 p-0 bg-slate-950 text-white border-r-slate-800">
                        <SheetHeader class="border-b border-slate-800 px-4 py-3">
                            <SheetTitle class="flex items-center gap-2 text-white">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-white">
                                    <Package2 class="h-4 w-4" />
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-white">SIPOS</div>
                                    <div class="text-xs text-slate-400">Posyandu Belumbang</div>
                                </div>
                            </SheetTitle>
                        </SheetHeader>
                        <div class="overflow-y-auto p-4">
                            <!-- Main Menu -->
                            <div class="mb-4">
                                <div class="mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Menu Utama</div>
                                <div class="space-y-1">
                                    <a v-for="item in filterByRole(mainNavItems)" :key="item.title" :href="route(item.route!)" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors hover:bg-slate-900 hover:text-white" :class="item.active ? 'bg-slate-900 text-white font-medium' : 'text-slate-400'">
                                        <component :is="item.icon" class="h-4 w-4" />
                                        <span>{{ item.title }}</span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Peserta Menu -->
                            <div v-if="filterByRole(pesertaNavItems).length > 0" class="mb-4">
                                <div class="mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Data Peserta</div>
                                <div class="space-y-1">
                                    <a v-for="item in filterByRole(pesertaNavItems)" :key="item.title" :href="route(item.route!)" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors hover:bg-slate-900 hover:text-white" :class="item.active ? 'bg-slate-900 text-white font-medium' : 'text-slate-400'">
                                        <component :is="item.icon" class="h-4 w-4" />
                                        <span>{{ item.title }}</span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Master Data (Admin only) -->
                            <div v-if="filterByRole(masterNavItems).length > 0" class="mb-4">
                                <div class="mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</div>
                                <div class="space-y-1">
                                    <a v-for="item in filterByRole(masterNavItems)" :key="item.title" :href="route(item.route!)" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors hover:bg-slate-900 hover:text-white" :class="item.active ? 'bg-slate-900 text-white font-medium' : 'text-slate-400'">
                                        <component :is="item.icon" class="h-4 w-4" />
                                        <span>{{ item.title }}</span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Laporan -->
                            <div v-if="filterByRole(laporanNavItems).length > 0" class="mb-4">
                                <div class="mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Laporan</div>
                                <div class="space-y-1">
                                    <a v-for="item in filterByRole(laporanNavItems)" :key="item.title" :href="route(item.route!)" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors hover:bg-slate-900 hover:text-white" :class="item.active ? 'bg-slate-900 text-white font-medium' : 'text-slate-400'">
                                        <component :is="item.icon" class="h-4 w-4" />
                                        <span>{{ item.title }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </SheetContent>
                </Sheet>
                
                <!-- Desktop sidebar trigger -->
                <SidebarTrigger class="-ml-1 hidden md:flex" />
                <Separator orientation="vertical" class="mr-2 h-4 hidden md:block" />
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
