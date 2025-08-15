<template>
  <div class="">
    <div class="row">
      <div class="col-auto">
        {{ title }}
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import {toRefs} from 'vue';

const props = withDefaults(defineProps<{
  title?: string
  moreTitle?: string
  moreUrl?: string
  clickNavMore?: boolean
}>(), {})

const {title} = toRefs(props)

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
