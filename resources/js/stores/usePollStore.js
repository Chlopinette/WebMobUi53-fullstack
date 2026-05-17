import { ref } from 'vue';
import { useFetchApi } from '@/composables/useFetchApi';

// Module-level state
const polls = ref([]);
const loading = ref(false);
const error = ref(null);

let hasFetched = false;

export function usePollStore() {
  const { fetchApi } = useFetchApi();

  async function fetchPolls() {
    if (hasFetched && polls.value.length > 0) {
      return;
    }

    loading.value = true;
    error.value = null;
    try {
      const data = await fetchApi({ url: '/polls', method: 'GET' });
      polls.value = data;
      hasFetched = true;
    } catch (e) {
      error.value = e;
      console.error("Failed to fetch polls:", e);
    } finally {
      loading.value = false;
    }
  }

  async function createPoll(pollData) {
    const newPoll = await fetchApi({
      url: '/polls',
      method: 'POST',
      data: pollData,
    });
    polls.value.unshift(newPoll); // Add to the beginning of the list
  }

  async function updatePoll(id, pollData) {
    const updatedPoll = await fetchApi({
      url: `/polls/${id}`,
      method: 'PUT',
      data: pollData,
    });
    const index = polls.value.findIndex(p => p.id === id);
    if (index !== -1) {
      polls.value[index] = updatedPoll;
    }
  }

  async function deletePoll(id) {
    try {
      await fetchApi({ url: 'polls/' + id, method: 'DELETE' });
      polls.value = polls.value.filter(p => p.id !== id);
    } catch (e) {
      console.error(`Failed to delete poll ${id}:`, e);
      // Optionally, set an error state for the UI
    }
  }

  async function vote(token, optionId) {
    await fetchApi({
      url: `/polls/${token}/vote`,
      method: 'POST',
      data: { option_id: optionId },
    });
  }

  // Automatically fetch polls when the store is first used
  fetchPolls();

  return { polls, loading, error, createPoll, updatePoll, deletePoll, fetchPolls, vote };
}
