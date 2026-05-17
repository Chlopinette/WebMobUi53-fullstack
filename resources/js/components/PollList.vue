<template>
  <div>
    <h2>My Polls</h2>
    <div v-if="loading">Loading...</div>
    <div v-if="error">Error: {{ error.statusText }}</div>
    <ul v-if="data">
      <li v-for="poll in data" :key="poll.id">
        {{ poll.question }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { useFetchApi } from '../composables/useFetchApi';

const { data, error, loading } = useFetchApi().fetchApiToRef({
  url: '/polls',
  method: 'GET',
});
</script>
