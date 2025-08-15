<template>
    <span>
      <span v-html="prepend"></span><span>{{ displayNumber }}</span><span :class="appendClass" v-html="append"></span>
    </span>
</template>
<script>
import toInt from "@/core/util/toInt";

export default {
  props: {
    number: {default: 0},
    append: {},
    appendClass: {default: null},
    prepend: {}
  },
  data() {
    return {
      displayNumber: 0,
      interval: false,
      dir: null,
      step: 0
    }
  },
  mounted() {
    this.displayNumber = this.number ? this.number : 0;
  },
  watch: {
    number: function () {
      clearInterval(this.interval);
      const number = toInt(this.number)
      const displayNumber = toInt(this.displayNumber)
      if (number !== displayNumber) {
        this.start()
      }
    }
  },
  methods: {
    iteration() {
      this.displayNumber = this.displayNumber + this.step;
      if (this.dir === 'up') {
        if (this.displayNumber >= this.number)
          this.finish()
      } else {
        if (this.displayNumber <= this.number)
          this.finish()
      }
    },
    getStep() {
      let step = (this.number - this.displayNumber) / 10
      step = step >= 0 ? Math.ceil(step) : Math.floor(step)
      return step
    },
    finish() {
      this.displayNumber = this.number
      clearInterval(this.interval);
    },
    start() {
      this.dir = this.displayNumber < this.number ? 'up' : 'down'
      this.step = this.getStep()
      this.interval = setInterval(() => this.iteration(), 20);
    }
  }
}
</script>
