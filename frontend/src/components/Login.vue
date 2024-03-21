<template>
    <div class="flex items-center h-full justify-center flex-col">
        <h2
            v-if="!validationIsEmpty && error?.message"
            class="py-2 text-red-500"
        >
            {{ error.message }}
        </h2>
        <form @submit.prevent="login()" class="flex gap-3 flex-col w-[320px]">
            <Input
                type="text"
                name="email"
                v-model="form.email"
                placeholder="Enter your email"
            />
            <div v-if="error?.validation" class="text-red-500">
                {{ error.validation.email }}
            </div>
            <Input
                type="password"
                name="password"
                v-model="form.password"
                placeholder="Enter your password"
            />
            <div v-if="error?.validation" class="text-red-500">
                {{ error.validation.password }}
            </div>
            <Button type="submit">Login</Button>
            <router-link to="/register">
                <Button
                    type="button"
                    style="
                        width: 100%;
                        border: 1px solid gray;
                        background-color: gray;
                    "
                >
                    Register
                </Button>
            </router-link>
        </form>
    </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import { computed, nextTick, ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { AxiosErrorResponse } from '@/types/errors';
import { LoginForm } from '@/types/form';

const router = useRouter();

const error = ref<AxiosErrorResponse>();
const form = ref<LoginForm>({
    email: '',
    password: '',
});

const login = async () => {
    try {
        await axios.get('/sanctum/csrf-cookie');
        await axios.post('/login', form.value);
        localStorage.setItem('logged-in', 'true');
        router.push('/home');
    } catch (err) {
        error.value = err as AxiosErrorResponse;
    }
    setTimeout(() => {
        error.value = undefined;
    }, 3000);
};

const validationIsEmpty = computed(() => {
    if (!error.value) return false;

    const validation = error.value.validation;
    const length = Object.keys(error.value.validation).length > 0;

    if (validation && length) return true;
});

watch(error, () => {
    nextTick(() => {
        // Ensure the DOM is updated before recomputing
        validationIsEmpty.value;
    });
});
</script>

<style scoped></style>
