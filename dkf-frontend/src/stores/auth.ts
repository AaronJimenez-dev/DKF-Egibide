import type { User } from "@/interfaces/User";
import { defineStore } from "pinia";
import { ref } from "vue";
import router from "@/router";

export const useAuthStore = defineStore('auth', () => {
  const currentUser = ref<User | null>(null);
  const token = ref<string | null>(localStorage.getItem('token'));

  async function login(email: string, password: string) {
    try {
      const response = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
      });

      if(!response.ok) return false;

      const data = await response.json();

      token.value = data.token;
      localStorage.setItem('token', data.token);

      router.replace('/');

    } catch (error) {
      console.error(error);
      return false
    }
  }

  return { currentUser, token, login }
});
