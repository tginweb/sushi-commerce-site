<template>
  <div class="query-item">

    <div class="row">
      <div class="c-info col-auto q-mr-2md q-mr-auto">
        <div class="c-author text-weight-bold"><q-icon :name="ICONS.person" /> {{ entity.PROPERTIES.AUTHOR_NAME }}</div>
      </div>
    </div>

    <div class="c-text q-mt-sm" v-html="entity.DETAIL_TEXT"></div>

    <div class="q-mt-sm flex items-center" v-if="entity.PROPERTIES.RATING">
      <q-rating class="c-reviews__rating__stars q-mr-md" color-selected="actions-yellow" icon="star"
        icon-selected="star" max="5" size="24px" readonly :model-value="entity.PROPERTIES.RATING" />
      <div>{{ entity.PROPERTIES.RATING }}</div>
    </div>

    <div class="c-date s-font-sm text-primary-brown-gray-4 q-mt-sm">
      {{ timestampToFormat(entity.ACTIVE_FROM, 'date') }}
    </div>

    <div v-if="entity.PROPERTIES.ANSWER" class="c-answer q-mt-md q-pl-lg q-py-xs">
      <div class="c-answer-author text-weight-bold q-mb-sm">
        менеджер Sushi Studio
      </div>
      <div>
        {{ entity.PROPERTIES.ANSWER }}
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">

import { ICONS } from '@/assets/icons';
import { ReviewElement } from '@/gql/gen';
import timestampToFormat from '@/core/util/date/timestampToFormat';
import { toRefs } from 'vue';

const props = withDefaults(defineProps<{
  entity: ReviewElement
}>(), {})

const { entity } = toRefs(props)

</script>

<style lang="scss" scoped>
.c-product {
  max-width: 40px;
}

@media (max-width: $breakpoint-sm-max) {
  .c-product {
    max-width: 20px;
  }
}

.c-answer {
  border-left: 4px solid #87bd28;
}
</style>
