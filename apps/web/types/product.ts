export interface Product {
  id: string;
  name: string;
  description: string;
  price: string;
  createdAt: string;
  updatedAt: string;
}

export interface ProductListResponse {
  data: Product[];
  meta: {
    page: number;
    limit: number;
    total: number;
    totalPages: number;
  };
}

export interface CreateProductPayload {
  name: string;
  description: string;
  price: number;
}

