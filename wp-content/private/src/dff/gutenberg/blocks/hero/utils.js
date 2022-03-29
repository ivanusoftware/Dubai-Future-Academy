import * as variations from './variations';

export const getHeroComponent = variation => {
  switch (variation) {
    case 'home':
      return variations.Home;
    case 'quote':
      return variations.Quote;
    case 'quote-content':
      return variations.QuoteContent;
    case 'sbu':
      return variations.Sbu;
    case 'sbu-archive':
      return variations.SbuArchive;
    default:
      return variations.Home;
  }
};

export default { getHeroComponent };
