<template>
    <Dialog v-model:open="isOpen">
      <DialogTrigger as-child>
        <button @click="openDialog">
          <Trash class="w-4 h-4 mr-2 text-red-600 hover:text-red-700" />
        </button>
      </DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Confirm Delete</DialogTitle>
          <DialogDescription>
            Are you sure you want to delete <strong>{{ user.name }}</strong>? This action cannot be undone.
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button variant="outline" @click="closeDialog">Cancel</Button>
          <Button variant="destructive" :disabled="loading" @click="confirmDelete">
            <span v-if="loading">Deleting...</span>
            <span v-else>Confirm</span>
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </template>
  
  <script setup lang="ts">
  import { ref } from "vue";
  import { Button } from "@/components/ui/button";
  import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
  } from "@/components/ui/dialog";
  import { Trash } from "lucide-vue-next";
  import axios from "axios";
  import { useToast } from "vue-toastification";
  
  // Define the User type
  interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    status: string;
  }
  
  // Define props with explicit type
  const props = defineProps<{
    user: User;
  }>();
  
  const toast = useToast();
  const isOpen = ref(false);
  const loading = ref(false);
  
  const openDialog = () => (isOpen.value = true);
  const closeDialog = () => (isOpen.value = false);
  
  const confirmDelete = async () => {
    loading.value = true;
    try {
      await axios.delete(`/api/users/${props.user.id}`);
      toast.success("User deleted successfully!");
      setTimeout(() => location.reload(), 2000);
    } catch (error) {
      toast.error("An error occurred!");
    } finally {
      loading.value = false;
      closeDialog();
    }
  };
  </script>