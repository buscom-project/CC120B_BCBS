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

const iconMap = {
  Users,
  Bus,
  MapPin,
  CalendarDays,
  Ticket,
  CalendarCheck,
  Armchair,
  UserCircle
};

const props = defineProps<{
    stats: Array<{
        title: string,
        value: string | number,
        change: string,
        trend: 'up' | 'down' | 'neutral',
        icon: keyof typeof iconMap
    }>,
    recentBookings: Array<{
        id: number,
        seat_number: number,
        total_price: number,
        created_at: string,
        bus: {
            bus_name: string
        },
        route: {
            start_location: string,
            end_location: string
        },
        schedule: {
            departure_time: string,
            arrival_time: string
        }
    }>,
    busAvailability: Array<{
        bus_name: string,
        available_seats: number,
        total_seats: number,
        percentage: number
    }>
    }>();


</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2 lg:grid-cols-5">
                <div 
                    v-for="stat in props.stats" :key="stat.title"
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border px-6 py-4"
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
                            <component :is="iconMap[stat.icon]" class="h-6 w-6 text-primary" />
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
                        <div v-for="booking in props.recentBookings" :key="booking.id">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium">{{ booking.bus.bus_name }}</p>
                                    <p class="font-medium">Seat Number {{ booking.seat_number }}</p>
                                </div>
                                <p class="font-medium">₱ {{ booking.total_price }}</p>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                {{ booking.route.start_location }} → {{ booking.route.end_location }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                🕒
                                {{
                                    new Date( booking.schedule.departure_time ).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        hour12: true
                                    })
                                }}
                                -
                                {{
                                    new Date( booking.schedule.arrival_time ).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        hour12: true
                                    })
                                }}
                            </p>
                            <div class="my-4 border-t border-dashed border-gray-400 relative"></div>
                        </div>
                    </div>
                </div>
                
                <div class="relative min-h-[300px] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-6">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <Bus class="h-5 w-5" /> Bus Availability
                    </h3>
                    <div class="space-y-4">
                        <div v-for="bus in props.busAvailability" :key="bus.bus_name">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium">{{ bus.bus_name }}</p>
                                <p class="text-sm text-muted-foreground">{{ bus.available_seats }}/{{ bus.total_seats }} seats</p>
                            </div>
                            <p class="text-sm" :class="bus.percentage > 50 ? 'text-green-500' : 'text-red-500'">
                                {{ bus.percentage }}% available
                            </p>
                        </div>
                        <div class="my-4 border-t border-dashed border-gray-400 relative"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>