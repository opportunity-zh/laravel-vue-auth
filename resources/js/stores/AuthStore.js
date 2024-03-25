
import { defineStore } from 'pinia'
import AuthService from "@/services/AuthService";


export const useAuthStore = defineStore('AuthStore', {
  
  state: () => {
    return {
        user: null
    }
  },

  
  actions: {

    async getAuthUser(){
        try{
          let response = await AuthService.getAuthUser();
          this.user = response.data.data;
          return response.data.data;
        }
        catch(error){
          this.user = null;
        }
    },


    async logout(){
      return AuthService.logout().then(() => {
        this.user = null;
      });
    }

  },


  // get Elements from store
  getters: {

    authUser: (state) => state.user,
  },


})