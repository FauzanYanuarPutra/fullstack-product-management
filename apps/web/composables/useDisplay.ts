export const useDisplay = () => {
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
    formatPrice,
    formatDateTime,
  };
};

