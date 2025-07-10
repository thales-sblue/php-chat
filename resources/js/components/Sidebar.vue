<template>
  <aside class="w-1/4 bg-[#121618] border-r border-green-500/20 p-4 flex flex-col">
    <h2 class="text-xl font-bold mb-4 text-green-400">Conversas</h2>

    <ul class="space-y-2 overflow-y-auto flex-1 scrollbar-thin scrollbar-thumb-green-700/30 scrollbar-track-transparent">
      <li
        v-for="conversation in conversations"
        :key="conversation.id"
        @click="$emit('selectConversation', conversation.id)"
        class="p-3 rounded-lg cursor-pointer hover:bg-green-500/10 transition"
      >
        {{ conversation.recipient.name }}
      </li>
    </ul>

    <div class="mt-4">
      <input
        v-model="cpf"
        placeholder="Digite o CPF"
        class="bg-[#1a1e1f] text-sm text-white p-2 rounded w-full mb-2 border border-green-600 focus:outline-none"
      />
      <button
        @click="startConversation"
        class="bg-green-600 hover:bg-green-500 text-white py-2 px-4 rounded-lg text-sm transition duration-300 shadow w-full"
      >
        Nova conversa
      </button>
      <p v-if="error" class="text-red-400 text-sm mt-1">{{ error }}</p>
    </div>
  </aside>
</template>

<script setup>
import { ref, onMounted, defineEmits } from 'vue'
import axios from '../axios'

const emit = defineEmits(['selectConversation'])

const conversations = ref([])
const cpf = ref('')
const error = ref('')

const fetchConversations = async () => {
  try {
    const res = await axios.get('/conversations/')
    conversations.value = res.data
  } catch (e) {
    console.error('Erro ao buscar conversas:', e)
  }
}

const startConversation = async () => {
  error.value = ''
  const cpfDigitado = cpf.value.trim()

  if (!cpfDigitado) {
    error.value = 'CPF é obrigatório'
    return
  }

  const existing = conversations.value.find(conv =>
    conv.name === `Conversa com ${cpfDigitado}`
  )

  if (existing) {
    emit('selectConversation', existing.id)
    cpf.value = ''
    return
  }

  try {
    const res = await axios.post('/conversations/start', {
      cpf_cnpj: cpfDigitado,
    })

    const newConv = {
      id: res.data.conversation_id,
      recipient: {
        id: res.data.recipient_id,
        name: res.data.recipient_name,
      },
    }

    conversations.value.push(newConv)
    emit('selectConversation', newConv.id)
    cpf.value = ''
  } catch (e) {
    error.value = e?.response?.data?.message || 'Erro ao iniciar conversa'
  }
}

onMounted(() => {
  fetchConversations()
})
</script>
