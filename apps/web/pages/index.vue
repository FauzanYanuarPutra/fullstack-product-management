<script setup lang="ts">
const { fetchProducts } = useProducts();

const searchInput = ref('');
const appliedSearch = ref('');
const page = ref(1);
const limit = ref(10);
const popularSearches = ['starter', 'kit', 'bundle', 'limited'];

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
    : 'Semua produk',
);

const searchSummary = computed(() =>
  appliedSearch.value
    ? `Menampilkan hasil untuk kata kunci "${appliedSearch.value}".`
    : 'Semua produk ditampilkan.',
);

const resultSummary = computed(() =>
  appliedSearch.value
    ? `${meta.value.total.toLocaleString('id-ID')} hasil ditemukan`
    : `${meta.value.total.toLocaleString('id-ID')} produk di katalog`,
);

const handleCreated = async () => {
  page.value = 1;
  await refresh();
};

const applySearchPreset = (term: string) => {
  searchInput.value = term;
};
</script>

<template>
  <main class="page-shell page-shell--catalog">
    <section class="panel panel--compact catalog-topbar">
      <div class="catalog-topbar__copy">
        <p class="eyebrow eyebrow--soft">Katalog produk</p>
        <h1 class="catalog-title">Cari dan tambah produk tanpa banyak pindah halaman.</h1>
        <p class="section-lead section-lead--compact">
          Search, daftar produk, dan form utama diprioritaskan supaya lebih cepat dipakai.
        </p>
      </div>

      <div class="catalog-topbar__actions">
        <a class="button-secondary" href="#catalog-search">Cari produk</a>
        <a class="button" href="#catalog-form">Tambah produk</a>
      </div>
    </section>

    <section class="layout-grid layout-grid--catalog">
      <div class="results-stack">
        <section id="catalog-search" class="panel panel--search">
          <div class="results-header results-header--compact">
            <div>
              <p class="eyebrow eyebrow--soft">Pencarian</p>
              <h2 class="section-title">{{ activeLabel }}</h2>
              <p class="section-lead section-lead--compact">{{ resultSummary }}</p>
            </div>
            <p class="meta-line">Halaman {{ meta.page }}/{{ meta.totalPages }}</p>
          </div>

          <SearchBar v-model="searchInput" />

          <div class="search-presets">
            <span class="search-presets__label">Coba cepat</span>
            <div class="hero-chips">
              <button
                v-for="term in popularSearches"
                :key="term"
                :class="[
                  'hero-chip',
                  'hero-chip--interactive',
                  { 'hero-chip--active': searchInput === term },
                ]"
                type="button"
                @click="applySearchPreset(term)"
              >
                {{ term }}
              </button>
            </div>
          </div>
        </section>

        <section class="panel">
          <div class="results-header">
            <div>
              <p class="eyebrow eyebrow--soft">Daftar produk</p>
              <h2 class="section-title">{{ activeLabel }}</h2>
              <p class="section-lead section-lead--compact">{{ searchSummary }}</p>
            </div>
            <p class="meta-line">{{ meta.total.toLocaleString('id-ID') }} produk</p>
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
              Coba kata kunci lain atau tambah produk baru dari form di kiri.
            </p>
          </div>
        </section>

        <Pagination
          :page="meta.page"
          :total-pages="meta.totalPages"
          @change="page = $event"
        />
      </div>

      <div id="catalog-form" class="catalog-form-shell">
        <ProductForm @created="handleCreated" />
      </div>
    </section>
  </main>
</template>
