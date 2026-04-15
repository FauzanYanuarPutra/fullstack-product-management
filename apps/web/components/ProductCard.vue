<script setup lang="ts">
import type { Product } from '~/types/product';

const props = defineProps<{
  product: Product;
}>();

const { formatPrice } = useDisplay();

const formattedPrice = computed(() => formatPrice(props.product.price));
</script>

<template>
  <NuxtLink :to="`/products/${product.id}`" class="product-card">
    <div class="product-card__media">
      <span class="product-card__eyebrow">Katalog</span>
      <p class="product-card__badge">Produk #{{ product.id }}</p>
    </div>

    <div class="product-card__header">
      <h3>{{ product.name }}</h3>
    </div>

    <p class="product-card__body">{{ product.description }}</p>

    <div class="product-card__footer">
      <div>
        <span class="product-card__price-label">Harga</span>
        <strong>{{ formattedPrice }}</strong>
      </div>
      <span class="product-card__link">Lihat detail</span>
    </div>
  </NuxtLink>
</template>

<style scoped>
.product-card {
  display: grid;
  gap: 18px;
  min-height: 260px;
  padding: 18px;
  border: 1px solid rgba(255, 214, 153, 0.12);
  border-radius: 28px;
  background:
    linear-gradient(180deg, rgba(255, 214, 153, 0.08), transparent 42%),
    linear-gradient(155deg, rgba(25, 20, 23, 0.95), rgba(15, 14, 18, 0.92)),
    var(--panel);
  box-shadow: var(--shadow);
  transition: transform 160ms ease, border-color 160ms ease, box-shadow 160ms ease;
}

.product-card:hover {
  transform: translateY(-4px);
  border-color: rgba(255, 214, 153, 0.3);
  box-shadow: 0 26px 80px rgba(0, 0, 0, 0.36);
}

.product-card__media {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 10px;
  min-height: 82px;
  padding: 14px;
  border-radius: 22px;
  background:
    radial-gradient(circle at top right, rgba(255, 214, 153, 0.18), transparent 34%),
    linear-gradient(160deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.01));
  border: 1px solid rgba(255, 214, 153, 0.1);
}

.product-card__eyebrow {
  color: var(--muted-strong);
  font-size: 0.78rem;
  font-weight: 800;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.product-card__badge {
  margin: 0;
  padding: 7px 10px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.08);
  color: var(--muted-strong);
  font-size: 0.74rem;
  font-weight: 700;
}

.product-card__header h3 {
  margin: 0;
  font-size: 1.34rem;
  letter-spacing: -0.03em;
}

.product-card__body {
  margin: 0;
  color: var(--muted);
  display: -webkit-box;
  overflow: hidden;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 4;
}

.product-card__footer {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 12px;
  margin-top: auto;
}

.product-card__price-label {
  display: block;
  margin-bottom: 6px;
  color: var(--muted);
  font-size: 0.78rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.product-card__footer strong {
  font-size: 1.28rem;
  color: var(--accent-strong);
}

.product-card__link {
  color: var(--muted-strong);
  font-size: 0.92rem;
  font-weight: 700;
}
</style>
