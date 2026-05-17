<script setup>
import { useModalStore } from '@/stores/useModalStore';
import PollForm from './components/PollForm.vue';
import PollTable from './components/PollTable.vue';
import { usePollStore } from '@/stores/usePollStore';

usePollStore();
const { isModalOpen, modalComponent, modalProps } = useModalStore();
</script>

<template>
  <div class="min-h-screen bg-yellow-300 font-black">

    <!-- Header -->
    <header class="bg-black border-b-4 border-black">
      <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between">
          <a href="/" class="text-2xl font-black text-yellow-300 tracking-tighter uppercase hover:text-pink-400 transition">
            ⚡ SONDAPÉROOO
          </a>
        </div>
      </nav>
    </header>

    <!-- Contenu -->
    <main class="container mx-auto px-4 py-10 max-w-4xl">

      <div class="mb-8">
        <h1 class="text-5xl font-black text-black uppercase leading-none tracking-tighter">
          MES<br>
          <span class="text-pink-600">SONDAGES</span> 🗳️
        </h1>
        <p class="mt-2 text-black font-bold uppercase text-sm">
          Crée, gère et partage tes sondages !
        </p>
      </div>

      <PollForm />
      <PollTable />

      <!-- Modal global -->
      <component v-if="isModalOpen" :is="modalComponent" v-bind="modalProps" />
    </main>

    <!-- Footer -->
    <footer class="bg-black text-yellow-300 font-black text-sm mt-10">
      <div class="container mx-auto px-4 py-4 text-center uppercase tracking-widest">
        ⚡ SONDAPÉROOO — {{ new Date().getFullYear() }} — VOTE. DÉBATS. RÉPÈTE.
      </div>
    </footer>
  </div>
</template>
