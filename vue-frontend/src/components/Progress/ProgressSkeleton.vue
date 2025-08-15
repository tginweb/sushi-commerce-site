<template>
  <div class="skeleton">
    <q-skeleton :type="type" v-for="index in count" :key="index"/>
  </div>
</template>

<script>
export default {
  props: {
    type: {
      default: 'text'
    },
    count: {
      default: 4
    }
  },
}
</script>
<style lang="scss" scoped>
</style>
