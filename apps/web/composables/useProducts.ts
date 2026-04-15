import type {
  CreateProductPayload,
  Product,
  ProductListResponse,
} from '~/types/product';

export const useProducts = () => {
  const config = useRuntimeConfig();

  const fetchProducts = (params: {
    page?: number;
    limit?: number;
    search?: string;
  }) => {
    return $fetch<ProductListResponse>(`${config.public.apiBase}/api/products`, {
      query: params,
    });
  };

  const fetchProduct = (id: string | number) => {
    return $fetch<Product>(`${config.public.apiBase}/api/products/${id}`);
  };

  const createProduct = (payload: CreateProductPayload) => {
    return $fetch<Product>(`${config.public.apiBase}/api/products`, {
      method: 'POST',
      body: payload,
    });
  };

  return {
    fetchProducts,
    fetchProduct,
    createProduct,
  };
};

