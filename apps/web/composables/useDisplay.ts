export const useDisplay = () => {
  const sanitizePriceInput = (value: string | number) =>
    String(value ?? '').replace(/[^\d]/g, '');

  const formatPriceInput = (value: string | number) => {
    const digits = sanitizePriceInput(value);

    if (!digits) {
      return '';
    }

    return Number(digits).toLocaleString('id-ID');
  };

  const parsePriceInput = (value: string | number) => {
    const digits = sanitizePriceInput(value);

    if (!digits) {
      return 0;
    }

    return Number(digits);
  };

  const formatPrice = (value: string | number) => {
    const amount = Number(value);
    const hasDecimals = !Number.isInteger(amount);

    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: hasDecimals ? 2 : 0,
      maximumFractionDigits: hasDecimals ? 2 : 0,
    }).format(amount);
  };

  const formatDateTime = (value: string | Date) =>
    new Intl.DateTimeFormat('id-ID', {
      dateStyle: 'medium',
      timeStyle: 'short',
    }).format(new Date(value));

  return {
    formatPriceInput,
    formatPrice,
    formatDateTime,
    parsePriceInput,
  };
};
