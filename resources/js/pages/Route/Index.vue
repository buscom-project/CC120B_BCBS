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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Route',
        href: '/route',
    },
];

const props = defineProps<{
  routes: Array<{
    id: number;
    start_location: string;
    end_location: string;
    created_at: string;
  }>;
}>();


const startLocation = ref('');
const endLocation = ref('');
const toast = useToast();

const submit = async () => {
    try {
        await axios.post('/api/routes', {
            start_location: startLocation.value,
            end_location: endLocation.value,
        });

        toast.success('Route created successfully!');
        startLocation.value = '';
        endLocation.value = '';
    } catch (error: any) {
        toast.error(error.response?.data?.message ?? 'Error creating route.');
    }
};

const deleteRoute = async (id: number) => {
  try {
    await axios.delete(`/api/routes/${id}`);
    toast.success('Route deleted successfully!');
    window.location.reload(); // Or better: use Inertia visit or Vue state
  } catch (error: any) {
    toast.error(error.response?.data?.message ?? 'Failed to delete route.');
  }
};


</script>

<template>
    <Head title="Route" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                    <!-- table -->
                    <h2 class="text-lg font-semibold mb-4">Route List</h2>
                    <Table>
                        <TableHeader>
                            <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Start</TableHead>
                            <TableHead>End</TableHead>
                            <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                            v-for="route in props.routes"
                            :key="route.id"
                            class="hover:bg-muted"
                            >
                            <TableCell>{{ route.id }}</TableCell>
                            <TableCell>{{ route.start_location }}</TableCell>
                            <TableCell>{{ route.end_location }}</TableCell>
                            <TableCell>
                                <Button
                                variant="destructive"
                                size="sm"
                                @click="deleteRoute(route.id)"
                                >
                                Delete
                                </Button>
                            </TableCell>
                            </TableRow>
                        </TableBody>
                        </Table>

                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <!-- form -->
                    <form @submit.prevent="submit" class="p-4 flex flex-col gap-4">
                        <h2 class="text-lg font-semibold">Create Route</h2>

                        <div>
                            <label class="block text-sm font-medium mb-1">Start Location</label>
                            <input
                                v-model="startLocation"
                                type="text"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                                placeholder="e.g., Manila"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">End Location</label>
                            <input
                                v-model="endLocation"
                                type="text"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                                placeholder="e.g., Baguio"
                                required
                            />
                        </div>

                        <Button type="submit">Create Route</Button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
