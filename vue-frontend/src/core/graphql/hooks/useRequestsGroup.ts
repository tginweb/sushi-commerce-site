import { computed, ref } from "vue";
import { GqlUserRequestReturn } from "../types";

export default function useRequestsGroup(requests: GqlUserRequestReturn[]) {

  const loading = computed(
    () => !!requests.find((request) => request.loading.value)
  );

  const loaded = computed(
    () => !!requests.find((request) => request.loaded.value)
  );

  const lastRequest = computed(() =>
    requests.sort((a, b) => b.startTimestamp.value - a.startTimestamp.value)
  );

  return {
    loading,
    loaded,
    lastRequest,
  };
}
