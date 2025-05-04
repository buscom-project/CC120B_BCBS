<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import {
  Table,
  TableHeader,
  TableRow,
  TableHead,
  TableBody,
  TableCell,
} from '@/components/ui/table'; 

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Schedule',
        href: '/schedule',
    },
];


const props = defineProps<{
  schedules: Array<{
    id: number;
    departure_time: string;
    arrival_time: string;
    availability: string;
  }>;
}>();

const departureTime = ref('');
const arrivalTime = ref('');
const availability = ref('available');

const toast = useToast();

const submit = async () => {
    try {
        const response = await axios.post('/api/schedules', {
            departure_time: departureTime.value,
            arrival_time: arrivalTime.value,
            availability: availability.value,
        });

        toast.success('Schedule created successfully!');
        departureTime.value = '';
        arrivalTime.value = '';
        availability.value = 'available';
    } catch (error: any) {
        if (error.response?.data?.message) {
            toast.error(error.response.data.message);
        } else {
            toast.error('Something went wrong while creating the schedule.');
        }
    }
};

const deleteSchedule = async (id: number) => {
  try {
    await axios.delete(`/api/schedules/${id}`);
    toast.success('Schedule deleted successfully!');
    window.location.reload(); // or manually update props if needed
  } catch (error: any) {
    toast.error(error.response?.data?.message ?? 'Error deleting schedule.');
  }
};
</script>

<template>
    <Head title="Schedule" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <!-- table -->
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                    <h2 class="text-lg font-semibold mb-4">All Schedules</h2>

                    <Table>
                        <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Departure</TableHead>
                            <TableHead>Arrival</TableHead>
                            <TableHead>Availability</TableHead>
                            <TableHead>Actions</TableHead>
                        </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="schedule in props.schedules"
                                :key="schedule.id"
                                class="hover:bg-muted"
                                >
                                <TableCell>{{ schedule.id }}</TableCell>
                                <TableCell>
                                    {{
                                    new Date(schedule.departure_time).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        hour12: true
                                    })
                                    }}
                                </TableCell>
                                <TableCell>
                                    {{
                                    new Date(schedule.arrival_time).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        hour12: true
                                    })
                                    }}
                                </TableCell>
                                <TableCell>{{ schedule.availability }}</TableCell>
                                <TableCell>
                                    <Button variant="destructive" @click="deleteSchedule(schedule.id)">Delete</Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <!-- form -->
                    <form @submit.prevent="submit" class="p-4 flex flex-col gap-4">
                        <h2 class="text-lg font-semibold">Create Schedule</h2>

                        <div>
                            <label class="block text-sm font-medium mb-1">Departure Time</label>
                            <input
                                v-model="departureTime"
                                type="datetime-local"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Arrival Time</label>
                            <input
                                v-model="arrivalTime"
                                type="datetime-local"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Availability</label>
                            <select
                                v-model="availability"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                                required
                            >
                                <option value="available">Available</option>
                                <option value="full">Full</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <Button type="submit">Create Schedule</Button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
