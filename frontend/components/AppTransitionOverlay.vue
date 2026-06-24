<script setup lang="ts">
const { visible, animKey } = usePageTransition()
</script>

<template>
  <ClientOnly>
    <div v-if="visible" :key="animKey" class="wipe-wrapper">
      <div class="wipe-forest" />
    </div>
  </ClientOnly>
</template>

<style scoped>
.wipe-wrapper {
  position: fixed;
  inset: 0;
  z-index: 9999;
  pointer-events: none;
  overflow: hidden;
  animation: r-expand 1.8s ease-in-out forwards;
}

.wipe-forest {
  position: absolute;
  inset: 0;
  background: url('/forest.png') center / cover no-repeat;
  /* 中心から透明な円が広がり、境目をグラデーションでぼかす */
  -webkit-mask-image: radial-gradient(circle, transparent var(--r), black calc(var(--r) + 35%));
  mask-image:         radial-gradient(circle, transparent var(--r), black calc(var(--r) + 35%));
}

.wipe-forest::after {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(4, 28, 51, 0.35);
}

@keyframes r-expand {
  from { --r: -35%; }
  to   { --r: 100%; }
}
</style>

<style>
@property --r {
  syntax: '<percentage>';
  initial-value: -35%;
  inherits: true;
}
</style>
