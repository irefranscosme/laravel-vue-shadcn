<template>
    <h2 style="text-align: left">Users</h2>
    <Suspense>
        <template #default>
            <div v-if="loading" style="margin-bottom: 1em">Loading...</div>
            <div v-else>
                <!-- Display users -->
                <ul>
                    <li v-for="user in users" :key="user.id">
                        {{ user.name }}
                    </li>
                </ul>
                <!-- Lists -->
            </div>
        </template>
        <template #fallback>
            <div>Loading...</div>
        </template>
    </Suspense>
    <!-- Display pagination -->
    <ul v-if="pagination?.links">
        <li v-for="link in pagination.links" :key="link.label">
            <a @click.prevent="paginate(link.url)">
                <button v-html="link.label"></button>
            </a>
        </li>
    </ul>
    <ul v-else>
        <li v-if="pagination">
            <a @click.prevent="paginate(pagination.prev_page_url)">
                <button>Prev</button>
            </a>
            <a @click.prevent="paginate(pagination.next_page_url)">
                <button>Next</button>
            </a>
        </li>
    </ul>
</template>

<script setup lang="ts">
import { ref, onBeforeMount } from 'vue';
import axios, { AxiosResponse } from 'axios';
import { Pagination } from '@/types/pagination';
import { User } from '@/types/users';
import { AxiosErrorResponse } from '@/types/errors';

const loading = ref(true);
const error = ref({});
const pagination = ref<Pagination<User[]>>();
const users = ref<User[]>();

const emits = defineEmits<{
    'handle-error': [data: AxiosErrorResponse];
}>();

const getUsers = async () => {
    try {
        const response: AxiosResponse = await axios.get('/api/users');
        const paginationResponse: Pagination<User[]> = response.data;

        pagination.value = paginationResponse;
        users.value = paginationResponse.data;

        loading.value = false;
    } catch (err) {
        const errorResponse = err as AxiosErrorResponse;
        error.value = errorResponse;
        loading.value = true;

        emits('handle-error', errorResponse);
    }
};

const paginate = async (url: string) => {
    if (!url) return;

    try {
        axios.defaults.baseURL = '';

        const response: AxiosResponse = await axios.get(url);
        const paginationResponse: Pagination<User[]> = response.data;

        pagination.value = paginationResponse;
        users.value = paginationResponse.data;

        loading.value = false;
    } catch (err) {
        error.value = err as AxiosErrorResponse;
    }
};

onBeforeMount(async () => {
    getUsers();
});
</script>

<style scoped></style>
