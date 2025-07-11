<template>
  <div class="min-h-screen bg-[#0b0f10] p-4 text-white flex flex-col">
    <div class="max-w-3xl w-full mx-auto flex-1 flex flex-col justify-between">
      <div class="space-y-2 overflow-y-auto mb-4 pr-2 flex-1 scrollbar-thin scrollbar-thumb-green-700/30 scrollbar-track-transparent">
        <template v-if="messages.length > 0">
          <div
            v-for="msg in messages"
            :key="msg.id"
            class="flex"
            :class="msg.sender_id === clientId ? 'justify-end' : 'justify-start'"
          >
            <div
              :class="[
                'px-4 py-2 rounded-xl max-w-[70%] break-words text-sm',
                msg.sender_id === clientId
                  ? 'bg-green-600 text-white rounded-br-none'
                  : 'bg-gray-700 text-white rounded-bl-none'
              ]"
            >
              {{ msg.content }}
            </div>
          </div>
        </template>

        <div
          v-else
          class="text-center text-gray-400 italic flex items-center justify-center h-full"
        >
          Nenhuma mensagem ainda. Envie a primeira!
        </div>
      </div>

      <form @submit.prevent="sendMessage" class="flex items-center gap-2">
        <input
          v-model="newMessage"
          type="text"
          placeholder="Digite sua mensagem..."
          class="flex-1 px-4 py-2 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-green-500"
        />
        <button
          type="submit"
          class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition-colors"
        >
          Enviar
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from '../axios'

const props = defineProps({
  conversationId: {
    type: [String, Number],
    required: true,
  },
})

const conversationId = ref(props.conversationId)
const messages = ref([])
const newMessage = ref('')
const recipientId = ref(null)
const clientId = ref(parseInt(sessionStorage.getItem('client_id')))

const fetchMessages = async () => {
  try {
    const res = await axios.get(`/conversations/${conversationId.value}/messages`)

    messages.value = res.data.messages || []
    recipientId.value = res.data.recipient?.id || ''
  } catch (err) {
    console.error('Erro ao buscar mensagens:', err)
  }
}

const sendMessage = async () => {
  if (!newMessage.value.trim() || !recipientId.value) return

  try {
    await axios.post('/messages/send', {
      conversation_id: conversationId.value,
      recipient_id: recipientId.value,
      content: newMessage.value,
      priority: 'normal',
    })

    newMessage.value = ''
    await fetchMessages()
  } catch (err) {
    console.error('Erro ao enviar mensagem:', err)
  }
}

let interval = null

onMounted(() => {
  fetchMessages()
  interval = setInterval(fetchMessages, 3000)
})

onUnmounted(() => {
  clearInterval(interval)
})
</script>
