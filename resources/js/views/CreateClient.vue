<template>
  <div class="min-h-screen flex items-center justify-center bg-[#0b0f10] px-4">
    <div class="w-full max-w-md bg-[#121618] p-8 rounded-2xl shadow-lg animate-fade-in border border-green-500/20">
      <h2 class="text-2xl font-semibold text-green-400 text-center mb-6">Criar Cliente</h2>

      <form @submit.prevent="createClient" class="space-y-4">
        <div>
          <label class="block text-sm text-gray-300 mb-1">Nome</label>
          <input
            v-model="name"
            type="text"
            class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-500"
          />
        </div>

        <div>
          <label class="block text-sm text-gray-300 mb-1">CPF ou CNPJ</label>
          <input
            v-model="cpf"
            type="text"
            class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-500"
          />
        </div>

        <div>
          <label class="block text-sm text-gray-300 mb-1">Telefone</label>
          <input
            v-model="phone"
            type="text"
            class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-green-500"
          />
        </div>

        <button
          type="submit"
          class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-2 rounded-lg transition-colors duration-300"
        >
          Criar Conta
        </button>
      </form>

      <p v-if="success" class="text-green-400 text-center mt-4">Conta criada com sucesso!</p>
      <p v-if="error" class="text-red-400 text-center mt-4">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from '../axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const name = ref('')
const cpf = ref('')
const phone = ref('')
const error = ref('')
const success = ref(false)

const createClient = async () => {
  error.value = ''
  success.value = false

  if (!name.value || !cpf.value || !phone.value) {
    error.value = 'Todos os campos são obrigatórios.'
    return
  }

  try {
    await axios.post('/clients', {
      name: name.value,
      cpf_cnpj: cpf.value,
      phone: phone.value,
      balance: 0,
      limit: 0,
      status: 'active'
    })

    success.value = true
    setTimeout(() => {
      router.push('/login')
    }, 1500)
  } catch (err) {
    error.value = err?.response?.data?.message || 'Erro ao criar conta.'
  }
}
</script>
