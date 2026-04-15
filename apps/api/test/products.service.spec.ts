import { getRepositoryToken } from '@nestjs/typeorm';
import { Test } from '@nestjs/testing';
import { Repository } from 'typeorm';
import { Product } from '../src/products/entities/product.entity';
import { ProductsService } from '../src/products/products.service';

describe('ProductsService', () => {
  let service: ProductsService;
  let repository: jest.Mocked<Repository<Product>>;

  beforeEach(async () => {
    const moduleRef = await Test.createTestingModule({
      providers: [
        ProductsService,
        {
          provide: getRepositoryToken(Product),
          useValue: {
            findAndCount: jest.fn(),
            findOne: jest.fn(),
            create: jest.fn(),
            save: jest.fn(),
          },
        },
      ],
    }).compile();

    service = moduleRef.get(ProductsService);
    repository = moduleRef.get(getRepositoryToken(Product));
  });

  it('returns paginated products with search metadata', async () => {
    repository.findAndCount.mockResolvedValueOnce([
      [
        {
          id: '1',
          name: 'Phone XL',
          description: 'Flagship device',
          price: '999.99',
        } as Product,
      ],
      1,
    ]);

    const result = await service.findAll({
      page: 1,
      limit: 10,
      search: 'phone',
    });

    expect(repository.findAndCount).toHaveBeenCalledWith(
      expect.objectContaining({
        skip: 0,
        take: 10,
      }),
    );
    expect(result.meta.totalPages).toBe(1);
    expect(result.data).toHaveLength(1);
  });

  it('formats price before saving', async () => {
    const created = {
      name: 'Mouse',
      description: 'Ergonomic mouse',
      price: '149.90',
    } as Product;

    repository.create.mockReturnValueOnce(created);
    repository.save.mockResolvedValueOnce({
      id: '2',
      ...created,
    } as Product);

    const result = await service.create({
      name: 'Mouse',
      description: 'Ergonomic mouse',
      price: 149.9,
    });

    expect(repository.create).toHaveBeenCalledWith({
      name: 'Mouse',
      description: 'Ergonomic mouse',
      price: '149.90',
    });
    expect(result.id).toBe('2');
  });
});
