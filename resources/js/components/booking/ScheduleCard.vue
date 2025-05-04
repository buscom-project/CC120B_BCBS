<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Bus, MapPin, Clock, Tag } from 'lucide-vue-next';
import type { PropType } from 'vue';


defineProps({
  schedule: {
    type: Object as PropType<any>,
    required: true
  },
  isSelected: {
    type: Boolean,
    default: false
  }
});


defineEmits(['select']);
</script>

<template>
  <div
    class="relative bg-white border border-gray-300 rounded-xl shadow-md overflow-hidden transition hover:shadow-lg"
    :class="{ 'border-primary ring-2 ring-primary/30': isSelected }"
  >
    <!-- Top: Bus + Price -->
    <div class="p-4 flex justify-between items-center bg-gray-50 border-b border-dashed">
      <div class="flex items-center gap-2 text-xl font-bold text-gray-800">
        <Bus class="w-5 h-5 text-primary" />
        {{ schedule.bus?.bus_name || 'No bus name' }}
      </div>
      <div class="flex items-center gap-1 text-green-600 font-bold text-xl">
        <Tag class="w-5 h-5" />
        ₱{{ schedule.price }}
      </div>
    </div>

    <!-- Middle: Route + Times -->
    <div class="px-4 py-3">
      <div class="flex items-center gap-2 text-sm text-gray-600 font-medium">
        <MapPin class="w-4 h-4 text-primary" />
        <span>{{ schedule.route?.start_location || 'Start' }} → {{ schedule.route?.end_location || 'End' }}</span>
      </div>

      <!-- Dashed divider like ticket tear -->
      <div class="my-4 border-t border-dashed border-gray-400 relative">
        <div class="ml-2 absolute -left-3 top-1/2 transform -translate-y-1/2 w-6 h-6 bg-white rounded-full border border-gray-300"></div>
        <div class="mr-2 absolute -right-3 top-1/2 transform -translate-y-1/2 w-6 h-6 bg-white rounded-full border border-gray-300"></div>
      </div>

      <div class="text-sm space-y-2">
        <div class="flex items-start gap-2">
          <Clock class="w-4 h-4 mt-1 text-gray-500" />
          <div>
            <strong>Departure:</strong><br />
            {{ schedule.schedule?.departure_time
              ? new Date(schedule.schedule.departure_time).toLocaleString('en-PH', {
                  year: 'numeric',
                  month: 'short',
                  day: 'numeric',
                  hour: 'numeric',
                  minute: '2-digit',
                  hour12: true,
                })
              : 'No departure time' }}
          </div>
        </div>

        <div class="flex items-start gap-2">
          <Clock class="w-4 h-4 mt-1 text-gray-500" />
          <div>
            <strong>Arrival:</strong><br />
            {{ schedule.schedule?.arrival_time
              ? new Date(schedule.schedule.arrival_time).toLocaleString('en-PH', {
                  year: 'numeric',
                  month: 'short',
                  day: 'numeric',
                  hour: 'numeric',
                  minute: '2-digit',
                  hour12: true,
                })
              : 'No arrival time' }}
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom: Select Button -->
    <div class="bg-gray-50 px-4 py-3 border-t">
      <Button
        variant="outline"
        class="w-full"
        :class="{ 'border-primary text-primary': isSelected }"
        @click="$emit('select', schedule.id)"
      >
        {{ isSelected ? 'Selected' : 'Select Schedule' }}
      </Button>
    </div>
  </div>
</template>