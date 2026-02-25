## 🛒 Shop 

Backend API для интернет-магазина с поддержкой пользователей, ролей, товаров, категорий, корзины и заказов.  


---

### Стек технологий

- **PHP** 8.2
- **Laravel** 12
- **MySQL**
- **XAMPP**
- **Laravel Sanctum** — API-аутентификация
- **Postman** — тестирование API

---

### Основные принципы:
- тонкие контроллеры
- бизнес-логика вынесена в Service layer
- валидация через Form Request
- доступ через Policies
- транзакции для критичных операций (заказы)

### Аутентификация
#### Используется Laravel Sanctum:
1. Регистрация
2. Логин
3. Получение токена
4. Переда токена


---
### Пользователи и роли

#### Администратор:
- создание (редактирование) товаров и категорий
- доступ к просмотру всех заказов
- редактирование статуса заказа

#### Пользователь:
- просмотр и добавление товаров в корзину
- редактирование корзины
- оформление и просмотр заказа

#### Что реализовано:

- REST API
- роли и доступ
- Service Layer
- транзакции
- защита от некорректных данных
- архитектура, готовая к масштабированию

openapi: 3.0.3
info:
  title: Shop Backend API
  version: 1.0.0
  description: REST API for managing products and categories

servers:
  - url: http://localhost/api/v1
    description: Local development server

paths:

  /auth/register:
    post:
      summary: Register new user
      tags: [Auth]
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/RegisterRequest'
      responses:
        201:
          description: User successfully registered
        422:
          description: Validation error

  /auth/login:
    post:
      summary: Login user
      tags: [Auth]
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/LoginRequest'
      responses:
        200:
          description: Login successful
        401:
          description: Invalid credentials

  /products:
    get:
      summary: Get all products
      tags: [Products]
      responses:
        200:
          description: List of products

    post:
      summary: Create product
      tags: [Products]
      security:
        - bearerAuth: []
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/StoreProductRequest'
      responses:
        201:
          description: Product created
        422:
          description: Validation error
        401:
          description: Unauthorized

  /products/{id}:
    get:
      summary: Get product by ID
      tags: [Products]
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        200:
          description: Product data
        404:
          description: Product not found

    put:
      summary: Update product
      tags: [Products]
      security:
        - bearerAuth: []
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        required: false
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/UpdateProductRequest'
      responses:
        200:
          description: Product updated
        404:
          description: Product not found
        401:
          description: Unauthorized

    delete:
      summary: Delete product
      tags: [Products]
      security:
        - bearerAuth: []
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        204:
          description: Product deleted
        401:
          description: Unauthorized

  /categories:
    get:
      summary: Get all categories
      tags: [Categories]
      responses:
        200:
          description: List of categories

components:

  securitySchemes:
    bearerAuth:
      type: http
      scheme: bearer
      bearerFormat: JWT

  schemas:

    RegisterRequest:
      type: object
      required:
        - name
        - email
        - password
      properties:
        name:
          type: string
          example: John Doe
        email:
          type: string
          format: email
          example: john@example.com
        password:
          type: string
          format: password
          example: password123

    LoginRequest:
      type: object
      required:
        - email
        - password
      properties:
        email:
          type: string
          format: email
          example: john@example.com
        password:
          type: string
          format: password
          example: password123

    StoreProductRequest:
      type: object
      required:
        - name
        - price
        - category_id
        - stock
        - slug
      properties:
        name:
          type: string
          example: iPhone 15
        description:
          type: string
          example: Latest Apple smartphone
        price:
          type: number
          format: float
          example: 999.99
        category_id:
          type: integer
          example: 1
        stock:
          type: integer
          example: 50
        slug:
          type: string
          example: iphone-15
        is_active:
          type: boolean
          example: true

    UpdateProductRequest:
      type: object
      properties:
        name:
          type: string
        description:
          type: string
        price:
          type: number
          format: float
        category_id:
          type: integer
        stock:
          type: integer
        slug:
          type: string
        is_active:
          type: boolean

    Product:
      type: object
      properties:
        id:
          type: integer
        name:
          type: string
        description:
          type: string
        price:
          type: number
        category_id:
          type: integer
        stock:
          type: integer
        slug:
          type: string
        is_active:
          type: boolean
        created_at:
          type: string
          format: date-time
        updated_at:
          type: string
          format: date-time

    Category:
      type: object
      properties:
        id:
          type: integer
        name:
          type: string
        slug:
          type: string
        created_at:
          type: string
          format: date-time
        updated_at:
          type: string
          format: date-time
