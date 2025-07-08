<template>
  <div class="flex h-screen bg-[#0b0f10] text-white">
    <aside class="w-1/4 bg-[#121618] border-r border-green-500/20 p-4 flex flex-col">
      <h2 class="text-xl font-bold mb-4 text-green-400">Conversas</h2>

      <ul class="space-y-2 overflow-y-auto flex-1 scrollbar-thin scrollbar-thumb-green-700/30 scrollbar-track-transparent">
        <li
          v-for="contact in contacts"
          :key="contact.id"
          class="p-3 rounded-lg cursor-pointer hover:bg-green-500/10 transition"
        >
          {{ contact.name }}
        </li>
      </ul>

      <button
        class="mt-4 bg-green-600 hover:bg-green-500 text-white py-2 px-4 rounded-lg text-sm transition duration-300 shadow"
      >
        Nova conversa
      </button>
    </aside>

    <main class="flex-1 flex flex-col p-6 bg-[#0d1112]">
      <h1 class="text-2xl font-bold text-green-400 mb-2">Bem-vindo!</h1>
      <p class="text-sm text-gray-400">
        Seu token:
        <code class="text-green-300 break-all">{{ token }}</code>
      </p>

      <div class="mt-8 flex-1 flex items-center justify-center text-gray-500 text-xl italic">
        Selecione uma conversa para começar
      </div>
    </main>
  </div>
</template>

<script setup>
const token = localStorage.getItem('token')

const contacts = [
  { id: 1, name: 'Suporte' },
  { id: 2, name: 'Fulano' },
  { id: 3, name: 'Thales Backend' },
]
</script>
