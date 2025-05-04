<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
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

// Props passed from Laravel
const props = defineProps<{
    buses: Array<any>,
    routes: Array<any>,
    schedules: Array<any>,
    busSchedules: Array<any> 
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Bus Schedule',
        href: '/bus_schedule',
    },
];

const busId = ref<number | null>(null);
const routeId = ref<number | null>(null);
const scheduleId = ref<number | null>(null);
const price = ref<number | null>(null); // Track the manually entered price

const buses = ref(props.buses);
const routes = ref(props.routes);
const schedules = ref(props.schedules);

const toast = useToast();

const submit = async () => {
    try {
        await axios.post('/api/bus-schedules', {
            bus_id: busId.value,
            route_id: routeId.value,
            schedule_id: scheduleId.value,
            price: price.value,
        });

        toast.success('Bus schedule assigned successfully!');
        busId.value = null;
        routeId.value = null;
        scheduleId.value = null;
        price.value = null; // Reset price after submission
    } catch (error: any) {
        if (error.response?.data?.message) {
            toast.error(error.response.data.message);
        } else {
            toast.error('Error assigning bus schedule.');
        }
    }
};

// Delete Bus Schedule
const deleteBusSchedule = async (id: string) => {
    try {
        await axios.delete(`/api/bus-schedules/${id}`);
        toast.success('Bus schedule deleted successfully!');
    } catch (error: any) {
        toast.error(error.response?.data?.message ?? 'Error deleting bus schedule.');
    }
};
</script>

<template>
    <Head title="Bus Schedule" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Updated grid with 2:1 ratio -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-[2fr_1fr]">
                <!-- Table column (wider) -->
                <div class="relative overflow-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="p-4 overflow-auto">
                    <h2 class="text-lg font-semibold mb-4">Bus Schedules</h2>
                    <DataTable class="w-full">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Bus</TableHead>
                                    <TableHead>Route</TableHead>
                                    <TableHead>Schedule</TableHead>
                                    <TableHead>Price</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow
                                    v-for="busSchedule in props.busSchedules"
                                    :key="busSchedule.id"
                                >
                                    <TableCell>{{ busSchedule.bus.bus_name }}</TableCell>
                                    <TableCell>
                                        {{ busSchedule.route.start_location }} → {{ busSchedule.route.end_location }}
                                    </TableCell>
                                    <TableCell>
                                    {{
                                    new Date(busSchedule.schedule.departure_time).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        hour12: true
                                    })
                                    }}
                                    <br><span> → </span><br>
                                    {{
                                    new Date(busSchedule.schedule.arrival_time).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        hour12: true
                                    })
                                    }}
                                </TableCell>
                                    
                                    <TableCell>₱ {{ busSchedule.price }}</TableCell>
                                    <TableCell class="text-right">
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            @click="deleteBusSchedule(busSchedule.id)"
                                        >
                                            Delete
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </DataTable>
                    </div>
                </div>

                <!-- Form column (narrower) -->
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-6">
                    <form @submit.prevent="submit" class="flex flex-col gap-4">
                        <h2 class="text-lg font-semibold">Assign Bus Schedule</h2>

                        <div>
                            <label class="block text-sm font-medium mb-1">Bus</label>
                            <select v-model="busId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                                <option disabled value="">Select a bus</option>
                                <option v-for="bus in buses" :key="bus.id" :value="bus.id">
                                    {{ bus.bus_name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Route</label>
                            <select v-model="routeId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                                <option disabled value="">Select a route</option>
                                <option v-for="route in routes" :key="route.id" :value="route.id">
                                    {{ route.start_location }} → {{ route.end_location }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Schedule</label>
                            <select v-model="scheduleId" class="w-full border border-gray-300 rounded px-3 py-2" required>
                                <option disabled value="">Select a schedule</option>
                                <option v-for="schedule in schedules" :key="schedule.id" :value="schedule.id">
                                    {{ new Date(schedule.departure_time).toLocaleString() }} → {{ new Date(schedule.arrival_time).toLocaleString() }}
                                </option>
                            </select>
                        </div>

                        <!-- Manual price entry -->
                        <div>
                            <label class="block text-sm font-medium mb-1">price</label>
                            <input
                                v-model="price"
                                type="number"
                                min="0"
                                step="0.01"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                                placeholder="Enter price amount"
                                required
                            />
                        </div>

                        <Button type="submit">Assign</Button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
