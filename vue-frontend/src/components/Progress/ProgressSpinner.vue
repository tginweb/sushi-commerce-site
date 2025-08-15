<template>
  <q-spinner
    color="primary"
    :size="size"
    :thickness="thickness"
  />
</template>
<script>

  export default {
    props: {
      size: {
        default: '3em'
      },
      thickness: {
        default: 10
      },
    },
  }

</script>

<style lang="scss" scoped>


</style>
