# RussMarket Shift Scheduler

Интерфейс создания графика смен сотрудников по тестовому заданию RussMarket.

## Сейчас реализовано

- Vue 3 + TypeScript + Vite;
- недельный календарь сотрудников;
- добавление, редактирование и удаление смен;
- типы и цветовые состояния смен;
- применение шаблона;
- сохранение расписания в `localStorage`;
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

## Следующий слой

Frontend использует простой локальный storage-адаптер. Для production-сдачи его нужно заменить на Laravel API с сущностями `employees`, `shifts` и `shift_templates`, сохранив тот же контракт данных.
