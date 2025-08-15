<template>
  <div class="product" v-bind="bindContainer">
    <a class="c-content bg-white" :is="bindLinkComponent" v-bind="bindLink" v-on="bindLinkListeners"
      @click.prevent.stop="onClick">

      <div class="c-row-holder">
        <div :class="view.rowClass" class="c-row">
          <div :class="view.colMediaClass">
            <div :class="view.mediaClass" class="c-media flex">
              <div v-if="flags.length" class="c-flags inline-block s-font-3xs s-font-md-xs q-gutter-sm">
                <div v-for="flag of flags" :key="flag.CODE" class="c-flags-item">
                  <div :class="{
                    ['bg-' + flag.COLOR]: true
                  }" class="c-flag leading-none text-white text-center q-px-xs q-py-xs" v-if="!flag.ICON">
                    <span class="gt-sm">{{ flag.NAME }}</span>
                    <span class="lt-md">{{ flag.NAME_SHORT || flag.NAME }}</span>
                  </div>
                  <div v-else class="c-flag-icon" :title="flag.NAME">
                    <img :src="flag.ICON" />
                  </div>
                </div>
              </div>
              <div class="c-favs q-ml-auto flex q-gutter-xs q-mr-xs q-mt-xs">
              </div>
              <div class="c-thumb q-my-auto full-width">
                <q-img fit="contain" v-bind="bindLink" :src="imageUrl" class="c-thumb-img" />
              </div>
            </div>
          </div>
          <div :class="view.colMainClass" class="c-main">
            <div class="flex column q-px-sm q-ml-xs q-mx-md-none q-px-md-md q-pb-sm q-pb-md-md full-height">
              <div class="c-info full-width cursor-pointer" style="position:relative;">
                <div class="c-header block q-mb-xs q-mb-md-sm">
                  <div v-bind="bindLink">
                    <h3 :class="view.title" class="c-title text-weight-bolder q-ma-none flex no-wrap q-pr-md">
                      {{ product.NAME }}
                    </h3>
                  </div>
                  <div v-if="descTeaser" class="q-mt-xs text-grey-6 s-font-xs s-font-md-sm" v-html="descTeaser" />
                </div>
              </div>
              <div class="c-bottom q-mt-auto full-width">

                <div class="flex items-center no-wrap">

                  <div class="flex items-center q-gutter-xs q-gutter-md-xs q-gutter-lg-sm q-gutter-md-sm col-grow">

                    <div class="c-price leading-none flex" v-if="priceAvailable">

                      <div
                        class="c-price__discounted s-font-sm s-font-md-md s-font-lg-lg s-font-xl-lg leading-none text-weight-bold">
                        {{ calc.priceDiscounted }}
                      </div>

                    </div>

                    <div v-if="(!basketItem.commited || $q.screen.gt.md) && weight"
                      class="c-measure text-grey-7 leading-none s-font-2xs s-font-sm-xs s-font-md-xs s-font-lg-sm s-font-xl-md">
                      / {{ weight }} гр.
                    </div>

                  </div>

                  <div v-if="mounted" class="c-sale q-ml-auto">

                    <div v-if="!basketItem.commited"
                      class="c-basket-add q-px-xs q-py-xs s-font-3xl s-font-md-3xl s-font-lg-3xl text-primary"
                      @click.stop="onBasketAdd">
                      <q-spinner v-if="basketMutating" size="12px" />
                      <svg v-else>
                        <use xlink:href="/icons.svg#basket"></use>
                      </svg>
                    </div>

                    <QuantityInput v-else :model-value="basketItem.fields.QUANTITY"
                      @update:model-value="onBasketQuantity" :btnOutline="true" :show-action-tooltip="true"
                      btnSize="14px" class="c-quantity" />

                  </div>

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
import { ProductCardProps, useProductView } from "./hooks";
import { useCurrency } from "@/modules/shop/store/currency";
import { ProductBenefit } from "@/gql/gen";
import Result from "@/core/classes/Result";
import { useAlerts } from "@/core/store/alerts";
import { useMounted } from "@vueuse/core";
import QuantityInput from "@/components/QuantityInput.vue";

const props = withDefaults(defineProps<ProductCardProps>(), {
  borderStyle: 'all',
  orientation: 'vert',
  viewMode: 'grid',
})

const { } = props

const {
  product,
  onClick,
  onBasketAdd,
  onBasketQuantity,
  weight,
  basketItem,
  bindContainer,
  view,
  flags,
  imageUrl,
  descTeaser,
  basketMutating,
  priceAvailable,
  calc,
  bindLinkComponent,
  bindLink,
  bindLinkListeners,
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

const onOpen = () => {
  // stackRouter.getStack().push()
}

const { format } = useCurrency()
const { showAlerts } = useAlerts()

const setSpecial = (benefit: ProductBenefit, quantity: number) => {
  const result = new Result()
  basketItem.value.setSpecial(benefit.PRODUCT_ID, quantity, benefit, result)
  if (!result.isSuccess()) {
    showAlerts(result.messages)
  }
}

const mounted = useMounted()


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

.c-favs {
  position: absolute;
  width: auto;
  right: 0;
  top: -4px;
  z-index: 1;
  border-radius: 6px;
  overflow: hidden;

  .c-btn {
    border-radius: 8px;
    line-height: 0;
    background-color: #fff;
    transition: all 0.4s;
    opacity: 0;
    cursor: pointer;

    &.--active {
      opacity: 0.85;
    }

    svg,
    img {
      width: 1em;
      height: 1em;
      fill: currentColor;
    }
  }
}


@media (max-width: $breakpoint-sm-max) {
  .c-content {
    -box-shadow: 2px 1px 13px -4px rgba(34, 60, 80, 0.15) !important;
  }
}

.product {


  &:hover {

    .c-favs {
      .c-btn {
        opacity: 0.85;
      }
    }

    .c-more {
      opacity: 0.92;
      transform: scale(1);
    }
  }

  &.--orientation-vert {
    @media (min-width: $breakpoint-sm-max) {
      .c-row {
        flex-direction: column;
      }
    }
  }

  &.--orientation-hor {

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

.c-media {
  min-height1: 120px;
  position: relative;
  background-color: #efefef;
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

.c-flags {
  position: absolute;
  width: 100%;
  left: 7px;
  top: 7px;
  z-index: 1;
}

.c-flags-item {
  display: inline-block;
  vertical-align: middle;
}

.c-flag {
  border-radius: 6px;
  display: inline-block;
}

.c-flag-icon {
  line-height: 0;
  max-width: 35px;
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

.c-sostav-item {
  line-height: 1.2;
  border: 1px solid #ccc;
  border-radius: 12px;
  padding: 4px 9px;

  &:hover {
    background-color: #aaa !important;
    color: #fff !important;
  }
}

.c-gift {
  :deep(.q-field__control:before) {
    border: 0;
  }
}

.c-more {
  opacity: 0;
  transform: scale(0.3);
  transition: all 0.6s;
}

.c-na-reason-time {
  border-radius: 6px;
  border: 1px solid #fd7d5a;
  line-height: 1.2;
}

.c-additive {
  border: 1px solid #ddd;
  border-radius: 15px;
  cursor: pointer;

  &.--selected {
    background-color: #009688;
    color: #fff;
  }
}

.c-gift-option {
  :deep(.q-btn__wrapper) {
    img {
      scale: 1.23;
    }

    &:before {
      border: 1px solid #CCCCCC !important;
    }
  }

  &.--selected {
    :deep(.q-btn__wrapper) {
      font-weight: 600;

      &:before {
        border: 2px solid currentColor !important;
      }
    }
  }
}

.c-addit-select {
  &:not(.q-field--focused) {
    :deep() {
      .label-opened {
        display: none;
      }

      .ellipsis {
        overflow: visible;
      }
    }
  }

  &.q-field--focused {
    :deep() {
      .label-opened {}

      .label-closed {
        display: none;
      }
    }
  }
}
</style>
