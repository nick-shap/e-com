# Laravel E-Commerce API

## Установка и запуск

1. Запустите контейнеры:
   ```bash
   docker-compose up -d
   ```

2. Выполните миграции и сиды:
   ```bash
   docker exec -it laravel-app php artisan migrate --seed
   ```

3. Запустите обработчик очередей:
   ```bash
   docker exec -it laravel-app php artisan queue:work
   ```

## Возможные улучшения
- Добавление тестов
- Интеграция Swagger документации

## API Документация

### Аутентификация

Перед выполнением защищённых запросов необходимо получить токен:

**POST /api/login/**

Параметры:
```json
{
"email": "user@example.com",
"password": "user123"
}
```

Ответ:
```json
{
"token": "ВАШ_ТОКЕН"
}
```

Добавьте полученный токен в заголовок \`Authorization\`:
```
Authorization: Bearer ВАШ_ТОКЕН
```

**Важно:** Все запросы должны содержать заголовок:
```
Accept: application/json
```

---

### Продукты

#### Получить список продуктов
**GET /api/products/**

Параметры:
- `per_page` — товаров на странице
- `sort_by` - поле сортировки
- `sort_order` - порядок сортировки

#### Получить продукт по ID
**GET /api/products/{id}**

---

### Корзина

#### Получить корзину
**GET /api/cart/**

#### Добавить товар
**POST /api/cart/add/**

Параметры:
- `product_id` - ID товара
- `quantity` - количество

#### Удалить товар
**POST /api/cart/remove/**

Параметры:
- `product_id` - ID товара
- `quantity` - количество

---

### Заказы

#### Получить список заказов
**GET /api/orders/**

Параметры:
- `per_page` — заказов на странице
- `sort_by` - поле сортировки
- `sort_order` - порядок сортировки
- `status` - статус заказов

#### Создать заказ
**POST /api/orders/create/**

Параметры:
- `payment_method_id` - ID способа оплаты

Ответ содержит:
- `payment_url` - ссылка для оплаты

#### Получить заказ по ID
**GET /api/orders/{id}**

---

## Postman коллекция
Файл: `e-com.postman_collection.json`
