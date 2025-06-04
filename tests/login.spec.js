import { test, expect } from '@playwright/test';

const BASE_URL = 'http://localhost/pocker_planing/';

test('Редирект на авторизацию', async ({ page }) => {
    await page.goto(BASE_URL);
    // Проверка заголовка страницы
    await expect(page).toHaveTitle(/BriPocker - Придумайте имя/);
    // И маршрута
    await expect(page).toHaveURL(/\/login/);
});

test('Ввод псевдонима', async ({ page }) => {
    await page.goto('/');
    const usernameInput = page.locator('input[name="username"]');
    await expect(usernameInput).toBeVisible();

    // Английская раскладка
    await usernameInput.fill('Test');
    await expect(usernameInput).toHaveValue('Test');

    // Русская раскладка
    await usernameInput.fill('Тест');
    await expect(usernameInput).toHaveValue('Тест');

    // Цифры
    await usernameInput.fill('123');
    await expect(usernameInput).toHaveValue('123');
});

test('Валидация длины псевдонима', async ({ page }) => {
    await page.goto('/');
    const usernameInput = page.locator('input[name="username"]');
    await expect(usernameInput).toBeVisible();

    // Ввод 16 символов
    await usernameInput.fill('testtesttesttest'); 
    // Ожидается, что валидация не даст ввести более 12 символов
    await expect(usernameInput).toHaveValue('testtesttest');
});