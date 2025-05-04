<script setup lang="ts">
import { Button } from '@/components/ui/button';
import type { PropType } from 'vue';
import { Dialog, DialogTrigger, DialogContent, DialogHeader, DialogFooter, DialogClose } from '@/components/ui/dialog';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import { ref, computed } from 'vue';
import CreditCard from '@/components/payment/CreditCard.vue';
import { Input } from '@/components/ui/input';

// Props
const props = defineProps({
  bookings: {
    type: Array as PropType<any[]>,
    default: () => []
  }
});

// Toast and dialog
const toast = useToast();
const openDialog = ref(false);
const bookingToUpdate = ref<any | null>(null);

// Function to update payment status
const updatePaymentStatus = async () => {
  if (bookingToUpdate.value) {
    try {
      const newStatus = 'paid';
      await axios.patch(`/api/bookings/${bookingToUpdate.value.id}/status`, {
        payment_status: newStatus
      });

      // Update local booking status
      bookingToUpdate.value.payment_status = newStatus;

      openDialog.value = false;
      toast.success('Payment successful!');
    } catch (error) {
      console.error('Error updating payment status:', error);
      toast.error('Failed to update payment status.');
    }
  }
};

// Open payment confirmation dialog
const openUpdateDialog = (booking: any) => {
  bookingToUpdate.value = booking;
  openDialog.value = true;
};

const isFlipped = ref(false);
const cardNumber = ref('');
const cardName = ref('');
const cardExpiry = ref('');
const cardCVV = ref('');

const isFormComplete = computed(() => {
  return (
    cardNumber.value.trim() !== '' &&
    cardName.value.trim() !== '' &&
    cardExpiry.value.trim() !== '' &&
    cardCVV.value.trim() !== ''
  );
});

// Cancel event
const emit = defineEmits(['cancel']);
</script>

<template>
  <div class="mt-8">
    <h3 class="text-xl font-semibold mb-4">Your Bookings</h3>
    <div class="space-y-4">
      <div 
        v-for="booking in bookings" 
        :key="booking.id"
        class="border rounded-lg p-4"
      >
        <div class="flex justify-between items-start">
          <div>
            <div class="font-medium">
              {{ booking.bus_schedule?.bus?.bus_name || 'Bus info not available' }} - Seat {{ booking.seat_number }}
            </div>
            <div class="text-sm text-gray-600">
              {{ booking.bus_schedule?.route?.start_location || 'Origin' }} → 
              {{ booking.bus_schedule?.route?.end_location || 'Destination' }}
            </div>
            <div class="text-sm">
              {{ booking.bus_schedule?.schedule?.departure_time 
                ? new Date(booking.bus_schedule.schedule.departure_time).toLocaleString('en-PH', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                  })
                : 'Departure time not available' }}
            </div>
          </div>
          <div class="text-right">
            <div class="font-bold text-green-700">₱ {{ booking.total_price }}</div>
            <div 
              class="text-sm capitalize"
              :class="{
                'text-yellow-600': booking.payment_status === 'pending',
                'text-green-600': booking.payment_status === 'paid',
                'text-red-600': booking.payment_status === 'cancelled'
              }"
            >
              {{ booking.payment_status }}
            </div>
          </div>
        </div>
        <div class="mt-3 flex gap-2 justify-end">
          <Button 
            v-if="booking.payment_status === 'pending'"
            variant="outline" 
            size="sm"
            @click="$emit('cancel', booking.id)"
          >
            Cancel
          </Button>
          <Button 
            v-if="booking.payment_status === 'pending'"
            @click="openUpdateDialog(booking)"
            size="sm"
          >
            Pay Now
          </Button>
        </div>
      </div>
    </div>

    <!-- Dialog -->
    <Dialog v-model:open="openDialog">
      <DialogTrigger as="div" />
      <DialogContent>
        <DialogHeader>Confirm Payment</DialogHeader>
        <CreditCard 
          :card-number="cardNumber" 
          :card-name="cardName"
          :card-expiry="cardExpiry"
          :card-cvv="cardCVV"
          :is-flipped="isFlipped"
        />

        <div class="mt-4 grid gap-3">
          <Input 
            v-model="cardNumber" 
            placeholder="Card Number" 
            maxlength="19"
          />
          <Input 
            v-model="cardName" 
            placeholder="Cardholder Name" 
          />
          <Input 
            v-model="cardExpiry" 
            placeholder="MM/YY" 
            maxlength="5"
          />
          <Input 
            v-model="cardCVV" 
            placeholder="CVV"
            maxlength="3"
            @focus="isFlipped = true"
            @blur="isFlipped = false"
          />
        </div>
        <DialogFooter class="flex justify-between mt-4">
          <DialogClose @click="openDialog = false">Cancel</DialogClose>
          <Button 
            @click="updatePaymentStatus"
            :disabled="!isFormComplete"
          >
            Pay Now
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>