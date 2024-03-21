<script setup>
import { useAuthStore } from '../../stores/AuthStore';
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
        // log user in
        await AuthService.login(user.value);

        // get user from db
        const authUser = await store.getAuthUser().then(() => {
            if (!store.authUser) router.push("/login");
        });

        if (authUser) {
          router.push("/dashboard");
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