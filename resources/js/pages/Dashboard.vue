<script setup>
import { useAuthStore } from '../stores/AuthStore';
import Logout from '@/components/Logout.vue';

const store = useAuthStore();



</script>


<template>
    <header>
        <Logout/>
    </header>
    <span>Dashboard</span>
    <h1>Hallo {{ store.authUser.name }}</h1>
</template>