<!-- components/CreateUserDialog.vue -->
<template>
    <Dialog v-model:open="isOpen" class="dark:bg-gray-800">
        <DialogTrigger as-child>
            <Button @click="openDialog" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 dark:bg-gray-700 dark:text-gray-200">
                <UserRoundPlus class="w-4 h-4 mr-2" /> Create
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle class="text-lg font-bold dark:text-gray-200">Create New User</DialogTitle>
                <DialogDescription class="text-sm dark:text-gray-400">Fill in the details to add a new user.</DialogDescription>
            </DialogHeader>

            <form @submit.prevent="createUser">
                <div class="grid gap-4">
                    <input v-model="formData.name" type="text" placeholder="Name" class="form-input" required />
                    <input v-model="formData.email" type="email" placeholder="Email" class="form-input" required />
                    <input v-model="formData.password" type="password" placeholder="Password" class="form-input" required />
                    <select v-model="formData.role" class="form-input" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    <select v-model="formData.status" class="form-input" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <DialogFooter class="mt-4">
                    <Button variant="outline" @click="closeDialog" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded border border-gray-300 dark:bg-gray-700 dark:text-gray-200">Cancel</Button>
                    <Button type="submit" :disabled="loading" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded dark:bg-gray-700 dark:text-gray-200">
                        <span v-if="loading">Creating...</span>
                        <span v-else>Create</span>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Button } from "@/components/ui/button";
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { UserRoundPlus } from "lucide-vue-next";
import axios from "axios";
import { useToast } from "vue-toastification";

const toast = useToast();
const isOpen = ref(false);
const loading = ref(false);
const formData = ref({ name: "", email: "", password: "", role: "user", status: "active" });

const openDialog = () => (isOpen.value = true);
const closeDialog = () => (isOpen.value = false);

const createUser = async () => {
    loading.value = true;
    try {
        await axios.post("/api/users", formData.value);
        toast.success("User created successfully!");
        setTimeout(() => location.reload(), 2000);
    } catch (error) {
        toast.error("Failed to create user");
    } finally {
        loading.value = false;
        closeDialog();
    }
};
</script>

<style scoped>
.form-input {
    @apply p-2 rounded w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700;
}
</style>