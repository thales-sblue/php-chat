<template>
  <div class="flex h-screen bg-[#0b0f10] text-white">
    <Sidebar @selectConversation="selectedConversationId = $event" />
    <div class="flex-1">
      <component
        :is="selectedConversationId ? ChatView : ChatPlaceholder"
        :conversation-id="selectedConversationId"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Sidebar from '../components/Sidebar.vue'
import ChatView from '../components/ChatView.vue'
import ChatPlaceholder from '../components/ChatPlaceholder.vue'

const selectedConversationId = ref(null)
</script>
