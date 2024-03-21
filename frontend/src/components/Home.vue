<template>
    <div
        v-if="currentUser?.name"
        class="flex items-center justify-center h-full"
    >
        <h1 class="text-lg">
            Welcome,
            <span class="font-bold"> {{ currentUser.name }} </span>
        </h1>
        <!-- <Users @handle-error="handleUsersError" /> -->
        <form
            @submit.prevent="logout()"
            class="absolute top-0 right-0 m-3 flex gap-2"
        >
            <span>{{ currentUser.email }}</span>
            <button type="submit">Logout</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import axios, { AxiosResponse } from 'axios';
import { onBeforeMount, ref } from 'vue';
import { useRouter } from 'vue-router';
// import Users from './Users.vue';
import { AxiosErrorResponse } from '@/types/errors';
import { User } from '@/types/users';

const error = ref<AxiosErrorResponse>();
const router = useRouter();
const currentUser = ref<User>();

const logout = async () => {
    console.log('User logged out');
    localStorage.setItem('logged-in', '');
    localStorage.removeItem('user-name');

    try {
        await axios.post('/api/logout');
        localStorage.removeItem('logged-in');
        router.push('/login');
    } catch (err) {
        error.value = err as AxiosErrorResponse;
    }
};

// const handleUsersError = (err: AxiosErrorResponse) => {
//     error.value = err;
// };

const getUser = async (): Promise<void> => {
    try {
        const response: AxiosResponse = await axios.get('/api/user');
        const user = response.data.data as User;
        currentUser.value = user;
    } catch (err) {
        error.value = err as AxiosErrorResponse;
    }
};

onBeforeMount(() => {
    getUser();
});
</script>

<style scoped>
form {
    display: flex;
    flex-direction: row;
    position: absolute;
    top: 0;
    right: 0;
    margin: 1em;
}
form button {
    all: unset;
    color: rgb(20, 92, 249);
    cursor: pointer;
}
</style>
