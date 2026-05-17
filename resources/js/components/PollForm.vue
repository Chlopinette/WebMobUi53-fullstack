<template>
  <div class="bg-white border-4 border-black shadow-[8px_8px_0px_black] p-6 mb-8">

    <h3 class="text-3xl font-black uppercase text-black mb-6">
      Lance un sondage 🎯
    </h3>

    <!-- Erreur globale -->
    <div v-if="formError" class="bg-red-400 border-4 border-black p-3 mb-4">
      <p class="font-black uppercase text-black text-sm">⚠ {{ formError }}</p>
    </div>

    <form @submit.prevent="submitPoll">

      <!-- Question -->
      <div class="mb-5">
        <label for="question" class="block text-sm font-black text-black uppercase mb-2 tracking-wide">
          Ta question 💬
        </label>
        <input
          id="question"
          v-model="newPoll.question"
          type="text"
          placeholder="Ex : Quelle pizza est la meilleure ?"
          class="w-full px-4 py-3 border-4 border-black font-bold text-black placeholder-gray-400 focus:outline-none focus:border-pink-500"
          required
        >
      </div>

      <!-- Options -->
      <div class="mb-5">
        <label class="block text-sm font-black text-black uppercase mb-2 tracking-wide">
          Les options 🎲
        </label>
        <p class="text-xs font-bold text-black/60 uppercase mb-3">
          Minimum 2 options requises
        </p>
        <div
          v-for="(option, index) in newPoll.options"
          :key="index"
          class="flex items-center mb-2 gap-2"
        >
          <input
            v-model="newPoll.options[index]"
            type="text"
            :placeholder="'Option ' + (index + 1)"
            :class="['w-full px-4 py-3 border-4 font-bold text-black placeholder-gray-400 focus:outline-none', optionBorderColors[index % optionBorderColors.length]]"
            required
          >
          <button
            v-if="newPoll.options.length > 2"
            @click.prevent="removeOption(index)"
            class="px-3 py-3 bg-red-500 text-white font-black border-4 border-black shadow-[3px_3px_0px_black] hover:shadow-none hover:translate-x-[3px] hover:translate-y-[3px] transition-all"
          >
            ✕
          </button>
        </div>
        <button
          @click.prevent="addOption"
          class="mt-2 px-4 py-2 bg-cyan-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all"
        >
          + Ajouter une option
        </button>
      </div>

      <!-- Paramètres -->
      <div class="mb-6 border-4 border-black p-4 bg-yellow-50">
        <p class="text-sm font-black uppercase text-black mb-3">Paramètres ⚙️</p>

        <label class="flex items-center gap-3 mb-3 cursor-pointer">
          <input type="checkbox" v-model="newPoll.allow_multiple_choices" class="w-5 h-5 accent-pink-500 border-2 border-black">
          <span class="font-bold uppercase text-sm text-black">Choix multiples autorisés</span>
        </label>

        <label class="flex items-center gap-3 mb-3 cursor-pointer">
          <input type="checkbox" v-model="newPoll.results_public" class="w-5 h-5 accent-pink-500 border-2 border-black">
          <span class="font-bold uppercase text-sm text-black">Résultats publics</span>
        </label>

        <div>
          <label class="block text-sm font-bold uppercase text-black mb-1">
            Durée (en minutes, optionnel)
          </label>
          <input
            v-model="newPoll.duration_minutes"
            type="number"
            min="1"
            placeholder="Ex : 10"
            class="w-full px-4 py-2 border-4 border-black font-bold text-black placeholder-gray-400 focus:outline-none focus:border-pink-500"
          >
        </div>
      </div>

      <!-- Actions -->
      <div class="flex gap-3 pt-4 border-t-4 border-black">
        <button
          type="submit"
          :disabled="isSubmitting"
          class="flex-1 py-3 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all cursor-pointer disabled:opacity-50"
        >
          {{ isSubmitting ? 'Création...' : 'Créer le sondage 🚀' }}
        </button>
      </div>

    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePollStore } from '@/stores/usePollStore';

const { createPoll } = usePollStore();

const optionBorderColors = ['border-pink-500', 'border-cyan-500', 'border-orange-500', 'border-lime-500', 'border-violet-500'];

const newPoll = ref({
  question: '',
  options: ['', ''],
  allow_multiple_choices: false,
  results_public: false,
  duration_minutes: null,
});

const isSubmitting = ref(false);
const formError = ref(null);

function addOption() {
  newPoll.value.options.push('');
}

function removeOption(index) {
  newPoll.value.options.splice(index, 1);
}

async function submitPoll() {
  isSubmitting.value = true;
  formError.value = null;

  const cleanOptions = newPoll.value.options.filter(o => o.trim() !== '');

  if (!newPoll.value.question.trim()) {
    formError.value = 'La question est obligatoire.';
    isSubmitting.value = false;
    return;
  }

  if (cleanOptions.length < 2) {
    formError.value = 'Il faut au moins 2 options.';
    isSubmitting.value = false;
    return;
  }

  try {
    await createPoll({
      question: newPoll.value.question,
      options: cleanOptions,
      allow_multiple_choices: newPoll.value.allow_multiple_choices,
      results_public: newPoll.value.results_public,
      duration: newPoll.value.duration_minutes ? newPoll.value.duration_minutes * 60 : null,
    });

    // Reset
    newPoll.value = {
      question: '',
      options: ['', ''],
      allow_multiple_choices: false,
      results_public: false,
      duration_minutes: null,
    };
  } catch (e) {
    formError.value = e.data?.message ?? 'Une erreur est survenue.';
  } finally {
    isSubmitting.value = false;
  }
}
</script>
