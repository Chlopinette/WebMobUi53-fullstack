<script setup>
import { useModalStore } from '@/stores/useModalStore';
import PollForm from './components/PollForm.vue';
import PollTable from './components/PollTable.vue';
import { usePollStore } from '@/stores/usePollStore';

usePollStore();
const { isModalOpen, modalComponent, modalProps } = useModalStore();
</script>

<template>
  <div>
    <div class="mb-8">
      <h1 class="text-5xl font-black text-black uppercase leading-none tracking-tighter">
        MES SONDAGES
      </h1>
      <p class="mt-2 text-black/70 font-bold uppercase text-sm">
        Crée, gère et partage tes sondages !
      </p>
    </div>

    <PollForm />
    <PollTable />

    <!-- Modal global -->
    <component v-if="isModalOpen" :is="modalComponent" v-bind="modalProps" />
  </div>
</template>
