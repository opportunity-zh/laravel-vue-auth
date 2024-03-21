import axios from 'axios'
import { defineStore } from 'pinia'
import { authClient } from '../services/AuthService'


export const useAuthStore = defineStore('AuthStore', {
  // state
  state: () => {
    return {
        user: null
    }
  },

  actions: {

    async getAuthUser(){
        try{
            this.user = await authClient.getAuthUser();
        }catch(error){
            this.user = null;
        }
    }

  },


  // get Elements from store
  getters: {

    authUser: (state) => state.user,
  },

  // actions

  // getters
})