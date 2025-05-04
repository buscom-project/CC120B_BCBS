<script setup lang="ts">
import { ref } from 'vue';
import type { PropType } from 'vue';
import { Button } from '@/components/ui/button';

const props = defineProps({
  availableSeats: {
    type: Array as PropType<number[]>,
    required: true
  },
  busCapacity: {
    type: Number,
    required: true
  },
  selectedSeats: {
    type: Array as PropType<string[]>,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'confirm', 'toggle-seat']);

const toggleSeat = (seat: number) => {
  emit('toggle-seat', seat);
};
</script>

<template>
  <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
      <h3 class="text-lg font-semibold mb-4">Select Seats</h3>
      
      <div class="grid grid-cols-4 gap-2 mb-4">
        <div 
          v-for="seat in busCapacity" 
          :key="seat"
          class="h-10 flex items-center justify-center rounded border"
          :class="{
            'bg-green-100 border-green-500': availableSeats.includes(seat),
            'bg-red-100 border-red-500': !availableSeats.includes(seat),
            'bg-primary text-white': selectedSeats.includes(seat.toString())
          }"
          @click="availableSeats.includes(seat) ? toggleSeat(seat) : null"
        >
          {{ seat }}
        </div>
      </div>
      
      <div class="flex justify-end gap-2">
        <Button variant="outline" @click="$emit('close')">Cancel</Button>
        <Button 
          @click="$emit('confirm')" 
          :disabled="selectedSeats.length === 0 || loading"
        >
          {{ loading ? 'Booking...' : `Book ${selectedSeats.length} Seat(s)` }}
        </Button>
      </div>
    </div>
  </div>
</template>