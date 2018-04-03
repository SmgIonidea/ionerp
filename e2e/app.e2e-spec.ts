import { AIonERPPage } from './app.po';

describe('a-ion-erp App', () => {
  let page: AIonERPPage;

  beforeEach(() => {
    page = new AIonERPPage();
  });

  it('should display welcome message', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('Welcome to app!');
  });
});
