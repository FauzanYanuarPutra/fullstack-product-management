<script setup lang="ts">
const emit = defineEmits<{
  created: [];
}>();

const { createProduct } = useProducts();
const { formatPriceInput, parsePriceInput } = useDisplay();

const form = reactive({
  name: '',
  description: '',
  price: '',
});

const loading = ref(false);
const error = ref('');
const success = ref('');

const resetForm = () => {
  form.name = '';
  form.description = '';
  form.price = '';
};

const handlePriceInput = (event: Event) => {
  const target = event.target as HTMLInputElement;

  form.price = formatPriceInput(target.value);
};

const submit = async () => {
  loading.value = true;
  error.value = '';
  success.value = '';

  try {
    await createProduct({
      name: form.name.trim(),
      description: form.description.trim(),
      price: parsePriceInput(form.price),
    });

    resetForm();
    success.value = 'Produk berhasil ditambahkan.';
    emit('created');
  } catch (requestError: any) {
    error.value =
      requestError?.data?.message?.join?.(', ') ||
      requestError?.data?.message ||
      'Produk gagal disimpan.';
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <section class="panel panel--sticky">
    <p class="eyebrow eyebrow--soft">Tambah produk</p>
    <h2 class="panel-title">Tambah produk</h2>
    <p class="section-lead">
      Isi data produk lalu simpan. Hasilnya akan langsung masuk ke katalog.
    </p>

    <form class="form-stack" @submit.prevent="submit">
      <label class="field">
        <span class="field-label">Nama produk</span>
        <input
          v-model="form.name"
          class="field-input"
          maxlength="255"
          required
          type="text"
          placeholder="Contoh: Starter Kit Midnight"
        >
        <span class="field-hint">Gunakan nama yang mudah dicari.</span>
      </label>

      <label class="field">
        <span class="field-label">Deskripsi</span>
        <textarea
          v-model="form.description"
          class="field-textarea"
          required
          placeholder="Tulis deskripsi singkat produk."
        ></textarea>
        <span class="field-hint">Deskripsi ini tampil di katalog dan halaman detail.</span>
      </label>

      <label class="field">
        <span class="field-label">Harga</span>
        <input
          :value="form.price"
          class="field-input"
          autocomplete="off"
          inputmode="numeric"
          pattern="[0-9.]*"
          required
          type="text"
          placeholder="350.000"
          @input="handlePriceInput"
        >
        <span class="field-hint">Angka akan otomatis dipisah per ribuan supaya lebih mudah dibaca.</span>
      </label>

      <button class="button" :disabled="loading" type="submit">
        {{ loading ? 'Menyimpan...' : 'Simpan produk' }}
      </button>

      <p v-if="success" class="status status--success">{{ success }}</p>
      <p v-if="error" class="status status--error">{{ error }}</p>
    </form>
  </section>
</template>

<style scoped>
.form-stack {
  display: grid;
  gap: 16px;
  margin-top: 22px;
}
</style>
