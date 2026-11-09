# RussMarket Shift Scheduler

Интерфейс создания графика смен сотрудников по тестовому заданию RussMarket.

## Сейчас реализовано

- Vue 3 + TypeScript + Vite;
- недельный календарь сотрудников;
- добавление, редактирование и удаление смен;
- типы и цветовые состояния смен;
- применение шаблона;
- production API на Laravel + SQLite для чтения и изменения смен;
- offline fallback через `localStorage`;
- адаптивная рабочая область.

## Запуск

```bash
npm install
npm run dev
```

Production build:

```bash
npm run build
```

## Production

Frontend обращается к Laravel API по `/russmarket-api/api`; локальное хранилище
остаётся fallback для работы без сети. Production URL: `https://brainwwave.ru/russmarket/`.
