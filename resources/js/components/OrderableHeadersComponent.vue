<template>
  <div class="headers">
    <div
      v-for="(header, index) in config.headers"
      :key="'header-' + index"
      @click="$emit('update-order-by', index + 1)"
    >
      {{ $root.trans(header) }}
      <svg-vue
        icon="arrow-down"
        class="decorator"
        :class="{ 'reverse': config.orderBy < 0 }"
        v-show="index === Math.abs(config.orderBy) - 1"
      />
    </div>
    <div v-if="extraHeader" :class="extraHeaderClass">{{ $root.trans(extraHeaderName) }}</div>
  </div>
</template>

<script>
export default {
  props: {
    config: { type: Object, default: null },
    extraHeader: { type: Boolean, default: false },
    extraHeaderName: { type: String, default: "" },
    extraHeaderClass: { type: String, default: "" }
  }
};
</script>