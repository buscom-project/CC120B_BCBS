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
        title: 'Bus',
        href: '/bus',
    },
];

const props = defineProps<{
    buses: Array<{
        id: number;
        bus_name: string;
        total_seats: number;
        created_at: string;
    }>;
}>();

const busName = ref('');
const totalSeats = ref<number | null>(null);

const toast = useToast();

const submit = async () => {
    try {
        const response = await axios.post('/api/buses', {
            bus_name: busName.value,
            total_seats: totalSeats.value,
        });

        toast.success('Bus created successfully!');
        busName.value = '';
        totalSeats.value = null;
    } catch (error: any) {
        if (error.response?.data?.message) {
            toast.error(error.response.data.message);
        } else {
            toast.error('Something went wrong while creating the bus.');
        }
    }
};

const deleteBus = async (id: number) => {
    try {
        await axios.delete(`/api/buses/${id}`);
        toast.success('Bus deleted successfully!');
        // Optionally reload the page or emit an event to re-fetch
        window.location.reload();
    } catch (error) {
        toast.error('Failed to delete the bus.');
        console.error(error);
    }
};
</script>

<template>
    <Head title="Bus" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <!-- table -->
                    <div class="p-4 overflow-auto">
                        <h2 class="text-lg font-semibold mb-4">All Buses</h2>

                        <Table>
                            <TableHeader>
                            <TableRow>
                                <TableHead>ID</TableHead>
                                <TableHead>Bus Name</TableHead>
                                <TableHead>Total Seats</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow
                                    v-for="bus in props.buses"
                                    :key="bus.id"
                                    class="hover:bg-muted"
                                    >
                                    <TableCell>{{ bus.id }}</TableCell>
                                    <TableCell>{{ bus.bus_name }}</TableCell>
                                    <TableCell>{{ bus.total_seats }}</TableCell>
                                    <TableCell>
                                        <Button
                                        variant="destructive"
                                        size="sm"
                                        @click="deleteBus(bus.id)"
                                        >
                                        Delete
                                        </Button>
                                    </TableCell>
                                    </TableRow>

                            </TableBody>
                        </Table>
                    </div>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <!-- form -->
                    <form @submit.prevent="submit" class="p-4 flex flex-col gap-4">
                        <h2 class="text-lg font-semibold">Create Bus</h2>

                        <div>
                            <label class="block text-sm font-medium mb-1">Bus Name</label>
                            <input
                                v-model="busName"
                                type="text"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                                placeholder="e.g., Super Deluxe"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Total Seats</label>
                            <input
                                v-model.number="totalSeats"
                                type="number"
                                min="1"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                                placeholder="e.g., 40"
                                required
                            />
                        </div>

                        <Button type="submit">Create Bus</Button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
