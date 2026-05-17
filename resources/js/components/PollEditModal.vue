<template>
  <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4" @click.self="closeModal">
    <div class="bg-white border-4 border-black shadow-[8px_8px_0px_black] w-full max-w-lg">

      <div class="bg-black p-4 flex justify-between items-center">
        <h3 class="text-xl font-black text-yellow-300 uppercase">Modifier le sondage ✏️</h3>
        <button @click="closeModal" class="text-yellow-300 font-black text-xl hover:text-pink-400">✕</button>
      </div>

      <form @submit.prevent="submitUpdate" class="p-6">

        <div class="mb-5">
          <label class="block text-sm font-black text-black uppercase mb-2 tracking-wide">
            Question
          </label>
          <input
            v-model="editablePoll.question"
            type="text"
            class="w-full px-4 py-3 border-4 border-black font-bold text-black focus:outline-none focus:border-pink-500"
          >
        </div>

        <div class="mb-5">
          <label class="block text-sm font-black text-black uppercase mb-2 tracking-wide">
            Titre (optionnel)
          </label>
          <input
            v-model="editablePoll.title"
            type="text"
            class="w-full px-4 py-3 border-4 border-black font-bold text-black focus:outline-none focus:border-pink-500"
          >
        </div>

        <!-- Lancer le sondage -->
        <div v-if="editablePoll.is_draft" class="mb-5 border-4 border-black p-4 bg-yellow-50">
          <p class="text-sm font-black uppercase text-black mb-3">Statut</p>
          <label class="flex items-center gap-3 cursor-pointer">
            <input
              type="checkbox"
              v-model="launchNow"
              class="w-5 h-5 accent-pink-500"
            >
            <span class="font-bold uppercase text-sm text-black">
              Lancer le sondage maintenant 🚀
            </span>
          </label>
        </div>

        <div class="mb-5">
          <label class="block text-sm font-black text-black uppercase mb-2 tracking-wide">
            Résultats publics
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input
              type="checkbox"
              v-model="editablePoll.results_public"
              class="w-5 h-5 accent-pink-500"
            >
            <span class="font-bold uppercase text-sm text-black">Oui, les résultats sont visibles par tous</span>
          </label>
        </div>

        <div v-if="updateError" class="bg-red-400 border-4 border-black p-3 mb-4">
          <p class="font-black uppercase text-black text-sm">⚠ {{ updateError }}</p>
        </div>

        <div class="flex justify-between gap-3 pt-4 border-t-4 border-black">
          <button
            type="button"
            @click="closeModal"
            class="px-5 py-2 bg-gray-200 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all"
          >
            Annuler
          </button>
          <button
            type="submit"
            :disabled="isSubmitting"
            class="px-5 py-2 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all cursor-pointer disabled:opacity-50"
          >
            {{ isSubmitting ? 'Mise à jour...' : 'Sauvegarder ✅' }}
          </button>
        </div>

      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watchEffect } from 'vue';
import { useModalStore } from '@/stores/useModalStore';
import { usePollStore } from '@/stores/usePollStore';

const props = defineProps({
  poll: { type: Object, required: true },
});

const { closeModal } = useModalStore();
const { updatePoll } = usePollStore();

const editablePoll = ref({});
const launchNow = ref(false);
const isSubmitting = ref(false);
const updateError = ref(null);

watchEffect(() => {
  editablePoll.value = { ...props.poll };
});

async function submitUpdate() {
  isSubmitting.value = true;
  updateError.value = null;

  try {
    await updatePoll(editablePoll.value.id, {
      question: editablePoll.value.question,
      title: editablePoll.value.title,
      results_public: editablePoll.value.results_public,
      is_draft: launchNow.value ? false : editablePoll.value.is_draft,
    });
    closeModal();
  } catch (e) {
    updateError.value = e.data?.message ?? 'Une erreur est survenue.';
  } finally {
    isSubmitting.value = false;
  }
}
</script>
