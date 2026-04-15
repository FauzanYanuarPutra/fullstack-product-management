<script setup lang="ts">
const props = defineProps<{
  page: number;
  totalPages: number;
}>();

const emit = defineEmits<{
  change: [page: number];
}>();

const pages = computed(() => {
  const total = Math.max(props.totalPages, 1);

  if (total <= 5) {
    return Array.from({ length: total }, (_, index) => index + 1);
  }

  const start = Math.max(1, props.page - 2);
  const end = Math.min(total, start + 4);
  const normalizedStart = Math.max(1, end - 4);

  return Array.from(
    { length: end - normalizedStart + 1 },
    (_, index) => normalizedStart + index,
  );
});

const goToPage = (page: number) => {
  if (page < 1 || page > props.totalPages || page === props.page) {
    return;
  }

  emit('change', page);
};
</script>

<template>
  <nav class="pagination" aria-label="Paginasi produk">
    <button
      class="button-secondary"
      :disabled="page <= 1"
      type="button"
      @click="goToPage(page - 1)"
    >
      Sebelumnya
    </button>

    <div class="pagination__pages">
      <button
        v-for="item in pages"
        :key="item"
        :class="['page-chip', { 'page-chip--active': item === page }]"
        type="button"
        @click="goToPage(item)"
      >
        {{ item }}
      </button>
    </div>

    <button
      class="button-secondary"
      :disabled="page >= totalPages"
      type="button"
      @click="goToPage(page + 1)"
    >
      Berikutnya
    </button>
  </nav>
</template>

<style scoped>
.pagination {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
  padding: 18px 20px;
  border: 1px solid var(--line);
  border-radius: 24px;
  background: rgba(16, 14, 18, 0.84);
  box-shadow: var(--shadow);
}

.pagination__pages {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.page-chip--active {
  color: #181215;
  border-color: transparent;
  background: linear-gradient(135deg, var(--accent), var(--accent-strong));
}
</style>
