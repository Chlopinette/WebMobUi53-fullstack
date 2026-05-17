import { ref } from 'vue';

const isModalOpen = ref(false);
const modalComponent = ref(null);
const modalProps = ref({});

export function useModalStore() {
  function openModal(component, props = {}) {
    modalComponent.value = component;
    modalProps.value = props;
    isModalOpen.value = true;
  }

  function closeModal() {
    isModalOpen.value = false;
    modalComponent.value = null;
    modalProps.value = {};
  }

  return {
    isModalOpen,
    modalComponent,
    modalProps,
    openModal,
    closeModal,
  };
}
