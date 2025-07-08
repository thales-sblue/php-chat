<template>
  <div>
    <h1>Bem-vindo!</h1>
    <p>Token salvo: {{ token }}</p>
  </div>
</template>

<script setup>
    const token = localStorage.getItem('token')
</script>
