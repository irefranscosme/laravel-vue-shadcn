<template>
    <div class="flex items-center h-full justify-center flex-col">
        <form
            @submit.prevent="register()"
            class="flex gap-3 flex-col w-[320px]"
        >
            <Input
                type="text"
                name="name"
                v-model="form.name"
                placeholder="Enter your name"
            />
            <div v-if="error?.validation" style="color: red">
                {{ error.validation.name }}
            </div>
            <Input
                type="email"
                name="email"
                v-model="form.email"
                placeholder="Enter your email"
            />
            <div v-if="error?.validation" style="color: red">
                {{ error.validation.email }}
            </div>
            <Input
                type="password"
                name="password"
                v-model="form.password"
                placeholder="Enter your password"
            />
            <Input
                type="password"
                name="passwordMatch"
                v-model="form.password_confirmation"
                placeholder="Confirm your password"
            />
            <div v-if="error?.validation" style="color: red">
                {{ error.validation.password }}
            </div>
            <Button type="submit">Register</Button>
        </form>
        <h2>{{ message }}</h2>
    </div>
    <router-link to="/login" class="absolute top-0 right-0 m-3">
        <Button
            type="button"
            style="width: 100%; border: 1px solid gray; background-color: gray"
        >
            Login
        </Button>
    </router-link>
</template>

<script setup lang="ts">
import { computed, nextTick, ref, watch } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { AxiosErrorResponse } from '@/types/errors';
import { RegistrationForm } from '@/types/form';

const router = useRouter();

const form = ref<RegistrationForm>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const error = ref();

const message = ref('');

const register = async () => {
    try {
        await axios.get('/sanctum/csrf-cookie');
        await axios.post('/register', form.value);
        localStorage.setItem('logged-in', 'true');

        setTimeout(() => {
            router.push('/home');
        }, 1000);
    } catch (err) {
        error.value = err as AxiosErrorResponse;
    }

    setTimeout(() => {
        error.value = '';
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
