<script setup>
import { useAuthStore } from '@/stores/AuthStore';
import AuthService from "@/services/AuthService";
import {ref} from 'vue';
import {useRouter} from 'vue-router';

const router = useRouter();
const store = useAuthStore();

const user = ref({
    email : '',
    password : ''
});


const login = async() => {

    try {

        await AuthService.login(user.value);

        const authUser = await store.getAuthUser();

        if(authUser) {
          router.push("/dashboard");
        }
        else {
            console.log('error');
        }
      } catch (error) {
        console.log(error);
      }
}

</script>

<template>


    <form action="" method="post" @submit.prevent="login">


        <div class="form__group">
            <label for="">E-Mail</label>
            <input type="email" name="email" v-model="user.email">
        </div>

        <div class="form__group">
            <label for="">Passwort</label>
            <input type="password" name="password" v-model="user.password">
        </div>

        <button type="submit">Login</button>


    </form>

</template>