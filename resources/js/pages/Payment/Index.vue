<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Table, TableHeader, TableBody, TableHead, TableRow, TableCell } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Dialog, DialogTrigger, DialogContent, DialogHeader, DialogFooter, DialogClose } from '@/components/ui/dialog';
import { useToast } from 'vue-toastification';
import axios from 'axios';

import { ref, computed } from 'vue';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '../../components/ui/tabs';

const activeTab = ref('pending');

const pendingBookings = computed(() =>
    props.bookings.filter((b) => b.payment_status.toLowerCase() === 'pending')
);
const paidBookings = computed(() =>
    props.bookings.filter((b) => b.payment_status.toLowerCase() === 'paid')
);

const breadcrumbs = [
    { title: 'Payment', href: '/payment' },
];

// Access bookings prop using defineProps
const props = defineProps<{
    bookings: Array<{
        id: number;
        seat_number: string;
        payment_status: string;
        user: { name: string };
        bus_schedule: {
            price: number;
            bus: { bus_name: string };
            route: {
                start_location: string;
                end_location: string;
            };
            schedule: {
                departure_time: string;
                arrival_time: string;
            };
        };
    }>;
}>();

// Toast for success/error messages
const toast = useToast();

// State for controlling the dialog
const openDialog = ref(false);
const bookingToUpdate = ref<number | null>(null);

// Function to update the payment status
const updatePaymentStatus = async () => {
    if (bookingToUpdate.value !== null) {
        try {
            const newStatus = "paid"; 
            await axios.patch(`/api/bookings/${bookingToUpdate.value}/status`, {
                payment_status: newStatus
            });

            // Optionally update local booking state here if necessary
            const booking = props.bookings.find(b => b.id === bookingToUpdate.value);
            if (booking) {
                booking.payment_status = newStatus;
            }

            // Close the dialog and show success notification
            openDialog.value = false;
            toast.success('Payment status updated successfully!');
        } catch (error) {
            console.error('Error updating payment status:', error);
            toast.error('Error updating payment status!');
        }
    }
};

// Function to handle the dialog confirmation
const openUpdateDialog = (bookingId: number) => {
    bookingToUpdate.value = bookingId;
    openDialog.value = true;
};
</script>

<template>
    <Head title="Payment" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 rounded-xl p-4">
            <Tabs v-model="activeTab" class="w-full">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Bookings</h2>
                    <TabsList>
                        <TabsTrigger value="pending">Pending</TabsTrigger>
                        <TabsTrigger value="paid">Paid</TabsTrigger>
                    </TabsList>
                </div>

                <!-- Pending Bookings -->
                <TabsContent value="pending">
                    <Table>
                    <TableHeader>
                        <TableRow>
                        <TableHead>User</TableHead>
                        <TableHead>Seat</TableHead>
                        <TableHead>Bus | Route</TableHead>
                        <TableHead>Schedule</TableHead>
                        <TableHead>Price</TableHead>
                        <TableHead>Payment</TableHead>
                        <TableHead class="text-right">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="booking in pendingBookings" :key="booking.id">
                            <TableCell>{{ booking.user?.name }}</TableCell>
                            <TableCell>{{ booking.seat_number }}</TableCell>
                            <TableCell>
                                {{ booking.bus_schedule?.bus?.bus_name }} <br>
                                {{ booking.bus_schedule?.route?.start_location }} → {{ booking.bus_schedule?.route?.end_location }}
                            </TableCell>
                            <TableCell>
                                <div class="flex flex-col">
                                    <div class="text-sm">
                                        {{
                                            new Date(booking.bus_schedule?.schedule?.departure_time)
                                                .toLocaleString('en-PH', {
                                                    month: 'short',
                                                    day: 'numeric',
                                                    hour: 'numeric',
                                                    minute: '2-digit',
                                                    hour12: true
                                                })
                                        }}
                                    </div>
                                    <div class="text-gray-500"> 
                                        <span class="text-center">↓</span>
                                    </div>
                                    <div class="text-sm">
                                        {{
                                            new Date(booking.bus_schedule?.schedule?.arrival_time)
                                                .toLocaleString('en-PH', {
                                                    month: 'short',
                                                    day: 'numeric',
                                                    hour: 'numeric',
                                                    minute: '2-digit',
                                                    hour12: true
                                                })
                                        }}
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell>₱{{ Number(booking.bus_schedule?.price ?? 0).toFixed(2) }}</TableCell>
                            <TableCell class="capitalize">
                                <span 
                                    :class="{
                                        'bg-yellow-100 text-yellow-800': booking.payment_status === 'pending',
                                        'bg-green-100 text-green-800': booking.payment_status === 'paid',
                                        'bg-red-100 text-red-800': booking.payment_status === 'Failed',
                                        'bg-gray-100 text-gray-800': booking.payment_status === 'Cancelled'
                                    }"
                                    class="inline-block rounded-full px-3 py-1 text-sm"
                                >
                                    {{ booking.payment_status }}
                                </span>
                            </TableCell>

                            <TableCell v-if="booking.payment_status == 'pending'" class="text-right">
                                <Button size="sm" @click="openUpdateDialog(booking.id)">
                                    Update Status
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                    </Table>
                </TabsContent>

                <!-- Paid Bookings -->
                <TabsContent value="paid">
                    <Table>
                    <TableHeader>
                        <TableRow>
                        <TableHead>User</TableHead>
                        <TableHead>Seat</TableHead>
                        <TableHead>Bus | Route</TableHead>
                        <TableHead>Schedule</TableHead>
                        <TableHead>Price</TableHead>
                        <TableHead>Payment</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="booking in paidBookings" :key="booking.id">
                            <TableCell>{{ booking.user?.name }}</TableCell>
                            <TableCell>{{ booking.seat_number }}</TableCell>
                            <TableCell>
                                {{ booking.bus_schedule?.bus?.bus_name }} <br>
                                {{ booking.bus_schedule?.route?.start_location }} → {{ booking.bus_schedule?.route?.end_location }}
                            </TableCell>
                            <TableCell>
                                <div class="flex flex-col">
                                    <div class="text-sm">
                                        {{
                                            new Date(booking.bus_schedule?.schedule?.departure_time)
                                                .toLocaleString('en-PH', {
                                                    month: 'short',
                                                    day: 'numeric',
                                                    hour: 'numeric',
                                                    minute: '2-digit',
                                                    hour12: true
                                                })
                                        }}
                                    </div>
                                    <div class="text-gray-500"> 
                                        <span class="text-center">↓</span>
                                    </div>
                                    <div class="text-sm">
                                        {{
                                            new Date(booking.bus_schedule?.schedule?.arrival_time)
                                                .toLocaleString('en-PH', {
                                                    month: 'short',
                                                    day: 'numeric',
                                                    hour: 'numeric',
                                                    minute: '2-digit',
                                                    hour12: true
                                                })
                                        }}
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell>₱{{ Number(booking.bus_schedule?.price ?? 0).toFixed(2) }}</TableCell>
                            <TableCell class="capitalize">
                                <span 
                                    :class="{
                                        'bg-yellow-100 text-yellow-800': booking.payment_status === 'pending',
                                        'bg-green-100 text-green-800': booking.payment_status === 'paid',
                                        'bg-red-100 text-red-800': booking.payment_status === 'Failed',
                                        'bg-gray-100 text-gray-800': booking.payment_status === 'Cancelled'
                                    }"
                                    class="inline-block rounded-full px-3 py-1 text-sm font-medium"
                                >
                                    {{ booking.payment_status }}
                                </span>
                            </TableCell>

                            <TableCell v-if="booking.payment_status == 'pending'" class="text-right">
                                <Button @click="openUpdateDialog(booking.id)">
                                    Update Status
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                    </Table>
                </TabsContent>
                </Tabs>

            <!-- Confirmation Dialog using ShadCN Dialog component -->
            <Dialog v-model:open="openDialog">
                <DialogTrigger as="div" />
                <DialogContent>
                    <DialogHeader>Confirm Payment Status Update</DialogHeader>
                    <div class="p-4 text-center">
                        Are you sure you want to update the payment status for this booking?
                    </div>
                    <DialogFooter class="flex justify-between">
                        <DialogClose @click="openDialog = false">Cancel</DialogClose>
                        <Button @click="updatePaymentStatus">Confirm</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
