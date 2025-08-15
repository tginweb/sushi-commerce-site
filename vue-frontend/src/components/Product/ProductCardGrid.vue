<template>
  <div
      class="product"
      v-bind="bindContainer"
  >
    <a
        class="c-content bg-white"
        :is="bindLinkComponent"
        v-bind="bindLink"
        v-on="bindLinkListeners"
        @click.prevent.stop="onClick"
    >
      <div class="c-row-holder">
        <div :class="view.rowClass" class="c-row">
          <div :class="view.colMediaClass">
            <div :class="view.mediaClass" class="c-media flex">

              <div class="c-media__top q-px-sm q-pt-sm flex">
                <ProductFlags
                    :items="product.FLAG_ITEMS"
                    :product="product"
                    class=""
                />
                <ProductActions
                    :product="product"
                    class=" q-ml-auto"
                />
              </div>

              <div class="c-thumb q-my-auto full-width">
                <q-img
                    fit="contain"
                    :src="imageUrl"
                    class="c-thumb-img"
                />
              </div>
            </div>
          </div>
          <div :class="view.colMainClass" class="c-main">
            <div class="flex column q-px-sm q-ml-xs q-mx-md-none q-px-md-md q-pb-sm q-pb-md-md full-height">
              <div
                  class="c-info full-width cursor-pointer"
                  style="position:relative;"
              >
                <div class="c-header block q-mb-xs q-mb-md-sm">
                  <div>
                    {{product.ID}}
                    <h3 :class="view.title" class="c-title text-weight-bolder q-ma-none flex no-wrap q-pr-md">
                      {{ product.NAME }}
                    </h3>
                  </div>
                  <div
                      v-if="descTeaser"
                      class="q-mt-xs text-grey-6 s-font-xs s-font-md-sm"
                      v-html="descTeaser"
                  />
                  <ProductSetItems
                      :product="product"
                      :items="setItems"
                      v-if="!!setItems.length"
                  />

                  <div v-if="availableSchedule" class="c-available-schedule q-flex-gutter-xs s-font-xs q-mt-sm">
                    <div class="text-weight-medium">
                      Действует в:
                    </div>
                    <div
                        v-for="title of availableSchedule.titles"
                        :key="title"
                        class="text-red-8"
                    >
                      {{ title }}
                    </div>
                  </div>

                </div>
              </div>

              <div class="c-bottom q-mt-auto full-width">

                <div class="flex items-center no-wrap prevent-click">

                  <div class="flex items-center q-gutter-xs q-gutter-md-xs q-gutter-lg-sm q-gutter-md-sm col-grow">

                    <div class="c-price leading-none flex" v-if="priceAvailable">

                      <div
                          class="c-price__discounted leading-none text-weight-bold"
                          v-html="format(calc.price)"
                      />

                      <div
                          class="c-price__base price-old s-font-xs leading-none q-ml-sm"
                          v-html="format(calc.priceSource, true, true)"
                          v-if="calc.discountPercent"
                      />

                    </div>

                    <div
                        v-if="!inBasket && weight"
                        class="c-measure text-grey-7 leading-none s-font-2xs s-font-sm-xs s-font-md-xs s-font-lg-sm s-font-xl-sm"
                    >
                      / {{ weight }} гр
                    </div>

                  </div>

                  <q-no-ssr>
                    <div class="c-sale q-ml-auto">

                      <div
                          v-if="!inBasket"
                          class="c-basket-add q-px-xs q-py-xs s-font-3xl s-font-md-3xl s-font-lg-3xl text-primary"
                          @click.stop.prevent="onBasketAdd"
                      >
                        <q-spinner v-if="basketMutating" size="12px"/>
                        <svg v-else>
                          <use xlink:href="/icons.svg#basket"></use>
                        </svg>
                      </div>

                      <QuantityInput
                          v-else
                          :model-value="basketItem.fields.QUANTITY"
                          @update:model-value="onBasketQuantity"
                          color="primary"
                          container-border-color="#ce8714"
                          :show-action-tooltip="true"
                          control-class="q-gutter-sm"
                          :input-disable="true"
                      />

                    </div>
                  </q-no-ssr>

                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
</template>

<script setup lang="ts">
import {ProductCardProps, useProductView} from "./hooks";
import {useCurrency} from "@/modules/shop/store/currency";
import {ProductBenefit} from "@/gql/gen";
import Result from "@/core/classes/Result";
import {useAlerts} from "@/core/store/alerts";
import {useMounted} from "@vueuse/core";
import QuantityInput from "@/components/QuantityInput.vue";
import ProductSetItems from "./SetItems.vue";
import ProductFlags from "./Flags.vue";
import ProductActions from "./Actions.vue";
import {onMounted, onUpdated} from "vue";

const props = withDefaults(defineProps<ProductCardProps>(), {
  borderStyle: 'all',
  orientation: 'vert',
  viewMode: 'grid',
})

const {} = props

const {
  onClick,
  product,
  onBasketAdd,
  onBasketQuantity,
  weight,
  basketItem,
  inBasket,
  bindContainer,
  view,
  flags,
  imageUrl,
  bindLinkComponent,
  bindLink,
  bindLinkListeners,
  descTeaser,
  basketMutating,
  priceAvailable,
  calc,
  setItems,
  onMouseEnter,
  onMouseLeave,
  hover,
  availableSchedule,
  now
} = useProductView(props, {

  calcOptions: {
    round: true
  },
  viewModes: {
    'list-hor': {
      rowClass: 'row full-height',
      colMediaClass: 'col-7 col-md-24 col-lg-11',
      colMainClass: 'col-17 col-md-grow col-lg-13 q-mt-md',
      mediaClass: '',
      title: 's-font-lg s-font-md-xl s-font-lg-3xl'
    },
    'grid-hor': {
      rowClass: 'row full-height q-col-gutter-x-md',
      colMediaClass: 'col-24 col-md-11',
      colMainClass: 'col-24 col-md-13 q-mt-sm ',
      mediaClass: 'full-height',
      title: 's-font-lg s-font-md-xl s-font-lg-3xl'
    },

    'list-vert': {
      rowClass: 'row  q-col-gutter-md-none full-height ',
      colMediaClass: 'col-7 col-md-auto',
      colMainClass: 'col-17 col-md-grow q-mt-sm q-mt-md-md',
      mediaClass: 'full-height',
      title: 's-font-md s-font-md-lg'
    },
    'grid-vert': {
      rowClass: 'column full-height',
      colMediaClass: 'col-auto',
      colMainClass: 'col-grow q-mt-sm q-mt-md-md',
      mediaClass: '',
      title: 's-font-md s-font-md-lg'
    },
  }
})

const {format} = useCurrency()
const {showAlerts} = useAlerts()

const setSpecial = (benefit: ProductBenefit, quantity: number) => {
  const result = new Result()
  basketItem.value.setSpecial(benefit.PRODUCT_ID, quantity, benefit, result)
  if (!result.isSuccess()) {
    showAlerts(result.messages)
  }
}
onUpdated(() => {
  //console.log('onUpdated', product.value.ID)
})

onMounted(() => {
//  console.log('onMounted', product.value.ID)
})

</script>

<style scoped lang="scss">

.c-basket-add {
  cursor: pointer;
  border: 1px solid currentColor;
  border-radius: 8px;
  line-height: 0;

  svg,
  img {
    width: 1em;
    height: 1em;
    fill: currentColor;
  }
}


.c-row-holder {
  height: 100%;
  position: relative;
}

.c-content {
  height: 100%;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  border-radius: 10px;
  border: 1px solid #eee;
}


@media (max-width: $breakpoint-xs-max) {
  .c-thumb-img {
    height: 158px;
  }
}

@media (max-width: $breakpoint-sm-max) {

  .c-flag-icon {
    max-width: 26px !important;
  }

  .--viewmode-list.--orientation-vert {
    .c-thumb-img {
      height: 100px;
    }

    .c-favs {
      bottom: 10px;
      left: 0;
      right: auto;
      top: auto;
    }
  }

  .--viewmode-grid.--orientation-vert {
    .c-thumb-img {
      height: 170px;
    }
  }

  .--orientation-hor {
    .c-thumb-img {
      height: 150px;
    }
  }
}

.c-more {
  opacity: 0;
  transform: scale(0.3);
  transition: all 0.6s;
}

.c-media {
  position: relative;
  background-color: #efefef;
}

.c-media__top {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 2;
}

.c-thumb {
  position: relative;
  overflow: hidden;
}

.c-thumb-img {
  height: 200px;
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center center;
  transition: transform 3s;
}

.c-product-actions {
  opacity: 0;
  transition: opacity 1s;
}

.product {

  &:hover {
    .c-product-actions {
      opacity: 1;
    }

    .c-more {
      opacity: 0.92;
      transform: scale(1);
    }
  }

  &.--orientation-vert-- {
    @media (min-width: $breakpoint-sm-max) {
      .c-row {
        flex-direction: column;
      }
    }
  }

  &.--orientation-hor-- {

    .c-info {
      top: 0px
    }

    .c-title {
      line-height: 1.1em;
    }

    @media (max-width: $breakpoint-sm-max) {
      .c-content {
        -padding-left: 16px;
        -padding-right: 16px;
      }
    }

  }

  @media (max-width: $breakpoint-sm-max) {
    .c-title {
      line-height: 1.3em;
    }
  }

  @media (min-width: $breakpoint-lg-min) {
    &:hover {
      .c-thumb-img {
        transform: scale(1.3);
      }
    }
  }

}

.c-price__discounted {
  font-size: 17px;
}

</style>
