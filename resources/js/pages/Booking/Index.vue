<script setup lang="ts">
import { ref } from 'vue';
import { useToast } from 'vue-toastification';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/layouts/AppLayout.vue';
import ScheduleCard from '@/components/booking/ScheduleCard.vue';
import SeatSelectionModal from '@/components/booking/SeatSelectionModal.vue';
import BookingList from '@/components/booking/BookingList.vue';
import type { PropType } from 'vue';


const props = defineProps({
  schedules: {
    type: Array as PropType<any[]>,
    required: true
  },
  bookings: {
    type: Array as PropType<any[]>,
    default: () => []
  },
  authUserId: {
    type: Number,
    required: true
  }
});

const toast = useToast();
const selectedScheduleId = ref<number | null>(null);
const selectedSeats = ref<string[]>([]);
const loading = ref(false);
const seatModalOpen = ref(false);
const availableSeats = ref<number[]>([]);
const busCapacity = ref(0);

const fetchAvailableSeats = async (scheduleId: number) => {
  try {
    const response = await axios.get(`/api/schedules/${scheduleId}/seats`);
    availableSeats.value = response.data.available_seats.map(Number);
    busCapacity.value = response.data.bus_capacity;
    seatModalOpen.value = true;
  } catch (error) {
    console.error('Error fetching seats:', error);
    toast.error('Failed to fetch available seats');
  }
};

const toggleSeat = (seat: number) => {
  const seatStr = seat.toString();
  const index = selectedSeats.value.indexOf(seatStr);
  if (index === -1) {
    selectedSeats.value.push(seatStr);
  } else {
    selectedSeats.value.splice(index, 1);
  }
};

const bookNow = async () => {
  if (!selectedScheduleId.value || selectedSeats.value.length === 0) return;

  loading.value = true;

  try {
    const schedule = props.schedules.find(s => s.id === selectedScheduleId.value);
    const totalPrice = schedule.price * selectedSeats.value.length;
    
    await axios.post('/api/bookings', {
      user_id: props.authUserId,
      bus_schedule_id: selectedScheduleId.value,
      seat_numbers: selectedSeats.value,
      total_price: totalPrice
    }, {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    });

    toast.success('Booking successful!');
    router.reload();
  } catch (error: any) {
    console.error('Booking error:', error.response);
    if (error.response?.data?.conflicts) {
      toast.error(`Seats ${error.response.data.conflicts.join(', ')} are already booked`);
    } else if (error.response?.data?.message) {
      toast.error(error.response.data.message);
    } else {
      toast.error('Booking failed. Please try again.');
    }
  } finally {
    loading.value = false;
    seatModalOpen.value = false;
  }
};

const cancelBooking = async (bookingId: number) => {
  try {
    await axios.patch(`/api/bookings/${bookingId}/status`, {
      payment_status: 'cancelled'
    });
    toast.success('Booking cancelled');
    router.reload();
  } catch (error) {
    console.error(error);
    toast.error('Failed to cancel booking');
  }
};
</script>

<template>
  <Head title="Booking" />

  <AppLayout :breadcrumbs="[{ title: 'Booking', href: '/booking' }]">
    <div class="p-4 space-y-6">
      <h2 class="text-2xl font-semibold">Available Bus Schedules</h2>

      <div class="grid gap-4 md:grid-cols-2">
        <ScheduleCard
          v-for="schedule in schedules"
          :key="schedule.id"
          :schedule="schedule"
          :is-selected="selectedScheduleId === schedule.id"
          @select="(id) => { 
            selectedScheduleId = id; 
            fetchAvailableSeats(id); 
          }"
        />
      </div>

      <SeatSelectionModal
        v-if="seatModalOpen"
        :available-seats="availableSeats"
        :bus-capacity="busCapacity"
        :selected-seats="selectedSeats"
        :loading="loading"
        @close="seatModalOpen = false"
        @confirm="bookNow"
        @toggle-seat="toggleSeat"
      />

      <BookingList
        v-if="bookings.length > 0"
        :bookings="bookings"
        @cancel="cancelBooking"
      />
    </div>
  </AppLayout>
</template>