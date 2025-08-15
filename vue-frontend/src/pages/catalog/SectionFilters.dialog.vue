<template>
  <StackItemModal
      v-bind="bind"
      :title="title"
      :dialog="{

      }"
  >
    FILTERS {{ sectionId}}
  </StackItemModal>
</template>
<script setup lang="ts">
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {ref} from "vue";
import {useCatalogStore} from "@/modules/shop/store/catalog";

export type SectionFiltersDialogProps = {
  sectionId: number,
  selectedPrimaryId: number | null,
  selectedSecondaryIds: number[],
}

const props = withDefaults(defineProps<StackComponentProps & SectionFiltersDialogProps>(), {})

const {sectionId, selectedPrimaryId, selectedSecondaryIds} = props

const title = ref('Фильтры')

const {bind} = useStackComponent<StackItemModalState>(props)

const catalog = useCatalogStore()

</script>
<style>

</style>
