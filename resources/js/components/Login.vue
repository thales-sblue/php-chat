<template>
  <div class="login">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <input v-model="cpf" type="text" placeholder="CPF ou CNPJ" />
      <button type="submit">Entrar</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from '../axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const cpf = ref('')

const login = async () => {
  try {
    const response = await axios.post('/api/login', { cpf_cnpj: cpf.value })
    localStorage.setItem('token', response.data.token)
    router.push('/home')
  } catch (err) {
    alert('Erro ao logar')
  }
}
</script>
