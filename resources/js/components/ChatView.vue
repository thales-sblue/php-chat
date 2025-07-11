<template>
  <div class="h-screen bg-[#0b0f10] p-4 text-white flex flex-col">
    <div class="max-w-3xl w-full mx-auto flex flex-col flex-1 min-h-0">
      <div
        ref="messagesContainer"
        class="flex-1 min-h-0 overflow-y-auto pr-2 mb-4 scrollbar-none"
      >
        <div class="flex flex-col gap-y-2 min-h-full">
          <template v-if="messages.length > 0">
            <div
              v-for="msg in messages"
              :key="msg.id"
              class="flex"
              :class="msg.sender_id === clientId ? 'justify-end' : 'justify-start'"
            >
              <div
                class="max-w-[70%] px-4 py-2 rounded-xl break-words text-sm relative"
                :class="msg.sender_id === clientId
                  ? 'bg-green-600 text-white rounded-br-none'
                  : 'bg-gray-700 text-white rounded-bl-none'"
              >
                {{ msg.content }}

                <div class="text-[10px] text-right mt-1 text-gray-300 whitespace-nowrap">
                  <template v-if="msg.sender_id === clientId">
                    {{ formatTimestamp(msg.created_at) }} ·
                    <span v-if="msg.status === 'sent'">✔ Enviado</span>
                    <span v-else-if="msg.status === 'received'">✔✔ Recebido</span>
                    <span v-else-if="msg.status === 'read'">✔✔ Lido</span>
                    <span v-else>⌛ Enviando...</span>
                  </template>
                  <template v-else>
                    {{ formatTimestamp(msg.created_at) }} ·
                    <span v-if="msg.status === 'sent'">✔ Enviado</span>
                    <span v-else-if="msg.status === 'received'">✔✔ Recebido</span>
                    <span v-else-if="msg.status === 'read'">✔✔ Lido</span>
                    <span v-else>⌛ Enviando...</span>
                  </template>
                </div>
              </div>
            </div>
          </template>

          <template v-else>
            <div
              class="text-center text-gray-400 italic flex items-center justify-center flex-1"
            >
              Nenhuma mensagem ainda. Envie a primeira!
            </div>
          </template>
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
import { ref, onMounted, onUnmounted, watch, nextTick, computed } from 'vue'
import axios from '../axios'

const props = defineProps({
  conversationId: {
    type: [String, Number],
    required: true,
  },
})

const conversationId = computed(() => props.conversationId)

const messages = ref([])
const newMessage = ref('')
const recipientId = ref(null)
const clientId = ref(parseInt(sessionStorage.getItem('client_id')))
const messagesContainer = ref(null)

const fetchMessages = async () => {
  if (!conversationId.value) return
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

watch(messages, async () => {
  await nextTick()
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
  }
})

watch(conversationId, async () => {
  await fetchMessages()
})

let interval = null

onMounted(() => {
  fetchMessages()
  interval = setInterval(fetchMessages, 3000)
})

onUnmounted(() => {
  clearInterval(interval)
})

const formatTimestamp = (timestamp) => {
  if (!timestamp) return ''
  const date = new Date(timestamp)
  return date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })
}
</script>
