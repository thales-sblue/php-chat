<template>
  <div class="min-h-screen flex items-center justify-center bg-[#0b0f10] px-4">
    <div class="w-full max-w-sm bg-[#121618] p-8 rounded-2xl shadow-2xl animate-fade-in border border-green-500/20">
      <h2 class="text-2xl font-semibold text-green-400 text-center mb-6">Entrar no Chat</h2>

      <form @submit.prevent="login" class="space-y-4">
        <div class="relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 11c0 0-4-1.5-4-4s1.79-3 4-3 4 1.5 4 4-4 4-4 4zm0 0c-2.5 0-8 1.5-8 4v3h16v-3c0-2.5-5.5-4-8-4z" />
            </svg>
          </span>
          <input
            v-model="cpf"
            type="text"
            placeholder="CPF ou CNPJ"
            class="w-full pl-10 pr-4 py-2 rounded-lg bg-[#1a1f21] text-white border border-green-500/30 focus:ring-2 focus:ring-green-500"
          />
        </div>

        <button
          type="submit"
          class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 rounded-lg transition duration-300"
        >
          Entrar
        </button>
      </form>

      <p v-if="error" class="text-red-400 text-center mt-4">{{ error }}</p>

      <div class="mt-6 text-center">
        <p class="text-sm text-gray-400 mb-2">Ainda não tem uma conta?</p>
        <button
          @click="goToCreate"
          class="text-green-400 hover:text-green-300 transition font-semibold text-sm"
        >
          Criar Conta
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from '../axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const cpf = ref('')
const error = ref('')

const login = async () => {
  try {
    const response = await axios.post('/auth/login', { cpf_cnpj: cpf.value })
    localStorage.setItem('token', response.data.token)
    router.push('/home')
  } catch (err) {
    error.value = 'CPF ou CNPJ inválido'
  }
}

const goToCreate = () => {
  router.push('/create-client')
}
</script>
