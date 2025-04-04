<template>
    <Sheet>
      <!-- Sheet Trigger -->
      <SheetTrigger @click="openDialog">
        <UserRoundPen class="w-4 h-4 mr-2 text-blue-500 dark:text-blue-400 hover:text-blue-700" />
      </SheetTrigger>
  
      <!-- Sheet Content -->
      <SheetContent class="text-gray-900 dark:text-gray-200">
        <SheetHeader>
          <SheetTitle>Edit User</SheetTitle>
          <SheetDescription>Modify user details and save changes.</SheetDescription>
        </SheetHeader>
  
        <!-- Edit User Form -->
        <form @submit.prevent="updateUser">
          <div class="space-y-4">
            <!-- Name Field -->
            <label class="block">
              <span class="text-gray-700 dark:text-gray-300">Name</span>
              <input
                v-model="userData.name"
                type="text"
                class="input dark:bg-gray-700 dark:text-white"
                required
              />
            </label>
  
            <!-- Email Field -->
            <label class="block">
              <span class="text-gray-700 dark:text-gray-300">Email</span>
              <input
                v-model="userData.email"
                type="email"
                class="input dark:bg-gray-700 dark:text-white"
                required
              />
            </label>
  
            <!-- Role Field -->
            <label class="block">
              <span class="text-gray-700 dark:text-gray-300">Role</span>
              <select
                v-model="userData.role"
                class="input dark:bg-gray-700 dark:text-white"
              >
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </select>
            </label>
  
            <!-- Status Field -->
            <label class="block">
              <span class="text-gray-700 dark:text-gray-300">Status</span>
              <select
                v-model="userData.status"
                class="input dark:bg-gray-700 dark:text-white"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </label>
  
            <!-- Save Changes Button -->
            <Button type="submit" class="w-full">Save Changes</Button>
          </div>
        </form>
      </SheetContent>
    </Sheet>
  </template>
  
  <script setup lang="ts">
  import { ref } from "vue";
  import {
    Sheet,
    SheetTrigger,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetDescription,
  } from "@/components/ui/sheet"; // Adjust the import path as needed
  import { Button } from "@/components/ui/button"; // Adjust the import path as needed
  import { UserRoundPen } from "lucide-vue-next"; // Icon for the trigger
  import axios from "axios";
  import { useToast } from "vue-toastification";
  
  // Props
  const props = defineProps<{
    user: {
      id: number;
      name: string;
      email: string;
      role: string;
      status: string;
    };
  }>();
  
  // Toast
  const toast = useToast();
  
  // User Data
  const userData = ref({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
    status: props.user.status,
  });
  
  // Open Dialog
  const openDialog = () => {
    // Reset form data to the current user's data
    userData.value = {
      id: props.user.id,
      name: props.user.name,
      email: props.user.email,
      role: props.user.role,
      status: props.user.status,
    };
  };
  
  // Update User
  const updateUser = async () => {
    try {
      const response = await axios.put(`/api/users/${userData.value.id}`, userData.value);
      toast.success("User updated successfully!");
      setTimeout(() => {
        location.reload(); // Reload the page to reflect changes
      }, 2000);
    } catch (error) {
      toast.error("Failed to update user. Please try again.");
    }
  };
  </script>
  
  <style scoped>
  .input {
    @apply w-full p-2 border rounded-md bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200;
  }
  </style>