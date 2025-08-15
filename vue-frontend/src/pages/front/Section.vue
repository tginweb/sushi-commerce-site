<template>
  <div class="product-section ">

    <div
        class="c-header-desktop bg-white bg-white swipe-disable gt-sm"
        :class="{
          '--stickable': stickable
        }"
        :style="{
          top: headerHeightCss
        }"
        v-sticky-state="{
          activeClass: 'stacked',
          top: headerHeightCss,
          disable: !stickable
        }"
    >
      <div class="row items-center q-col-gutter-md no-wrap">
        <div class="col-shrink">
          <div class="q-px-sm q-px-lg-none">
            <h2 class="q-ma-none text-weight-boldest s-font-lg s-font-md-4xl s-font-lg-2xl s-font-xl-2xl">
              {{ section.NAME }}
            </h2>
          </div>
        </div>
        <div class="col-shrink">
          <TagsTabs
              v-model="selectedTagPrimary"
              :options="tagsPrimary"
              v-bind="defaultEntityOptionsProps"
              option-featured="FEATURED"
              class="c-tags-primary"
          />
        </div>
      </div>
    </div>
    <div class="lt-lg">

      <div
          class="c-header-mobile bg-white q-px-md swipe-disable"
          :class="{
            '--with-tags': tagsPrimary.length > 0
          }"
      >
        <div class="row items-center q-col-gutter-xs">
          <div class="col-lg-grow">
            <h2 class="q-ma-none text-weight-boldest s-font-lg s-font-md-4xl s-font-lg-2xl s-font-xl-2xl">
              {{ section.NAME }}
            </h2>
          </div>
          <div class="col-lg-shrink q-ml-auto">
            <q-btn
                :icon="ICONS.filters"
                dense
                flat
                size="md"
                label="вид"
                color="primary"
                @click="onOpenFilters"
            />
          </div>
        </div>
      </div>

      <div
          class="c-tags-primary-mobile bg-white q-pb-sm q-pl-sm swipe-disable"
          :style="{
            top: (headerHeight - 1) + 'px'
          }"
          v-sticky-state="{
            activeClass: 'sticky',
            top: headerHeightCss,
          }"
          v-if="tagsPrimary.length > 0"
      >
        <TagsTabs
            v-model="selectedTagPrimary"
            :options="tagsPrimary"
            v-bind="defaultEntityOptionsProps"
            class="c-tags-primary"
        />
      </div>

      <div class="c-tags-secondary-mobile q-pb-sm q-pl-sm swipe-disable">
        <TagsChips
            :items="tagsPrimary"
            v-model="selectedTagsSecondary"
            title="Состав"
            class="c-tags-secondary q-pt-sm"
        />
      </div>

    </div>

    <div class="c-products q-mt-md q-mx-md q-mx-md-none">
      <TransitionGroup name="list" tag="div" class="c-products__list row q-col-gutter-md">
        <component
            v-for="product in products"
            :key="product.ID"
            :product="product"
            :class="viewVariant.cellClass"
            :is="viewVariant.cellComponent"
        />
      </TransitionGroup>
    </div>

  </div>

</template>

<script setup lang="ts">
import {useCatalogStore} from "@/modules/shop/store/catalog";
import ProductCardGrid from "@/components/Product/ProductCardGrid.vue";
import ProductCardGridBig from "@/components/Product/ProductCardGridBig.vue";
import {computed, ref, watch} from "vue";
import TagsTabs from '@/components/TagsTabs.vue'
import TagsChips from '@/components/TagsChips.vue'
import {useQuasar} from "quasar";
import {useLayoutStore} from "@/stores/layout";
import {storeToRefs} from "pinia";
import {defaultEntityOptionsProps} from "@/core/entity/constants";
import {ICONS} from "@/assets/icons";
import {useStackManager} from "@/packages/stack-router/hooks/useStackManager";
import SectionFiltersDialog from "../catalog/SectionFilters.dialog.vue";
import {SectionFiltersDialogProps} from "@/pages/catalog/SectionFilters.dialog.vue";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";
import {ProductSection} from "@/gql/gen";

const stackManager = useStackManager()


const q = useQuasar()

type ProductSectionProps = {
  section: ProductSection
}

const props = withDefaults(defineProps<ProductSectionProps>(), {})

const {section} = props

const layoutStore = useLayoutStore()

const {headerHeightCss, headerHeight} = storeToRefs(layoutStore)

const catalogStore = useCatalogStore()

const {productsModelsBySection} = storeToRefs(catalogStore)

const products = computed<ProductElementModel[]>(() => {
  const items = productsModelsBySection.value[section.ID] || []

  return items.filter(item => {
    if (selectedTagPrimary.value) {
      if (!item.IBLOCK_SECTION_IDS.includes(selectedTagPrimary.value)) {
        return false
      }
    }
    return true
  })
})

const emit = defineEmits<{
  filtered: [ProductSection]
}>()


const tagsPrimary = computed(() => {
  return section.CHILDREN.map((item, index) => ({
    ID: item.ID,
    NAME: item.NAME || '',
    FEATURED: index === 0
  }))
})

const selectedTagPrimary = ref(null)

const selectedTagsSecondary = ref([])

watch(selectedTagPrimary, () => {
  console.log('ww')
  emit('filtered', section)
})


type ViewVariant = {
  default?: boolean
  sectionCodes?: string[]
  sectionIds?: number[]
  cellClass: string
  cellComponent: any
}

const viewVariants: ViewVariant[] = [
  {
    default: true,
    cellClass: 'col-12 col-lg-8 col-xl-6',
    cellComponent: ProductCardGrid
  },
  {
    sectionCodes: ['sets'],
    cellClass: 'col-12 col-lg-12',
    cellComponent: ProductCardGridBig
  }
]

const viewVariant = computed(() => {
  let result = {} as ViewVariant
  viewVariants.forEach((variant) => {
    if (variant.sectionCodes && section.CODE && !variant.sectionCodes.includes(section.CODE)) {
      return
    }
    if (variant.sectionIds && !variant.sectionIds.includes(section.ID)) {
      return
    }
    result = {
      ...result,
      ...variant
    }
    return result
  })
  return result
})

const stickable = computed(() => !!tagsPrimary.value.length || true)

const onOpenFilters = () => {
  stackManager.getStack()?.push<SectionFiltersDialogProps>(SectionFiltersDialog, {
    sectionId: section.ID,
    selectedPrimaryId: selectedTagPrimary.value,
    selectedSecondaryIds: selectedTagsSecondary.value
  })
}

watch(viewVariant, () => {
  console.log('products', products)
}, {deep: true})

</script>

<style lang="scss" scoped>

.c-header-desktop {
  border-radius: 10px;
  border: 1px solid #eee;
  z-index: 10;
  transition: all 0.3s ease-in-out;
  padding: 12px 16px;

  .c-tags-primary {
    margin-top: 4px;
  }

  &.--stickable {
    position: sticky;
  }

  &.stacked {
    box-shadow: 0px 5px 15px -5px rgba(34, 60, 80, 0.2);
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
    padding: 7px 16px;
  }
}

.c-header-mobile {
  border-top: 1px solid #DDDDDD;
  padding-top: 8px;

  &:not(.--with-tags) {
    padding-bottom: 8px;
    border-bottom: 1px solid #DDDDDD;
  }
}

.c-tags-primary-mobile {
  position: sticky;
  z-index: 1;
  transition: all 0.5s ease-in-out;
  border-bottom: 1px solid #DDDDDD;

  .c-tags-primary {
    transition: all 0.5s ease-in-out;
  }

  :deep(.q-tab__label) {
    font-size: 14px;
  }

  &.sticky {
    box-shadow: 0px 4px 8px -3px rgba(34, 60, 80, 0.2);
    padding-top: 0px;
    padding-bottom: 0px;

    .c-tags-primary {
      padding-top: 0px;
    }
  }
}

.c-tags-secondary-mobile {
  z-index: 1;
}

.c-products__list {
  position: relative;
}


</style>
