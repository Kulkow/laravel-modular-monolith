# Документация API - модуля Identity

**Version:** 1.0.0

## Endpoints

### `GET /api/identity/auth/user`

**Summary:** Текущий пользователь со всеми разрешениями

**Operation ID:** `982eca91964db2aa2831a53865e8a537`

#### Responses

##### 200

Данные пользователя с полным списком разрешений

##### 401

Не авторизован

##### 500

Внутренняя ошибка сервера

### `POST /api/identity/auth/login`

**Summary:** Авторизация

**Operation ID:** `82ab0a88cf68acf2652c2be1caf087e5`

#### Request Body

**Required:** Yes

**Content-Type:** `application/json`

**Schema:**

```json
{
  "email": string, (required)
  "password": string, (required)
}
```

#### Responses

##### 200

Успешная авторизация

##### 422

Ошибка валидации / неверные данные

### `POST /api/identity/auth/logout`

**Summary:** Выход

**Operation ID:** `f6302e8011f614198d60d63dc3ec0219`

#### Responses

##### 200

Успешный выход

### `GET /api/identity/users`

**Summary:** Список пользователей

**Operation ID:** `4c5927a5c4c5f2519ed5a01e047ab323`

#### Responses

##### 200

Успешный ответ

**Content-Type:** `application/json`

**Schema:**

```json
{
  "data": array,
}
```

##### 500

Внутренняя ошибка сервера

### `POST /api/identity/users`

**Summary:** Создать пользователя

**Operation ID:** `00b0193d95e08342dcaefe4dd5e001de`

#### Request Body

**Required:** Yes

**Content-Type:** `application/json`

**Schema:**

```json
{
  "name": string, (required)
  "email": string, (required)
  "password": string, (required)
  "password_confirmation": string, (required)
  "role_ids": array,
}
```

#### Responses

##### 201

Пользователь создан

**Content-Type:** `application/json`

**Schema:**

```json
{
  "id": integer,
}
```

##### 422

Ошибка валидации

##### 500

Внутренняя ошибка сервера

### `GET /api/identity/users/{id}`

**Summary:** Получить пользователя

**Operation ID:** `259e8884540d253f00f093d017a5dcb0`

#### Parameters

| Name | In | Required | Type | Description |
|------|----|----------|------|-------------|
| `id` | `path` | Yes | `integer` |  |

#### Responses

##### 200

Успешный ответ

**Content-Type:** `application/json`

**Schema:**

```json
{
  "id": integer,
  "email": string,
  "name": string,
  "status": object,
  "roles": array,
  "created_at": string,
}
```

##### 404

Пользователь не найден

##### 500

Внутренняя ошибка сервера

### `PATCH /api/identity/users/{id}`

**Summary:** Обновить пользователя

**Operation ID:** `c9a97256a8d6a3df383d271716f4104e`

#### Parameters

| Name | In | Required | Type | Description |
|------|----|----------|------|-------------|
| `id` | `path` | Yes | `integer` |  |

#### Request Body

**Required:** No

**Content-Type:** `application/json`

**Schema:**

```json
{
  "name": string,
  "email": string,
}
```

#### Responses

##### 200

Пользователь обновлён

**Content-Type:** `application/json`

**Schema:**

```json
{
  "message": string,
}
```

##### 404

Пользователь не найден

##### 422

Ошибка валидации

##### 500

Внутренняя ошибка сервера

### `PATCH /api/identity/users/{id}/deactivate`

**Summary:** Деактивировать пользователя

**Operation ID:** `427c1abef52bcf1864b8dc88c007874e`

#### Parameters

| Name | In | Required | Type | Description |
|------|----|----------|------|-------------|
| `id` | `path` | Yes | `integer` |  |

#### Responses

##### 200

Пользователь деактивирован

**Content-Type:** `application/json`

**Schema:**

```json
{
  "message": string,
}
```

##### 404

Пользователь не найден

##### 500

Внутренняя ошибка сервера

### `POST /api/identity/users/{id}/roles`

**Summary:** Назначить роль пользователю

**Operation ID:** `20d379268457093a56ad26fc8fc15b37`

#### Parameters

| Name | In | Required | Type | Description |
|------|----|----------|------|-------------|
| `id` | `path` | Yes | `integer` |  |

#### Request Body

**Required:** Yes

**Content-Type:** `application/json`

**Schema:**

```json
{
  "role_id": integer, (required)
}
```

#### Responses

##### 200

Роль назначена

**Content-Type:** `application/json`

**Schema:**

```json
{
  "message": string,
}
```

##### 404

Пользователь или роль не найдены

##### 422

Ошибка валидации

##### 500

Внутренняя ошибка сервера

### `DELETE /api/identity/users/{id}/roles`

**Summary:** Отозвать роль у пользователя

**Operation ID:** `f9cca4775a14627a91a750df0648028c`

#### Parameters

| Name | In | Required | Type | Description |
|------|----|----------|------|-------------|
| `id` | `path` | Yes | `integer` |  |

#### Request Body

**Required:** Yes

**Content-Type:** `application/json`

**Schema:**

```json
{
  "role_id": integer, (required)
}
```

#### Responses

##### 200

Роль снята

**Content-Type:** `application/json`

**Schema:**

```json
{
  "message": string,
}
```

##### 404

Пользователь не найден

##### 422

Ошибка валидации

##### 500

Внутренняя ошибка сервера

### `POST /api/identity/users/auto-login/{id}`

**Summary:** Авторизовать пользователя

**Operation ID:** `0c157b36f0c094013bcd4465f53fd70b`

#### Parameters

| Name | In | Required | Type | Description |
|------|----|----------|------|-------------|
| `id` | `path` | Yes | `integer` |  |

#### Responses

##### 200

Успешный выход

## Schemas

### `AssignRoleResponse`

```json
{
  "message": string,
}
```

### `RevokeRoleResponse`

```json
{
  "message": string,
}
```

### `CreateUserResponse`

```json
{
  "id": integer,
}
```

### `DeactivateUserResponse`

```json
{
  "message": string,
}
```

### `GetUserResponse`

```json
{
  "id": integer,
  "email": string,
  "name": string,
  "status": object,
  "roles": array,
  "created_at": string,
}
```

### `ListUsersResponse`

```json
{
  "data": array,
}
```

### `UpdateUserResponse`

```json
{
  "message": string,
}
```

### `AssignRoleRequest`

```json
{
  "role_id": integer, (required)
}
```

### `CreateUserRequest`

```json
{
  "name": string, (required)
  "email": string, (required)
  "password": string, (required)
  "password_confirmation": string, (required)
  "role_ids": array,
}
```

### `UpdateUserRequest`

```json
{
  "name": string,
  "email": string,
}
```

