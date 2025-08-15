<template>
  <div class="q-pt-sm">
    <div class="q-gutter-md">

      <div class="q-gutter-y-sm">

        <ButtonsToggle
            v-model="attrs.DELIVERY_ID"
            option-value="ID"
            option-label="NAME"
            :props="{
              class: 'q-px-md col-grow',
              size: '15px',
              unelevated: true,
              dense: true
            }"
            :props-active="{
              color: 'secondary'
            }"
            :props-inactive="{
              outline: false,
              color: 'grey-3',
              textColor: 'black'
            }"
            :options="deliveries"
            class="flex q-gutter-x-md justify-center no-wrap"
        />
      </div>

      <div class="с-sections" v-if="attrs.DELIVERY_ID">

        <template v-if="attrs.DELIVERY_ID === 1">
          <SectionDeliveryCourier
              code="delivery-courier"
          />
        </template>
        <template v-else>
          <SectionDeliveryPickup
              code="delivery-courier"
          />
        </template>

        <SectionTime
            code="time"
        />

      </div>

      <div class="с-sections q-mb-md" v-if="attrs.DELIVERY_ID">

        <SectionPayment
            code="payment"
        />

        <SectionBonus
            code="bonus"
        />

        <SectionPromocode
            code="promocode"
        />

        <SectionCultery
            code="cultery"
        />

        <SectionComment
            code="comment"
        />

      </div>


    </div>
  </div>
</template>

<script setup lang="ts">

import SectionTime from "@/components/VOrder/Section/Time.vue";
import SectionDeliveryCourier from "@/components/VOrder/Section/DeliveryCourier.vue"
import SectionDeliveryPickup from "@/components/VOrder/Section/DeliveryPickup.vue"
import SectionCultery from "@/components/VOrder/Section/Cultery.vue"
import SectionPromocode from "@/components/VOrder/Section/Promocode.vue"
import SectionBonus from "@/components/VOrder/Section/Bonus.vue"
import SectionPayment from "@/components/VOrder/Section/Payment.vue"

import {onMounted} from "vue";
import {injection} from "@/core/util/vue/injection";
import {VOrderValidateInject} from "./constants";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import ButtonsToggle from "@/components/ButtonsToggle.vue";
import SectionComment from "./Section/Comment.vue";

const vorderStore = useVorderStore()
const {setAttr} = vorderStore

const {
  attrs,
  deliveries,
} = storeToRefs(vorderStore)

const validate = injection(VOrderValidateInject)

onMounted(() => {

})

const onSubmit = async () => {
  if (await validate()) {
    console.log('OK')
  }
}

</script>

<style scoped lang="scss">

.с-sections {
  background-color: rgba(28, 28, 28, 0.05);
  border-radius: 14px;
  padding: 2px 0 0 0;
  overflow: hidden;;

  :deep() {
    .c-section:not(:last-child) {
      border-bottom: 1px solid #DDDDDD;
    }
  }
}

</style>
