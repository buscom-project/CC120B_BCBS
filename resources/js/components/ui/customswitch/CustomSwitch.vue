<template>
    <button
      :class="[
        'relative inline-flex h-[18px] w-10 items-center rounded-full transition-colors',
        checked ? 'bg-green-500' : 'bg-gray-300',
      ]"
      @click="toggle"
    >
      <span
        :class="[
          'inline-block h-3 w-3 transform rounded-full bg-white transition-transform',
          checked ? 'translate-x-6' : 'translate-x-1',
        ]"
      />
    </button>
  </template>
  
  <script setup lang="ts">
  import { ref, watch } from "vue";
  
  const props = defineProps<{
    checked: boolean;
  }>();
  
  const emit = defineEmits<{
    (event: "update:checked", value: boolean): void;
  }>();
  
  const isChecked = ref(props.checked);
  
  const toggle = () => {
    isChecked.value = !isChecked.value;
    emit("update:checked", isChecked.value);
  };
  
  watch(
    () => props.checked,
    (newValue) => {
      isChecked.value = newValue;
    }
  );
  </script>