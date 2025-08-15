<template>
  <div class="scroll-fade-container">
    <div
        ref="scrollContainer"
        class="scroll-container"
        @scroll="updateFadeState"
    >
      <div class="content-wrapper">
        <slot/>
      </div>
    </div>
    <div
        v-show="showLeftFade"
        class="fade-layer left-fade"
        :style="{
          width: faderWidth
        }"
        v-if="faderLeft"
    />
    <div
        v-show="showRightFade"
        class="fade-layer right-fade"
        :style="{
          width: faderWidth
        }"
        v-if="faderRight"
    />
  </div>
</template>

<script lang="ts" setup>
import {onBeforeUnmount, onMounted, ref} from 'vue';

const props = withDefaults(defineProps<{
  faderLeft?: boolean
  faderRight?: boolean
  faderWidth?: string
}>(), {
  faderWidth: '15px',
  faderLeft: true,
  faderRight: true,
})

const {faderWidth} = props

const scrollContainer = ref<HTMLElement | null>(null);
const showLeftFade = ref(false);
const showRightFade = ref(false);
let resizeObserver: ResizeObserver | null = null;

const updateFadeState = () => {
  if (!scrollContainer.value) return;

  const {scrollLeft, clientWidth, scrollWidth} = scrollContainer.value;
  const isAtStart = scrollLeft <= 0;
  const isAtEnd = scrollLeft + clientWidth >= scrollWidth - 10;

  showLeftFade.value = !isAtStart;
  showRightFade.value = !isAtEnd;
};

onMounted(() => {
  if (scrollContainer.value) {
    resizeObserver = new ResizeObserver(updateFadeState);
    resizeObserver.observe(scrollContainer.value);
    updateFadeState();
  }
});

onBeforeUnmount(() => {
  if (resizeObserver && scrollContainer.value) {
    resizeObserver.unobserve(scrollContainer.value);
  }
});

</script>

<style scoped>
.scroll-fade-container {
  position: relative;
  width: 100%;
}

.scroll-container {
  overflow-x: auto;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  white-space: nowrap;
}

.scroll-container::-webkit-scrollbar {
  display: none;
}

.content-wrapper {
  display: inline-block;
  min-width: 100%;
}

.fade-layer {
  position: absolute;
  top: 0;
  bottom: 0;
  width: 40px;
  pointer-events: none;
}

.left-fade {
  left: 0;
  background: linear-gradient(
      to right,
      rgba(255, 255, 255, 1) 20%,
      rgba(255, 255, 255, 0) 100%
  );
}

.right-fade {
  right: 0;
  background: linear-gradient(
      to left,
      rgba(255, 255, 255, 1) 20%,
      rgba(255, 255, 255, 0) 100%
  );
}

@media (hover: hover) {
  .scroll-container {
    overflow-x: hidden;
  }

  .scroll-container:hover {
    overflow-x: auto;
  }
}
</style>
