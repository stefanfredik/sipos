<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import {
    Sheet, SheetContent, SheetTrigger
} from '@/components/ui/sheet';
import { BookAIcon, CalendarIcon, ActivityIcon, LayoutDashboardIcon, BellIcon, MenuIcon, Package2Icon, SettingsIcon, UsersIcon } from 'lucide-vue-next';
import { useAuthStore } from '@/stores/useAuthStore';
import { computed } from 'vue';

const authStore = useAuthStore();
const user = computed(() => authStore.user);

const navigation = [
    { name: 'Dashboard', href: route('dashboard'), icon: LayoutDashboardIcon, current: route().current('dashboard') },
    { name: 'Pemeriksaan', href: '#', icon: ActivityIcon, current: false },
    { name: 'Jadwal', href: '#', icon: CalendarIcon, current: false },
    { name: 'Master Data', href: '#', icon: BookAIcon, current: false },
    { name: 'Pengguna', href: '#', icon: UsersIcon, current: false },
];
</script>

<template>
<div class="grid min-h-screen w-full md:grid-cols-[220px_1fr] lg:grid-cols-[280px_1fr]">
    <div class="hidden border-r border-slate-800 bg-slate-950 text-white md:block">
      <div class="flex h-full max-h-screen flex-col gap-2">
        <div class="flex h-14 items-center border-b border-slate-800 px-4 lg:h-[60px] lg:px-6">
          <Link href="/" class="flex items-center gap-2 font-semibold text-white">
            <Package2Icon class="h-6 w-6 text-emerald-400" />
            <span class="">SIPOS</span>
          </Link>
          <Button variant="outline" size="icon" class="ml-auto h-8 w-8 bg-transparent text-slate-300 border-slate-700 hover:bg-slate-800 hover:text-white">
            <BellIcon class="h-4 w-4" />
            <span class="sr-only">Toggle notifications</span>
          </Button>
        </div>
        <div class="flex-1">
          <nav class="grid items-start px-2 text-sm font-medium lg:px-4">
            <Link v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-slate-800 text-white' : 'text-slate-400', 'flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-white hover:bg-slate-800/50']">
              <component :is="item.icon" class="h-4 w-4" />
              {{ item.name }}
            </Link>
          </nav>
        </div>
      </div>
    </div>
    <div class="flex flex-col">
      <header class="flex h-14 items-center gap-4 border-b bg-muted/40 px-4 lg:h-[60px] lg:px-6">
        <Sheet>
          <SheetTrigger as-child>
            <Button variant="outline" size="icon" class="shrink-0 md:hidden">
              <MenuIcon class="h-5 w-5" />
              <span class="sr-only">Toggle navigation menu</span>
            </Button>
          </SheetTrigger>
          <SheetContent side="left" class="flex flex-col bg-slate-950 border-r-slate-800">
            <nav class="grid gap-2 text-lg font-medium">
              <Link href="#" class="flex items-center gap-2 text-lg font-semibold text-white">
                <Package2Icon class="h-6 w-6 text-emerald-400" />
                <span class="">SIPOS</span>
              </Link>
              <Link v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white', 'mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2']">
                <component :is="item.icon" class="h-5 w-5" />
                {{ item.name }}
              </Link>
            </nav>
          </SheetContent>
        </Sheet>
        <div class="w-full flex-1">
          <!-- Add breadcrumb slot here -->
          <slot name="header"></slot>
        </div>
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="secondary" size="icon" class="rounded-full">
              <UsersIcon class="h-5 w-5" />
              <span class="sr-only">Toggle user menu</span>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuLabel>Hi, {{ user?.nama_user || 'User' }}</DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuItem>Profil</DropdownMenuItem>
            <DropdownMenuItem>Pengaturan</DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem as-child>
                <Link :href="route('logout')" method="post" as="button" class="w-full text-left">Logout</Link>
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </header>
      <main class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6 bg-gray-50/50">
        <slot />
      </main>
    </div>
  </div>
</template>
