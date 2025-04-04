<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button'
import {
  Users,
  Bus,
  Armchair,
  CalendarCheck,
  MapPin,
  CalendarDays,
  Ticket,
  UserCircle
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Dummy data
const stats = [
  { title: 'Customers', value: '248', icon: Users, change: '+12%', trend: 'up' },
  { title: 'Buses', value: '42', icon: Bus, change: '+3', trend: 'up' },
  { title: 'Seats', value: '260', icon: Armchair, change: '84%', trend: 'down' },
  { title: 'Availability', value: '76%', icon: CalendarCheck, change: '+5%', trend: 'up' },
  { title: 'Routes', value: '18', icon: MapPin, change: '2 new', trend: 'up' },
  { title: 'Today', value: 'Apr 4', icon: CalendarDays, change: '3 bookings', trend: 'neutral' },
  { title: 'Bookings', value: '324', icon: Ticket, change: '+24', trend: 'up' },
  { title: 'Account', value: 'Admin', icon: UserCircle, change: 'Active', trend: 'neutral' },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div 
                    v-for="stat in stats" 
                    :key="stat.title"
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-6"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">
                                {{ stat.title }}
                            </p>
                            <p class="text-2xl font-bold mt-1">
                                {{ stat.value }}
                            </p>
                            <p 
                                class="text-xs mt-1 flex items-center"
                                :class="{
                                    'text-green-500': stat.trend === 'up',
                                    'text-red-500': stat.trend === 'down',
                                    'text-gray-500': stat.trend === 'neutral'
                                }"
                            >
                                <span v-if="stat.trend === 'up'">↑</span>
                                <span v-else-if="stat.trend === 'down'">↓</span>
                                <span v-else>•</span>
                                {{ stat.change }}
                            </p>
                        </div>
                        <div class="rounded-lg p-3 bg-primary/10">
                            <component :is="stat.icon" class="h-6 w-6 text-primary" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid gap-4 md:grid-cols-2">
                <div class="relative min-h-[300px] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-6">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <Ticket class="h-5 w-5" /> Recent Bookings
                    </h3>
                    <div class="space-y-4">
                        <div v-for="i in 2" :key="i" class="border-b pb-3 last:border-0 last:pb-0">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium">Booking #B{{ 1000 + i }}</p>
                                    <p class="text-sm text-muted-foreground">Route {{ i }} → Route {{ i+2 }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">₱ {{ 2000 + (i*5) }}</p>
                                    <p class="text-sm text-muted-foreground">May {{ 10 + i }}, 2023</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative min-h-[300px] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-6">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <Bus class="h-5 w-5" /> Bus Availability
                    </h3>
                    <div class="space-y-4">
                        <div v-for="i in 2" :key="i" class="border-b pb-3 last:border-0 last:pb-0">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium">Bus #B{{ 200 + i }}</p>
                                    <p class="text-sm text-muted-foreground">{{ ['Express', 'Deluxe', 'Standard', 'VIP', 'Economy'][i-1] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">{{ 40 - (i*5) }}/40 seats</p>
                                    <p class="text-sm" :class="(40 - (i*5)) > 15 ? 'text-green-500' : 'text-red-500'">
                                        {{ Math.round(((40 - (i*5))/40)*100) }}% available
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>