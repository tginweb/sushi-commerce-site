<template>
  <div class="swipe-navigation">

    <div style="position: fixed; top: 100px; z-index: 1000" v-if="false">
      {{ progress }}
    </div>

    <!-- Левый блок -->
    <div
        v-if="showLeft"
        class="nav-block left"
        :style="leftBlockStyle"
    >
      <div class="content">
        <span class="arrow">←</span>
        <span class="label">Назад</span>
      </div>
    </div>

    <!-- Правый блок -->
    <div
        v-if="showRight"
        class="nav-block right"
        :style="rightBlockStyle"
    >
      <div class="content">
        <span class="arrow">→</span>
        <span class="label">Вперед</span>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {computed, defineComponent, onBeforeUnmount, ref, watch} from 'vue';
import {useDebounceFn} from "@vueuse/core";

type TouchHandler = (e: TouchEvent) => void;

export default defineComponent({
  name: 'SwipeNavigation',
  props: {
    targetElement: {
      type: Object as () => HTMLElement | null,
      default: null
    },
    minSwipeThreshold: {
      type: Number,
      default: 20,
      validator: (value: number) => value >= 0 && value <= 100
    }
  },
  setup(props, context) {
    const touchStartX = ref(0);
    const touchCurrentX = ref(0);
    const touchStartY = ref(0);
    const isDragging = ref(false);
    const direction = ref<'left' | 'right' | null>(null);
    const isHorizontalSwipe = ref(false);

    const maxWidth = computed(() => window.innerWidth * 0.5);
    const screenWidth = computed(() => window.innerWidth);

    const calculateProgress = (start: number, current: number) => {
      const distanceToEdge = direction.value === 'right'
          ? screenWidth.value - start
          : start;

      const actualDelta = Math.abs(current - start);
      return Math.min(actualDelta / distanceToEdge, 1);
    };

    const progress = computed(() => {
      if (!isDragging.value) return 0;
      return calculateProgress(touchStartX.value, touchCurrentX.value);
    });

    const effectiveProgress = computed(() => {
      return Math.max(progress.value - (props.minSwipeThreshold / screenWidth.value), 0);
    });

    const showLeft = computed(() => {
      return isDragging.value &&
          direction.value === 'right' &&
          progress.value > (props.minSwipeThreshold / screenWidth.value);
    });

    const showRight = computed(() => {
      return isDragging.value &&
          direction.value === 'left' &&
          progress.value > (props.minSwipeThreshold / screenWidth.value);
    });

    const blockWidth = computed(() => effectiveProgress.value * maxWidth.value);

    const nav = useDebounceFn(() => {
      if (direction.value)
        context.emit('nav', direction.value)
    }, 100)

    watch(progress, () => {
      if (progress.value > 0.75) {
        nav()
      }
    })

    const leftBlockStyle = computed(() => ({
      width: `${blockWidth.value}px`,
      left: 0
      //transform: `translateX(${(touchCurrentX.value - touchStartX.value) - blockWidth.value}px)`
    }));

    const rightBlockStyle = computed(() => ({
      width: `${blockWidth.value}px`,
      right: 0,
      //transform: `translateX(${(touchCurrentX.value - touchStartX.value) + blockWidth.value}px)`
    }));

    const handleTouchStart: TouchHandler = (e) => {
      touchStartX.value = e.touches[0].clientX;
      touchCurrentX.value = e.touches[0].clientX;
      touchStartY.value = e.touches[0].clientY;
      isDragging.value = true;
      isHorizontalSwipe.value = false;
    };

    const handleTouchMove: TouchHandler = (e) => {
      if (!isDragging.value) return;

      const currentX = e.touches[0].clientX;
      const currentY = e.touches[0].clientY;
      const deltaX = currentX - touchStartX.value;
      const deltaY = currentY - touchStartY.value;

      if (!isHorizontalSwipe.value) {
        // Корректное определение направления свайпа
        const isHorizontal = Math.abs(deltaX) > Math.abs(deltaY) * 1.5;
        const isVertical = Math.abs(deltaY) > Math.abs(deltaX) * 1.5;

        if (isHorizontal) {
          isHorizontalSwipe.value = true;
          //e.preventDefault();
        } else if (isVertical) {
          isDragging.value = false;
          return;
        }
      }

      if (isHorizontalSwipe.value) {
       // e.preventDefault();
        touchCurrentX.value = currentX;
        direction.value = deltaX > 0 ? 'right' : 'left';
      }
    };

    const handleTouchEnd: TouchHandler = () => {
      isDragging.value = false;
      isHorizontalSwipe.value = false;
      touchStartX.value = 0;
      touchCurrentX.value = 0;
      direction.value = null;
    };

    const setupEventListeners = (element: HTMLElement | Window) => {
      const options = {passive: false};

      element.addEventListener('touchstart', handleTouchStart, options);
      element.addEventListener('touchmove', handleTouchMove, options);
      element.addEventListener('touchend', handleTouchEnd, options);
      element.addEventListener('touchcancel', handleTouchEnd, options);

      return () => {
        element.removeEventListener('touchstart', handleTouchStart);
        element.removeEventListener('touchmove', handleTouchMove);
        element.removeEventListener('touchend', handleTouchEnd);
        element.removeEventListener('touchcancel', handleTouchEnd);
      };
    };

    let cleanup = () => {
    };
    watch(() => props.targetElement, (newEl) => {
      cleanup();
      const target = newEl || window;
      cleanup = setupEventListeners(target);
    }, {immediate: true});

    onBeforeUnmount(() => cleanup());

    return {
      progress,
      showLeft,
      showRight,
      leftBlockStyle,
      rightBlockStyle
    };
  }
});
</script>

<style scoped>
.swipe-navigation {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
  z-index: 1000;
}

.nav-block {
  position: absolute;
  top: 50%;
  height: 80px;
  background: rgba(255, 255, 255, 0.9);
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.1s cubic-bezier(0.33, 1, 0.68, 1),
  width 0.1s cubic-bezier(0.33, 1, 0.68, 1);
  pointer-events: none;
  overflow: hidden;
}

.content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  min-width: 80px;
  padding: 0 20px;
}

.arrow {
  font-size: 24px;
  font-weight: bold;
}

.label {
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.left {
  left: 0;
  transform-origin: left center;
}

.right {
  right: 0;
  transform-origin: right center;
}
</style>
