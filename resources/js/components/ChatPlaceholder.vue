<template>
  <div class="flex-1 flex flex-col p-6 bg-[#0d1112]">
    <h1 class="text-2xl font-bold text-green-400 mb-2">Bem-vindo!</h1>
    <p class="text-sm text-gray-400">
      Seu token:
      <code class="text-green-300 break-all">{{ token }}</code>
    </p>

    <div class="mt-8 flex-1 flex items-center justify-center text-gray-500 text-xl italic">
      Selecione uma conversa para começar
    </div>
  </div>
</template>

<script setup>
const token = localStorage.getItem('token')
</script>
