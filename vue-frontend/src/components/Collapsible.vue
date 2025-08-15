<template>
    <div class="collapsible-wrapper">
        <Transition
            @enter="onEnter"
            @after-enter="onAfterEnter"
            @leave="onLeave"
            @after-leave="onAfterLeave">
            <div
                v-show="modelValue"
                ref="contentRef"
                class="collapsible-content"
                :class="contentClass"
                :style="contentStyle">
                <slot />
            </div>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface Props {
    modelValue: boolean
    duration?: number
    easing?: string
    contentClass?: string | string[]
    contentStyle?: Record<string, string>
}

const props = withDefaults(defineProps<Props>(), {
    duration: 300,
    easing: 'ease-in-out',
})

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void
    (e: 'enter'): void
    (e: 'leave'): void
    (e: 'after-enter'): void
    (e: 'after-leave'): void
}>()

const contentRef = ref<HTMLElement | null>(null)

function onEnter(el: Element) {
    const element = el as HTMLElement
    element.style.height = '0px'
    element.style.overflow = 'hidden'
    const fullHeight = element.scrollHeight
    requestAnimationFrame(() => {
        element.style.transition = `height ${props.duration}ms ${props.easing}`
        element.style.height = `${fullHeight}px`
    })
    emit('enter')
}

function onAfterEnter(el: Element) {
    const element = el as HTMLElement
    element.style.height = ''
    element.style.overflow = ''
    element.style.transition = ''
    emit('after-enter')
}

function onLeave(el: Element) {
    const element = el as HTMLElement
    element.style.height = `${element.offsetHeight}px`
    element.style.overflow = 'hidden'
    requestAnimationFrame(() => {
        element.style.transition = `height ${props.duration}ms ${props.easing}`
        element.style.height = '0px'
    })
    emit('leave')
}

function onAfterLeave(el: Element) {
    const element = el as HTMLElement
    element.style.height = ''
    element.style.overflow = ''
    element.style.transition = ''
    emit('after-leave')
}

// Методы для программного управления
function toggle() {
    emit('update:modelValue', !props.modelValue)
}
function open() {
    emit('update:modelValue', true)
}
function close() {
    emit('update:modelValue', false)
}

defineExpose({ toggle, open, close })
</script>

<style lang="scss" scoped>
.collapsible-wrapper {
    width: 100%;
}

.collapsible-content {
    width: 100%;
    will-change: height;
}
</style>