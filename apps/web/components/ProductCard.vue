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
    <div class="product-card__header">
      <p class="product-card__badge">Produk #{{ product.id }}</p>
      <h3>{{ product.name }}</h3>
    </div>
    <p class="product-card__body">{{ product.description }}</p>
    <div class="product-card__footer">
      <strong>{{ formattedPrice }}</strong>
      <span>Lihat detail</span>
    </div>
  </NuxtLink>
</template>

<style scoped>
.product-card {
  display: grid;
  gap: 18px;
  min-height: 220px;
  padding: 22px;
  border: 1px solid var(--line);
  border-radius: 26px;
  background:
    linear-gradient(155deg, rgba(255, 255, 255, 0.9), rgba(241, 252, 249, 0.76)),
    var(--panel);
  box-shadow: var(--shadow);
  transition: transform 160ms ease, border-color 160ms ease;
}

.product-card:hover {
  transform: translateY(-4px);
  border-color: var(--accent);
  box-shadow: 0 24px 70px rgba(18, 71, 61, 0.14);
}

.product-card__header h3 {
  margin: 10px 0 0;
  font-size: 1.28rem;
  letter-spacing: -0.03em;
}

.product-card__badge {
  margin: 0;
  color: var(--muted);
  font-size: 0.8rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
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
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-top: auto;
}

.product-card__footer strong {
  font-size: 1.2rem;
}

.product-card__footer span {
  color: var(--muted);
  font-size: 0.92rem;
  font-weight: 700;
}
</style>
