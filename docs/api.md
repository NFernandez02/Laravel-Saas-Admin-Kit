# Api Documentation

## Authorization

All endpoints except login require a Sanctum token. Endpoints that require a specific permission will be stated. By default, the admin have all permissions.

Protected endpoints must include:

Header:

```text
Authorization: Bearer {token}
```

---

Admin endpoints are prefixed with:

/api/admin

## Common Responses

### 401 Unauthorized

Returned when the request does not contain a valid authentication token.

```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden

Returned when the authenticated user does not have permission to perform the action.

```json
{
  "message": "This action is unauthorized."
}
```

### 422 Validation Error

Returned when submitted data fails validation.

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field": [
      "Validation message"
    ]
  }
}
```

### 409 Conflict

Returned when the requested action cannot be completed because the resource is currently in use.

Example:

```json
{
  "message": "This role is assigned to a user"
}
```

## Pagination (Global)

Default page size is 10 unless otherwise specified via ?per_page
All paginated endpoints return:

- data
- links
- meta

Supports:
- ?page=
- ?search=

## Authentication

### Login

POST /api/login

Request Fields

| Field    | Type   | Required |
| -------- | ------ | -------- |
| email    | string | Yes      |
| password | string | Yes      |

Success Response (200 OK)
```json
{
    "token": "1|abcdefghijklmnopqrstuvwxyz"
}
```
### Logout

POST /api/logout

Success Response (200 OK)
```json
{
    "message": "Logged Out Successfully."
}
```
## Profile

### Get Profile

GET /api/profile

Success Response (200 OK)
```json
{
    "name": "Admin User",
    "email": "admin@example.com",
    "avatar": "/storage/avatars/avatar.png",
    "bio": "System Administrator"
}
```
### Update Profile

PUT /api/profile

Content-Type:
multipart/form-data

Request Fields

| Field  | Type   | Required |
| ------ | ------ | -------- |
| name   | string | Yes      |
| email  | string | Yes      |
| bio    | string | No       |
| avatar | file   | No       |

Success Response (200 OK)
```json
{
    "name": "Admin User",
    "email": "admin@example.com",
    "avatar": "http://localhost/storage/avatars/abc123.jpg",
    "bio": "System Administrator"
}
```
### Update Password

PUT api/password

Request Fields

| Field            | Type   | Required |
| ---------------- | ------ | -------- |
| current_password | string | Yes      |
| password         | string | Yes      |

Success Response (200 OK)
```json
{
    "message": "Password changed successfully."
}
```
## Users

### Get Users (Paginated)

GET /api/admin/users

## Query Parameters

| Parameter | Type | Required | Description |
|----------|------|----------|-------------|
| page     | int  | no       | Page number (default: 1) |
| search   | string | no     | Search by name or email |

**Permissions**

users.view

Success Response (200 OK)
```json
{
  "data": [
    {
      "id": 1,
      "name": "Admin User",
      "email": "admin@admin.com",
      "role": {
        "id": 1,
        "name": "admin"
      }
    }
  ],
  "links": {
    "first": "http://localhost/api/admin/users?page=1",
    "last": "http://localhost/api/admin/users?page=5",
    "prev": null,
    "next": "http://localhost/api/admin/users?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 10,
    "to": 10,
    "total": 50
  }
}
```
### Get User

GET /api/admin/users/{user}

**Permissions**

users.view

Success Response (200 OK)
```json
{
    "id": 1,
      "name": "Admin User",
      "email": "admin@admin.com",
      "role": {
        "id": 1,
        "name": "admin"
      }
}
```
### Create User

POST /api/admin/users

**Permissions**

users.create

Request Fields

| Field    | Type    | Required |
| -------- | ------- | -------- |
| name     | string  | Yes      |
| email    | string  | Yes      |
| password | string  | Yes      |
| role_id  | integer | Yes      |

Success Response (200 OK)
```json
{
    "id": 2,
      "name": "User",
      "email": "user@user.com",
      "role": {
        "id": 1,
        "name": "admin"
      }
}
```
### Update User

PUT /api/admin/users/{user}

**Permissions**

users.update

Request Fields

| Field   | Type    | Required |
| ------- | ------- | -------- |
| name    | string  | Yes      |
| role_id | integer | Yes      |

Success Response (200 OK)
```json
{
    "id": 2,
      "name": "User",
      "email": "user@user.com",
      "role": {
        "id": 1,
        "name": "admin"
      }
}
```
### Delete User

DELETE /api/admin/users/{user}

**Permissions**

users.delete

Success Response (200 OK)
```json
{
    "message": "User deleted successfully."
}
```
## Roles

### Get Roles (Paginated)

GET /api/admin/roles

## Notes

- Default page size is 10
- Use `?page=2` to navigate pages
- Use `?search=` to filter users by name or email

## Query Parameters

| Parameter | Type | Required | Description |
|----------|------|----------|-------------|
| page     | int  | no       | Page number (default: 1) |
| search   | string | no     | Search by name or email |

**Permissions**

roles.view

Success Response (200 OK)
```json
{
  "data": [
    {
      "id": 1,
      "name": "admin",
      "users_count": 1,
      "permissions": [
        "users.view",
        "users.create",
        "users.update",
        "users.delete"
      ]
    }
  ],
  "links": {
    "first": "http://localhost/api/admin/roles?page=1",
    "last": "http://localhost/api/admin/roles?page=5",
    "prev": null,
    "next": "http://localhost/api/admin/roles?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 10,
    "to": 10,
    "total": 50
  }
}
```
### Get Role

GET /api/admin/roles/{role}

**Permissions**

roles.view

Success Response (200 OK)
```json
{
    "id": 1,
      "name": "admin",
      "users_count": 1,
      "permissions": [
        "users.view",
        "users.create",
        "users.update",
        "users.delete"
      ]
}
```
### Create Role

POST /api/admin/roles

**Permissions**

roles.create

Request Fields

| Field       | Type   | Required |
| ----------- | ------ | -------- |
| name        | string | Yes      |
| permissions | array  | Yes      |

Success Response (200 OK)
```json
{
    "id": 2,
      "name": "user",
      "users_count": 0,
      "permissions": [
        "users.view",
        "users.create",
        "users.update",
        "users.delete"
      ]
}
```
### Update Role

PUT /api/admin/roles/{role}

**Permissions**

roles.update

Request Fields

| Field       | Type   | Required |
| ----------- | ------ | -------- |
| name        | string | Yes      |
| permissions | array  | Yes      |

Success Response (200 OK)
```json
{
    "id": 2,
      "name": "user",
      "users_count": 0,
      "permissions": [
        "users.view",
        "users.create",
        "users.update",
        "users.delete"
      ]
}
```
### Delete Role

DELETE /api/admin/roles/{role}

**Permissions**

roles.delete

Success Response (200 OK)
```json
{
    "message": "Role deleted successfully."
}
```
## Permissions

### Get Permissions

GET /api/admin/permissions

## Notes

- Default page size is 10
- Use `?page=2` to navigate pages
- Use `?search=` to filter users by name or email

## Query Parameters

| Parameter | Type | Required | Description |
|----------|------|----------|-------------|
| page     | int  | no       | Page number (default: 1) |
| search   | string | no     | Search by name or email |

**Permissions**

permissions.view

Success Response (200 OK)
```json
{
  "data": [
    {
      "id": 1,
      "name": "users.view",
      "roles_count": 1,
      "roles": [
        "admin"
      ]
    }
  ],
  "links": {
    "first": "http://localhost/api/admin/permissions?page=1",
    "last": "http://localhost/api/admin/permissions?page=5",
    "prev": null,
    "next": "http://localhost/api/admin/permissions?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 10,
    "to": 10,
    "total": 50
  }
}
```
### Get Permission

GET /api/admin/permissions/{permission}

**Permissions**

permissions.view

Success Response (200 OK)
```json
{
    "id": 1,
    "name": "users.view",
    "roles_count": 1,
    "roles": [
        "admin"
    ]
}
```
### Update Permission

PUT /api/admin/permissions/{permission}

**Permissions**

permissions.update

Request Fields

| Field | Type   | Required |
| ----- | ------ | -------- |
| name  | string | Yes      |

Success Respone (200 OK)
```json
{
    "id": 1,
    "name": "users.view",
    "roles_count": 1,
    "roles": [
        "admin"
    ]
}
```
### Delete Permission

DELETE /api/admin/permissions/{permission}

**Permissions**

permissions.delete

Success Response (200 OK)
```json
{
    "message": "Permission deleted successfully."
}
```

## Audit Logs

### Get Logs

GET /api/admin/logs

## Notes

- Default page size is 10
- Use `?page=2` to navigate pages
- Use `?search=` to filter users by name or email

## Query Parameters

| Parameter | Type | Required | Description |
|----------|------|----------|-------------|
| page     | int  | no       | Page number (default: 1) |
| search   | string | no     | Filter logs by associated user name |

**Permissions**

logs.view

Success Response (200 OK)
```json
{
  "data": [
    {
      "id": 1,
      "user": {
        "user_id": 1,
        "user_name": "Admin"
      },
      "action": "created",
      "target_type": "User",
      "target_id": 2,
      "description": "created user John Doe",
      "date": "2026-05-30 08:33:11"
    }
  ],
  "links": {
    "first": "http://localhost/api/admin/logs?page=1",
    "last": "http://localhost/api/admin/logs?page=5",
    "prev": null,
    "next": "http://localhost/api/admin/logs?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 10,
    "to": 10,
    "total": 50
  }
}
```
