<template>
  <div class="min-h-screen bg-yellow-300 font-black p-4">

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <p class="text-2xl font-black uppercase">Chargement... ⚡</p>
    </div>

    <!-- Erreur -->
    <div v-else-if="error" class="max-w-2xl mx-auto mt-10">
      <div class="bg-red-400 border-4 border-black shadow-[6px_6px_0px_black] p-6 text-center">
        <p class="text-2xl font-black uppercase text-black">Sondage introuvable 😢</p>
      </div>
    </div>

    <!-- Sondage en brouillon -->
    <div v-else-if="poll && poll.is_draft" class="max-w-2xl mx-auto mt-10">
      <div class="bg-white border-4 border-black shadow-[6px_6px_0px_black] p-6 text-center">
        <p class="text-4xl mb-4">🔒</p>
        <p class="text-2xl font-black uppercase">Ce sondage n'est pas encore disponible</p>
      </div>
    </div>

    <!-- Sondage disponible -->
    <div v-else-if="poll" class="max-w-2xl mx-auto">

      <!-- Header -->
      <div class="text-center mb-8 pt-6">
        <h1 class="text-5xl font-black text-black uppercase leading-none tracking-tighter">
          ⚡ SONDAPÉROOO
        </h1>
      </div>

      <!-- Card principale -->
      <div class="bg-white border-4 border-black shadow-[8px_8px_0px_black] p-6 mb-6">

        <!-- Question -->
        <div class="mb-6">
          <h2 class="text-3xl font-black text-black uppercase leading-tight">
            {{ poll.question }}
          </h2>
          <p v-if="poll.title" class="text-black/60 font-bold uppercase text-sm mt-1">
            {{ poll.title }}
          </p>
        </div>

        <!-- Badge statut -->
        <div class="flex gap-2 mb-6">
          <span v-if="isExpired"
            class="text-xs px-2 py-1 bg-black text-red-400 font-black uppercase border-2 border-black">
            🔴 Terminé
          </span>
          <span v-else
            class="text-xs px-2 py-1 bg-black text-lime-400 font-black uppercase border-2 border-black">
            🟢 Ouvert
          </span>
          <span v-if="poll.ends_at && !isExpired"
            class="text-xs px-2 py-1 bg-yellow-300 text-black font-black uppercase border-2 border-black">
            ⏱ {{ timeLeft }}
          </span>
        </div>

        <!-- Pendant la vérification — on n'affiche rien -->
        <div v-if="isAuthenticated === null" class="text-center py-4">
            <p class="font-black uppercase text-black/50 text-sm">Vérification...</p>
        </div>

        <!-- CAS 1 : Non connecté -->
        <div v-else-if="isAuthenticated === false">
          <div v-if="poll.results_public">
            <p class="text-sm font-black uppercase text-black/60 mb-4">
              Connecte-toi pour voter 👇
            </p>

              <a href="/auth/login"
              class="block text-center py-3 mb-6 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all"
            >
              Se connecter pour voter 🔑
            </a>
            <div class="border-t-4 border-black pt-4">
              <h3 class="text-xl font-black uppercase text-black mb-4">Résultats 📊</h3>
              <div class="space-y-3 mb-6">
                <div
                  v-for="(option, i) in poll.options"
                  :key="option.id"
                  class="border-4 border-black p-3"
                >
                  <div class="flex justify-between text-sm font-black uppercase text-black mb-2">
                    <span>{{ option.text }}</span>
                    <span>{{ percentage(option) }}% ({{ option.votes_count ?? 0 }})</span>
                  </div>
                  <div class="w-full bg-gray-200 border-2 border-black h-5">
                    <div
                      :class="barColors[i % barColors.length]"
                      class="h-full border-r-2 border-black transition-all duration-500"
                      :style="{ width: percentage(option) + '%' }"
                    ></div>
                  </div>
                </div>
              </div>
              <div class="border-4 border-black p-4 bg-gray-50">
                <h4 class="text-sm font-black uppercase text-black mb-3">Vue graphique</h4>
                <canvas ref="chartCanvas"></canvas>
              </div>
            </div>
          </div>
          <div v-else>
            <div class="border-4 border-black p-6 text-center bg-gray-50">
              <p class="text-4xl mb-3">🔒</p>
              <p class="font-black uppercase text-black mb-4">
                Connecte-toi pour participer
              </p>

                <a href="/auth/login"
                class="inline-block px-6 py-3 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all"
              >
                Se connecter 🔑
              </a>
            </div>
          </div>
        </div>

        <!-- CAS 2 : Connecté -->
        <div v-else>
          <!-- Formulaire de vote -->
          <div v-if="!hasVoted && !isExpired">
            <p class="text-sm font-black uppercase text-black/60 mb-3">
              {{ poll.allow_multiple_choices ? 'Plusieurs choix possibles' : 'Un seul choix' }}
            </p>

            <div class="space-y-3 mb-6">
              <label
                v-for="(option, i) in poll.options"
                :key="option.id"
                :class="[optionColors[i % optionColors.length], isSelected(option.id) ? 'ring-4 ring-black' : '']"
                class="flex items-center gap-3 p-4 border-4 border-black cursor-pointer shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all"
              >
                <input
                  v-if="poll.allow_multiple_choices"
                  type="checkbox"
                  :value="option.id"
                  v-model="selectedOptions"
                  class="w-4 h-4 accent-black"
                >
                <input
                  v-else
                  type="radio"
                  :value="option.id"
                  v-model="selectedOption"
                  class="w-4 h-4 accent-black"
                >
                <span class="text-black font-black uppercase">{{ option.text }}</span>
              </label>
            </div>

            <div v-if="voteError" class="bg-red-400 border-4 border-black p-3 mb-4">
              <p class="font-black uppercase text-black text-sm">⚠ {{ voteError }}</p>
            </div>

            <button
              @click="submitVote"
              :disabled="isSubmitting || (!selectedOption && selectedOptions.length === 0)"
              class="w-full py-3 bg-pink-400 text-black font-black uppercase text-lg border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-[4px_4px_0px_black] disabled:translate-x-0 disabled:translate-y-0"
            >
              {{ isSubmitting ? 'Envoi...' : 'Voter 🗳️' }}
            </button>
          </div>

          <!-- Résultats pour connecté -->
          <div v-if="hasVoted || isExpired">
            <div v-if="hasVoted" class="mb-4 bg-lime-400 border-4 border-black p-4 text-center">
              <p class="font-black uppercase text-black">✅ Tu as déjà voté !</p>
            </div>
            <div v-if="isExpired && !hasVoted" class="mb-4 bg-red-400 border-4 border-black p-4 text-center">
              <p class="font-black uppercase text-black">🔴 Ce sondage est terminé</p>
            </div>

            <h3 class="text-xl font-black uppercase text-black mb-4 border-t-4 border-black pt-4">
              Résultats 📊
            </h3>

            <div class="space-y-3 mb-6">
              <div
                v-for="(option, i) in poll.options"
                :key="option.id"
                class="border-4 border-black p-3"
              >
                <div class="flex justify-between text-sm font-black uppercase text-black mb-2">
                  <span :class="option.id === poll.user_vote_option_id ? 'text-pink-600' : ''">
                    {{ option.text }}
                    <span v-if="option.id === poll.user_vote_option_id" class="text-xs">← ton vote</span>
                  </span>
                  <span>{{ percentage(option) }}% ({{ option.votes_count ?? 0 }})</span>
                </div>
                <div class="w-full bg-gray-200 border-2 border-black h-5">
                  <div
                    :class="barColors[i % barColors.length]"
                    class="h-full border-r-2 border-black transition-all duration-500"
                    :style="{ width: percentage(option) + '%' }"
                  ></div>
                </div>
              </div>
            </div>

            <div class="border-4 border-black p-4 bg-gray-50">
              <h4 class="text-sm font-black uppercase text-black mb-3">Vue graphique</h4>
              <canvas ref="chartCanvas"></canvas>
            </div>
          </div>

        </div>
      </div>

      <!-- Share Link -->
      <div class="text-center pb-6">
        <p class="text-black/50 font-bold uppercase text-xs mb-2">
          Partage ce sondage !
        </p>
        <div class="flex justify-center">
          <input type="text" :value="pollUrl" readonly class="p-2 border-2 border-black text-center text-sm">
          <button @click="copyToClipboard(pollUrl)" class="bg-black text-white px-4 py-2 text-sm font-bold uppercase">
            Copier
          </button>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center pb-6">
        <p class="text-black/50 font-bold uppercase text-xs">
          ⚡ SONDAPÉROOO — VOTE. DÉBATS. RÉPÈTE.
        </p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue';
import { useFetchApi } from '@/composables/useFetchApi';
import { usePolling } from '@/composables/usePolling';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const { fetchApi } = useFetchApi();

const poll = ref(null);
const loading = ref(true);
const error = ref(null);
const selectedOption = ref(null);
const selectedOptions = ref([]);
const isSubmitting = ref(false);
const voteError = ref(null);
const chartCanvas = ref(null);
const isAuthenticated = ref(null);
let chartInstance = null;

const optionColors = ['bg-pink-400', 'bg-cyan-400', 'bg-orange-400', 'bg-lime-400', 'bg-violet-400'];
const barColors = ['bg-pink-400', 'bg-cyan-400', 'bg-orange-400', 'bg-lime-400', 'bg-violet-400'];

const token = window.location.pathname.split('/').pop();
const pollUrl = window.location.href;

const isExpired = computed(() => poll.value?.is_expired ?? false);

const hasVoted = computed(() => poll.value?.has_voted ?? false);

const showResults = computed(() => {
  if (!poll.value) return false;
  return poll.value.results_public || hasVoted.value || isExpired.value;
});

const timeLeft = computed(() => {
  if (!poll.value?.ends_at) return '';
  const diff = new Date(poll.value.ends_at) - new Date();
  if (diff <= 0) return 'Terminé';
  const h = Math.floor(diff / 3600000);
  const m = Math.floor((diff % 3600000) / 60000);
  const s = Math.floor((diff % 60000) / 1000);
  if (h > 0) return `${h}h ${m}m restantes`;
  if (m > 0) return `${m}m ${s}s restantes`;
  return `${s}s restantes`;
});

function isSelected(optionId) {
  if (poll.value?.allow_multiple_choices) {
    return selectedOptions.value.includes(optionId);
  }
  return selectedOption.value === optionId;
}

function totalVotes() {
  if (!poll.value?.options) return 0;
  return poll.value.options.reduce((sum, o) => sum + (o.votes_count ?? 0), 0);
}

function percentage(option) {
  const total = totalVotes();
  if (total === 0) return 0;
  return Math.round(((option.votes_count ?? 0) / total) * 100);
}

async function fetchPoll() {
  try {
    const data = await fetchApi({ url: `/polls/${token}` });
    poll.value = data;
  } catch (e) {
    error.value = e;
  } finally {
    loading.value = false;
  }
}

async function submitVote() {
  isSubmitting.value = true;
  voteError.value = null;

  try {
    if (poll.value.allow_multiple_choices) {
      if (selectedOptions.value.length === 0) {
        voteError.value = 'Sélectionne au moins une option.';
        isSubmitting.value = false;
        return;
      }
      await fetchApi({
        url: `/polls/${token}/vote`,
        method: 'POST',
        data: { option_ids: selectedOptions.value },
      });
    } else {
      if (!selectedOption.value) {
        voteError.value = 'Sélectionne une option avant de voter.';
        isSubmitting.value = false;
        return;
      }
      await fetchApi({
        url: `/polls/${token}/vote`,
        method: 'POST',
        data: { option_id: selectedOption.value },
      });
    }
    await fetchPoll();
  } catch (e) {
    voteError.value = e.data?.message ?? 'Une erreur est survenue.';
  } finally {
    isSubmitting.value = false;
  }
}

function updateChart() {
  if (!chartCanvas.value || !poll.value?.options) return;

  const labels = poll.value.options.map(o => o.text);
  const data = poll.value.options.map(o => o.votes_count ?? 0);
  const colors = ['#f472b6', '#22d3ee', '#fb923c', '#a3e635', '#a78bfa'];

  if (chartInstance) {
    chartInstance.data.labels = labels;
    chartInstance.data.datasets[0].data = data;
    chartInstance.update();
    return;
  }

  chartInstance = new Chart(chartCanvas.value, {
    type: 'doughnut',
    data: {
      labels,
      datasets: [{
        data,
        backgroundColor: colors,
        borderColor: '#000',
        borderWidth: 3,
      }],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          labels: {
            font: { weight: 'bold', family: 'monospace' },
          },
        },
      },
    },
  });
}

function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(() => {
    alert('Link copied to clipboard!');
  }).catch(err => {
    console.error('Failed to copy text: ', err);
  });
}

usePolling(async () => {
  if (!poll.value || poll.value.is_draft) return;
  try {
    const data = await fetchApi({ url: `/polls/${token}` });
    poll.value = data;
  } catch {}
}, 5000);

watch(
  () => poll.value?.options,
  async () => {
    if (showResults.value) {
      await nextTick();
      updateChart();
    }
  },
  { deep: true }
);

onMounted(async () => {
  await fetchPoll();
  try {
    const response = await fetch('/api/user', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XmlHttpRequest',
      },
      credentials: 'include',
    });
    isAuthenticated.value = response.ok;
  } catch {
    isAuthenticated.value = false;
  }
});

onUnmounted(() => {
  if (chartInstance) chartInstance.destroy();
});
</script>
