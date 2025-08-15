<template>
  <StackItemModal :sheet="{}" :actionClose="false" :actions="actions" v-bind="bind" title="Выход">
    Вы действительно хотите выйти из своего аккаунта?
  </StackItemModal>
</template>
<script setup lang="ts">
import { userLogoutMutation } from "@/gql/gen/mutation/userLogoutMutation";
import { useGraphql } from "@/core/graphql/service";
import { StackComponentProps, useStackComponent } from "@/packages/stack-router/hooks/useStackComponent";
import { StackItemModalState } from "@/packages/stack-router/types";
import { ref } from "vue";

const { mutationWrapped } = useGraphql()

const props = withDefaults(defineProps<StackComponentProps & {

}>(), {})

const actions = [
  {
    label: 'Выйти',
    onClick: async () => {

      const res = await mutationWrapped(userLogoutMutation({
        state: {
          __fragment: 'ResponseState',
        }
      }, {}), true)

      //stackItem?.close()
    }
  }
]

const { bind, stackItem, stackItemState } = useStackComponent<StackItemModalState>(props)

</script>
<style></style>
