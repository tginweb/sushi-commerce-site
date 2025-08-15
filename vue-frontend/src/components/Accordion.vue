<template>
    <q-list v-if="items.length" class="qx-focus-disable">
        <q-expansion-item v-for="(item, index) of items" :key="index" :class="itemClass" :default-opened="index === 0"
            :header-class="headerClass" :label="item.NAME" group="main" @update:model-value="onStateChange($event, item)"
            :duration="duration"
            :ref="el => setItemRef(el, index)"
        >
            <template #default>
                <div :class="contentClass">
                    <RuntimeContent v-if="item.HTML" :content="item.HTML" />
                    <div v-else v-html="item.VALUE"></div>
                </div>
            </template>
        </q-expansion-item>
    </q-list>
</template>

<script setup lang="ts">
import { useQuasar } from "quasar";
import RuntimeContent from "./RuntimeContent.vue";
import { withDefaults, defineProps, toRefs, ref, type ComponentPublicInstance } from "vue";
import { scrollTo } from "@/core/util/scrollTo";

const $q = useQuasar()

const props = withDefaults(defineProps<{
    itemClass?: any,
    items: any[],
    duration?: number,
    headerClass?: any,
    contentClass?: any
}>(), {
    items: () => [],
    headerClass: 'text-weight-bold q-px-none',
    duration: 100,
})

const { items, itemClass, headerClass, duration, contentClass } = toRefs(props)

// Массив ref-ов для q-expansion-item
const itemRefs = ref<(HTMLElement | null)[]>([])



function setItemRef(el: Element | ComponentPublicInstance | null, index: number) {
    let domEl: HTMLElement | null = null
    if (el) {
        if (el instanceof HTMLElement) {
            domEl = el
        } else if ('$el' in el && el.$el instanceof HTMLElement) {
            domEl = el.$el
        }
    }
    itemRefs.value[index] = domEl
}

function onStateChange(val: boolean, item: any) {
    if (val) {
        if ($q.screen.gt.lg) return
        const index = items.value.indexOf(item)
        const el = itemRefs.value[index]

        if (el) {
            setTimeout(() => {
                scrollTo(el, 80, { behavior: 'smooth' })
            }, 100)
        }
    }
}
</script>

<style lang="scss" scoped>
.items {
    :deep() {
        .i-wrap {
            padding-left: 30px !important;
            padding-right: 30px !important;
        }
    }
}

:deep() {
    .q-item {
        min-height: auto;
    }
}

.q-expansion-item.q-expansion-item--expanded {
    border-bottom: 1px solid map-get($theme-colors, "primary-brown-gray-1");
}
</style>
