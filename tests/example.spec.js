import { test, expect } from '@playwright/test';

let url = 'http://localhost/pocker_planing/';

test('Редирект на авторизацию', async ({ page }) => {
  // Go to the starting page
    await page.goto(url);
    await expect(page).toHaveTitle(/BriPocker/);
});
