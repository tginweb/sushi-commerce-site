<template>
  <div class="">

    <ProgressInnerLoading
        :model-value="status"
        :reserve-height="true"
        :repeat-callback="query"
    >
      <div class="q-gutter-md" v-for="order in result" :key="order.ID">
        <OrderHistory
            :order="order"
        />
      </div>

    </ProgressInnerLoading>

  </div>
</template>
<script setup lang="ts">
import {onMounted, ref, toRefs} from "vue";
import saleOrderHistoryListQuery from "@/gql/gen/query/order";
import {useGraphql} from "@/core/graphql/service";
import ProgressInnerLoading from "@/components/Progress/ProgressInnerLoading.vue";
import OrderHistory from "@/components/Order/OrderHistory.vue";

const props = withDefaults(defineProps<{
  id?: string
}>(), {})

const {id} = toRefs(props)

const {useQuery} = useGraphql()

const {query, result, loading, status} = useQuery(saleOrderHistoryListQuery({
  __fragment: 'OrderFields',
}), {
})


onMounted(() => {

})

</script>
<style>

</style>
