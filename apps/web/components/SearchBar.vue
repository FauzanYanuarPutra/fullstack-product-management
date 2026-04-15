<script setup lang="ts">
const model = defineModel<string>({ default: '' });
</script>

<template>
  <label class="search-bar">
    <span class="search-bar__label">Cari produk</span>
    <div class="search-bar__control">
      <input
        v-model="model"
        class="field-input"
        type="search"
        placeholder="Cari nama atau deskripsi produk..."
        autocomplete="off"
      >
      <button
        v-if="model"
        class="button-secondary search-bar__clear"
        type="button"
        @click="model = ''"
      >
        Reset
      </button>
    </div>
  </label>
</template>

<style scoped>
.search-bar {
  display: grid;
  gap: 8px;
}

.search-bar__label {
  font-size: 0.8rem;
  color: var(--muted-strong);
  font-weight: 800;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.search-bar__control {
  display: grid;
  grid-template-columns: minmax(0, 1fr) auto;
  gap: 10px;
}

.search-bar__clear {
  min-width: 92px;
}

@media (max-width: 640px) {
  .search-bar__control {
    grid-template-columns: 1fr;
  }
}
</style>
