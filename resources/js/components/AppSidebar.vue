<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import NavManage from '@/components/NavManage.vue';
import { type NavItem, type ManageItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Book, CreditCard, Bus, Folder,CalendarCheck, Route, LayoutGrid, UserRoundCog  } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

import { usePage } from '@inertiajs/vue3';
const user = usePage().props.auth.user;

const userNavItems: NavItem[] = user?.role != 'admin' ?[
    {
        title: 'Booking',
        href: 'booking',
        icon: Book,
    },
]: [];

const mainNavItems: NavItem[] = user?.role === 'admin' ? [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Booking',
        href: 'booking',
        icon: Book,
    },
] : [];

const manageNavItems: ManageItem[] = user?.role === 'admin' ? [
    {
        title: 'Payment',
        href: 'payment',
        icon: CreditCard,
    },
    {
        title: 'Bus',
        href: 'bus',
        icon: Bus,
    },
    {
        title: 'Routes',
        href: 'route',
        icon: Route,
    },
    {
        title: 'Schedules',
        href: 'schedule',
        icon: CalendarCheck,
    },
    {
        title: 'Bus Schedules',
        href: 'bus_schedule',
        icon: CalendarCheck,
    },
] : [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain v-if="user?.role != 'admin'"  :items="userNavItems" />
            <NavMain v-if="user?.role === 'admin'" :items="mainNavItems" />
            <NavManage v-if="user?.role === 'admin'" :items="manageNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
