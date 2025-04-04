<template>
  <Table>
    <TableHeader>
      <TableRow>
        <TableHead>User Name</TableHead>
        <TableHead>Email</TableHead>
        <TableHead>Role</TableHead>
        <TableHead>Status</TableHead>
        <TableHead class="text-right">Action</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableRow v-for="user in users" :key="user.id">
        <TableCell class="font-medium">{{ user.name }}</TableCell>
        <TableCell>{{ user.email }}</TableCell>
        <TableCell>{{ user.role }}</TableCell>
        <TableCell>
          <CustomSwitch
            :checked="user.status === 'active'"
            @update:checked="(checked) => handleToggle(user, checked)"
          />
        </TableCell>
        <TableCell class="text-right">
          <!-- Edit User -->
          <EditUserDialog :user="user" />
          <!-- Delete User -->
          <DeleteUserDialog :user="user" />
        </TableCell>
      </TableRow>
    </TableBody>
  </Table>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import EditUserDialog from "@/components/users/EditUserDialog.vue";
import DeleteUserDialog from "@/components/users/DeleteUserDialog.vue";
import CustomSwitch from '../../components/ui/customswitch/CustomSwitch.vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';

// Define the User type
interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  status: string;
}

// Define props
const props = defineProps<{ users: User[] }>();

// Toast
const toast = useToast();

// Local copy of users for reactivity
const localUsers = ref<User[]>([...props.users]);

// Toggle User Status
const handleToggle = async (user: User, checked: boolean) => {
  try {
    // Determine the new status
    const newStatus = checked ? "active" : "inactive";

    console.log("Sending PATCH request to update status...");
    console.log("User ID:", user.id);
    console.log("New Status:", newStatus);

    // Send update request to the new endpoint
    const response = await axios.patch(`/api/users/${user.id}/status`, {
      status: newStatus,
    });

    console.log("API Response:", response.data);

    // Update the local state
    const userIndex = localUsers.value.findIndex(u => u.id === user.id);
    if (userIndex !== -1) {
      localUsers.value[userIndex].status = newStatus;
    }

    toast.success(`User status updated to ${newStatus}`);
  } catch (error) {
    console.error("Error updating user status:", error);
    toast.error("Failed to update user status.");
  }
};
</script>