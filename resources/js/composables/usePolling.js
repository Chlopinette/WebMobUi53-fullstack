import { onMounted, onUnmounted } from 'vue';

/**
 * Polling composable
 *
 * @param {Function} fn - The function to call on each interval tick
 * @param {number} [interval=5000] - The interval in milliseconds
 * @param {boolean} [immediate=true] - Whether to run the function immediately on mount
 */
export function usePolling(fn, interval = 5000, immediate = true) {
  let timer;

  onMounted(() => {
    if (immediate) {
      fn();
    }
    timer = setInterval(fn, interval);
  });

  onUnmounted(() => {
    if (timer) {
      clearInterval(timer);
    }
  });
}
