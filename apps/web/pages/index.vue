<script setup lang="ts">
const { fetchProducts } = useProducts();

const searchInput = ref('');
const appliedSearch = ref('');
const page = ref(1);
const limit = ref(10);

let debounceHandle: ReturnType<typeof setTimeout> | undefined;

watch(searchInput, (value) => {
  if (debounceHandle) {
    clearTimeout(debounceHandle);
  }

  debounceHandle = setTimeout(() => {
    appliedSearch.value = value.trim();
    page.value = 1;
  }, 300);
});

onBeforeUnmount(() => {
  if (debounceHandle) {
    clearTimeout(debounceHandle);
  }
});

const { data, pending, error, refresh } = await useAsyncData(
  'products',
  () =>
    fetchProducts({
      page: page.value,
      limit: limit.value,
      search: appliedSearch.value || undefined,
    }),
  {
    watch: [page, limit, appliedSearch],
  },
);

const products = computed(() => data.value?.data ?? []);
const meta = computed(() => ({
  page: data.value?.meta.page ?? 1,
  total: data.value?.meta.total ?? 0,
  totalPages: data.value?.meta.totalPages ?? 1,
}));

const activeLabel = computed(() =>
  appliedSearch.value
    ? `Hasil pencarian untuk "${appliedSearch.value}"`
    : 'Semua produk terbaru',
);

const statCards = computed(() => [
  {
    label: 'Total produk',
    value: meta.value.total.toLocaleString('id-ID'),
    note: appliedSearch.value ? 'produk cocok dengan pencarian aktif' : 'produk tersimpan di katalog',
  },
  {
    label: 'Halaman aktif',
    value: `${meta.value.page}/${meta.value.totalPages}`,
    note: 'navigasi daftar produk',
  },
  {
    label: 'Mode tampilan',
    value: appliedSearch.value ? 'Tersaring' : 'Penuh',
    note: appliedSearch.value ? 'daftar sedang dipersempit keyword' : 'seluruh katalog sedang ditampilkan',
  },
]);

const searchSummary = computed(() =>
  appliedSearch.value
    ? `Keyword aktif: "${appliedSearch.value}"`
    : 'Belum ada filter pencarian. Semua produk akan ditampilkan.',
);

const handleCreated = async () => {
  page.value = 1;
  await refresh();
};
</script>

<template>
  <main class="page-shell">
    <section class="hero hero--market">
      <div class="hero-copy-stack">
        <p class="eyebrow">Katalog digital</p>
        <h1>Kelola katalog produk dengan rasa dashboard yang cocok untuk tim Indonesia.</h1>
        <p class="hero-copy">
          Halaman ini menampilkan daftar produk dari API NestJS, mendukung pencarian
          server-side, pagination, detail produk, dan input produk baru dalam satu alur
          yang cepat dipakai tim operasional maupun owner.
        </p>
        <div class="hero-chips">
          <span class="hero-chip">Pencarian cepat</span>
          <span class="hero-chip">Form input langsung</span>
          <span class="hero-chip">Tampilan mobile-friendly</span>
        </div>
      </div>

      <div class="hero-spotlight">
        <p class="hero-spotlight__eyebrow">Ringkasan hari ini</p>
        <h2>{{ activeLabel }}</h2>
        <p>{{ searchSummary }}</p>
        <div class="hero-spotlight__footer">
          <span>API: NestJS</span>
          <span>Admin: Filament</span>
          <span>DB: PostgreSQL</span>
        </div>
      </div>
    </section>

    <section class="stats-grid">
      <article v-for="stat in statCards" :key="stat.label" class="stat-card">
        <p class="stat-card__label">{{ stat.label }}</p>
        <strong class="stat-card__value">{{ stat.value }}</strong>
        <span class="stat-card__note">{{ stat.note }}</span>
      </article>
    </section>

    <section class="layout-grid">
      <ProductForm @created="handleCreated" />

      <div class="results-stack">
        <section class="panel">
          <SearchBar v-model="searchInput" />
        </section>

        <section class="panel">
          <div class="results-header">
            <div>
              <p class="eyebrow">Katalog</p>
              <h2 class="section-title">{{ activeLabel }}</h2>
              <p class="section-lead section-lead--compact">{{ searchSummary }}</p>
            </div>
            <p class="meta-line">{{ meta.total.toLocaleString('id-ID') }} produk ditemukan</p>
          </div>

          <p v-if="pending" class="meta-line">Memuat produk dari server...</p>
          <p v-else-if="error" class="status status--error">
            Produk gagal dimuat dari API.
          </p>
          <div v-else-if="products.length" class="cards-grid">
            <ProductCard
              v-for="product in products"
              :key="product.id"
              :product="product"
            />
          </div>
          <div v-else class="empty-state">
            <h3 class="section-title">Belum ada produk yang cocok.</h3>
            <p class="section-lead">
              Coba kata kunci lain atau tambahkan produk baru lewat form di sisi kiri.
            </p>
          </div>
        </section>

        <Pagination
          :page="meta.page"
          :total-pages="meta.totalPages"
          @change="page = $event"
        />
      </div>
    </section>
  </main>
</template>
