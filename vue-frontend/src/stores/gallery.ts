import { useScopeQuery } from "@/core/store/scopeQuery";
import { Element } from "@/gql/gen";
import { defineStore } from "pinia";
import { computed, ref } from "vue";

const STORE_NAME = "modules.gallery";

export const useGalleryStore = defineStore(STORE_NAME, () => {
  const galleries = ref<Element[]>([]);
  const galleriesById = computed<Record<number, Element>>(() => {
    const map: Record<number, Element> = {};
    for (const it of galleries.value) {
      if (it.ID != null) map[it.ID] = it;
    }
    return map;
  });
  const galleriesByCode = computed<Record<string, Element>>(() => {
    const map: Record<string, Element> = {};
    for (const it of galleries.value) {
      if (it.CODE) map[it.CODE] = it;
    }
    return map;
  });

  const { registerScopeQuery } = useScopeQuery();

  registerScopeQuery(
    STORE_NAME,
    "app",
    {
      // After backend codegen, this will be `gallery_element_list`
      // Using generic element list via search or dedicated query name requires schema support.
      // For now, rely on gallery specific query name and types generation.
      gallery_element_list: {
        __fragment: "GalleryFields",
      } as any,
    } as any,
    (data: any) => {
      galleries.value = data.gallery_element_list;
    }
  );

  return {
    galleries,
    galleriesById,
    galleriesByCode,
  };
});
