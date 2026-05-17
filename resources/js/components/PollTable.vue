<script setup>
import { defineAsyncComponent, computed } from 'vue';
import { usePollStore } from '@/stores/usePollStore';
import { useModalStore } from '@/stores/useModalStore';

const { polls, loading, error, deletePoll } = usePollStore();
const { openModal } = useModalStore();

const PollEditModal = defineAsyncComponent(() => import('./PollEditModal.vue'));

const colors = ['bg-pink-400', 'bg-cyan-400', 'bg-orange-400', 'bg-lime-400', 'bg-violet-400'];

function getPollUrl(token) {
  return `${window.location.origin}/p/${token}`;
}

function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(() => {
    alert('Lien copié ! 🎉');
  });
}

function editPoll(poll) {
  openModal(PollEditModal, { poll });
}

async function delPoll(id) {
  if (confirm('Supprimer ce sondage ? C\'est irréversible !')) {
    await deletePoll(id);
  }
}
</script>

<template>
  <div>
    <div v-if="loading" class="text-center py-10">
      <p class="text-2xl font-black uppercase">Chargement... ⚡</p>
    </div>

    <div v-else-if="error" class="bg-red-400 border-4 border-black p-4">
      <p class="font-black uppercase text-black">⚠ Erreur : {{ error.statusText }}</p>
    </div>

    <div v-else-if="polls.length === 0" class="bg-white border-4 border-black shadow-[4px_4px_0px_black] p-10 text-center">
      <p class="text-5xl mb-4">🗳️</p>
      <p class="text-2xl font-black uppercase">Aucun sondage pour l'instant...</p>
      <p class="font-bold uppercase text-sm text-black/60 mt-2">Crée ton premier sondage ci-dessus !</p>
    </div>

    <div v-else class="space-y-4">
      <div
        v-for="(poll, i) in polls"
        :key="poll.id"
        :class="colors[i % colors.length]"
        class="border-4 border-black shadow-[6px_6px_0px_black] p-5"
      >
        <div class="flex justify-between items-start gap-4 mb-3">
          <div>
            <p class="text-black font-black text-xl uppercase leading-tight">
              {{ poll.question }}
            </p>
            <p v-if="poll.title" class="text-black/60 font-bold uppercase text-xs mt-1">
              {{ poll.title }}
            </p>
          </div>
          <div class="flex gap-2 shrink-0">
            <span v-if="poll.is_draft"
              class="text-xs px-2 py-1 bg-black text-yellow-300 font-black uppercase">
              ✏️ Brouillon
            </span>
            <span v-else-if="poll.ends_at && new Date() > new Date(poll.ends_at)"
              class="text-xs px-2 py-1 bg-black text-red-400 font-black uppercase">
              🔴 Terminé
            </span>
            <span v-else
              class="text-xs px-2 py-1 bg-black text-lime-400 font-black uppercase">
              🟢 Ouvert
            </span>
          </div>
        </div>

        <!-- Infos -->
        <div class="flex flex-wrap gap-2 text-xs font-bold uppercase text-black/70 mb-4">
          <span>{{ poll.options?.length ?? 0 }} options</span>
          <span>·</span>
          <span>{{ poll.allow_multiple_choices ? 'Choix multiple' : 'Choix unique' }}</span>
          <span>·</span>
          <span>{{ poll.results_public ? 'Résultats publics' : 'Résultats privés' }}</span>
          <span v-if="poll.ends_at">· Fin : {{ new Date(poll.ends_at).toLocaleString('fr-CH') }}</span>
        </div>

        <!-- Lien -->
        <div class="flex items-center gap-2 mb-4 bg-white/50 border-2 border-black p-2">

            <a :href="getPollUrl(poll.secret_token)"
            target="_blank"
            class="text-xs font-bold text-black underline truncate flex-1"
          >
            {{ getPollUrl(poll.secret_token) }}
          </a>
          <button
            @click="copyToClipboard(getPollUrl(poll.secret_token))"
            class="shrink-0 px-3 py-1 bg-black text-yellow-300 font-black uppercase text-xs border-2 border-black hover:bg-gray-800 transition"
          >
            Copier
          </button>
        </div>

        <!-- Actions -->
        <div class="flex gap-2">
          <button
            @click="editPoll(poll)"
            class="px-4 py-2 bg-white text-black font-black uppercase border-4 border-black shadow-[3px_3px_0px_black] hover:shadow-none hover:translate-x-[3px] hover:translate-y-[3px] transition-all text-sm"
          >
            ✏️ Modifier
          </button>
          <button
            @click="delPoll(poll.id)"
            class="px-4 py-2 bg-red-500 text-white font-black uppercase border-4 border-black shadow-[3px_3px_0px_black] hover:shadow-none hover:translate-x-[3px] hover:translate-y-[3px] transition-all text-sm"
          >
            🗑️ Supprimer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
