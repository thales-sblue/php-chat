<template>
  <div class="min-h-screen bg-[#0b0f10] p-4 text-white flex flex-col">
    <div class="max-w-3xl w-full mx-auto flex-1 flex flex-col justify-between">
      <div class="space-y-2 overflow-y-auto mb-4 pr-2 flex-1 scrollbar-thin scrollbar-thumb-green-700/30 scrollbar-track-transparent">
        <template v-if="messages.length > 0">
          <div
            v-for="msg in messages"
            :key="msg.id"
            :class="{
              'text-right': msg.from === clientId,
              'text-left': msg.from !== clientId
            }"
          >
            <div
              :class="[
                'inline-block px-4 py-2 rounded-xl max-w-[70%] break-words',
                msg.from === clientId
                  ? 'bg-green-600 text-white'
                  : 'bg-gray-700 text-white'
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
import { ref, onMounted } from 'vue'
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
const clientId = parseInt(localStorage.getItem('client_id'))
const recipientId = ref(null)

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

onMounted(fetchMessages)


</script>
