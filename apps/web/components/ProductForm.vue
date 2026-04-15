<script setup lang="ts">
const emit = defineEmits<{
  created: [];
}>();

const { createProduct } = useProducts();

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

const submit = async () => {
  loading.value = true;
  error.value = '';
  success.value = '';

  try {
    await createProduct({
      name: form.name.trim(),
      description: form.description.trim(),
      price: Number(form.price),
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
    <p class="eyebrow">Input cepat</p>
    <h2 class="panel-title">Tambah produk baru</h2>
    <p class="section-lead">
      Form ini langsung mengirim data ke API NestJS, jadi katalog di sebelah kanan
      akan ikut terbarui tanpa pindah halaman.
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
          placeholder="Contoh: Paket Frozen Food Hemat"
        >
        <span class="field-hint">Nama singkat yang jelas akan lebih mudah dicari tim.</span>
      </label>

      <label class="field">
        <span class="field-label">Deskripsi</span>
        <textarea
          v-model="form.description"
          class="field-textarea"
          required
          placeholder="Tuliskan isi produk, ukuran, manfaat, atau catatan penting untuk pembeli."
        ></textarea>
        <span class="field-hint">Deskripsi ini akan tampil di daftar dan halaman detail.</span>
      </label>

      <label class="field">
        <span class="field-label">Harga</span>
        <input
          v-model="form.price"
          class="field-input"
          min="0"
          required
          step="0.01"
          type="number"
          placeholder="25000"
        >
        <span class="field-hint">Masukkan angka saja. Tampilan pengguna akan otomatis diformat ke Rupiah.</span>
      </label>

      <button class="button" :disabled="loading" type="submit">
        {{ loading ? 'Menyimpan produk...' : 'Simpan produk' }}
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
