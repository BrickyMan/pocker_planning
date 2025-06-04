import { test, expect } from '@playwright/test';

let url = 'http://localhost/pocker_planing/';

test('Редирект на лобби', async ({ page }) => {
    await page.goto(url);
    await expect(page).toHaveTitle(/BriPocker - Лобби/);
});

