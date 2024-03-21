<script setup>
import { useAuthStore } from '../../stores/AuthStore';
import { authClient } from '../../services/AuthService';
import {ref} from 'vue';

const store = useAuthStore();

const user = ref({
    email : '',
    password : ''
});


const login = async() => {
    try {
        // log user in
        await AuthService.login(payload);

        // get user from db
        const authUser = await store.getAuthUser().then(() => {
            if (!store.authUser) next('/login');
        });


        if (authUser) {
          this.$store.dispatch("auth/setGuest", { value: "isNotGuest" });
          this.$router.push("/dashboard");
        } else {
          const error = Error(
            "Unable to fetch user after login, check your API settings."
          );
          error.name = "Fetch User";
          throw error;
        }
      } catch (error) {
        this.error = getError(error);
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


</form>

</template>