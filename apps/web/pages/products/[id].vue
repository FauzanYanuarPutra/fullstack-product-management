<script setup lang="ts">
const route = useRoute();
const { fetchProduct } = useProducts();
const { formatDateTime, formatPrice } = useDisplay();

const { data: product, pending, error } = await useAsyncData(
  `product-${route.params.id}`,
  () => fetchProduct(String(route.params.id)),
);

const formattedPrice = computed(() => {
  if (!product.value) {
    return '';
  }

  return formatPrice(product.value.price);
});
</script>

<template>
  <main class="page-shell">
    <NuxtLink class="back-link" to="/">Kembali ke katalog produk</NuxtLink>

    <section v-if="pending" class="empty-state">
      <h1 class="section-title">Memuat detail produk...</h1>
      <p class="section-lead">Data sedang diambil dari API NestJS.</p>
    </section>

    <section v-else-if="error || !product" class="empty-state">
      <h1 class="section-title">Produk tidak ditemukan</h1>
      <p class="section-lead">
        Produk yang kamu buka tidak tersedia atau gagal diambil dari server.
      </p>
    </section>

    <section v-else class="detail-card">
      <div class="detail-grid">
        <div>
          <p class="eyebrow">Detail produk</p>
          <h1 class="detail-title">{{ product.name }}</h1>
          <p class="hero-copy detail-description">
            {{ product.description }}
          </p>
          <p class="detail-price">{{ formattedPrice }}</p>
        </div>

        <aside class="detail-side">
          <span class="stat-label">ID produk</span>
          <p class="stat-value">#{{ product.id }}</p>

          <span class="stat-label">Dibuat pada</span>
          <p class="stat-value">
            {{ formatDateTime(product.createdAt) }}
          </p>

          <span class="stat-label">Diperbarui</span>
          <p class="stat-value">
            {{ formatDateTime(product.updatedAt) }}
          </p>

          <span class="stat-label">Catatan</span>
          <p class="stat-value stat-value--muted">
            Data ini sinkron dengan katalog frontend dan panel admin Filament.
          </p>
        </aside>
      </div>
    </section>
  </main>
</template>
