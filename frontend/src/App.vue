<template>
    <router-view></router-view>
</template>

<script setup lang="ts">
import axios from 'axios';
import { onBeforeMount } from 'vue';
import router from './router/routes';

onBeforeMount(async () => {
    try {
        await axios.get('/api/user');
        localStorage.setItem('logged-in', 'true');
        router.push('/home');
    } catch (err) {
        localStorage.setItem('logged-in', '');
        router.push('/login');
    }
});
</script>

<style scoped></style>
