# Shop test

## Installation instructions

First, install the package dependencies

```bash
composer install
```

You can either run the project with the in-built webserver, or in docker,
I used the former for development:

```bash
php artisan serve
```

```bash
php artisan migrate:fresh --seed
```

To run the testsuite use:

```bash
php artisan test
```

## Postman testing

The project root contains a _"WatchTowr Test API.postman_collection.json"_
file, which can be imported into Postman for API testing, and includes
requests to all endpoints. 

## Endpoints usage

### User registration

```http
POST /register HTTP/1.1
Host: localhost
Content-Type: application/json
```

> ### Request form parameters

| `name` | _string_ <br>
| `email` | _string_ | must be a valid email<br>
| `password` | _string_<br>

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8

{
    "status": true,
    "message": "User Created Successfully",
    "token": "2|c63LK0iCQ12Es7lLJ6HJZwwLZHB1oCfBsFyKokz9"
}
```


```http
POST /login HTTP/1.1
Host: localhost
Content-Type: application/json
```

> ### Request form parameters

| `email` | _string_ | must be a valid email<br>
| `password` | _string_<br>

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8

{
    "status": true,
    "message": "User Logged In Successfully",
    "token": "3|lV9hl72MwmTkFaaOynP4bZiD0LlXdPrpWs9ObbD2"
}
```

Use the token from Login in Bearer Authorization to get access to the other API funtions.

#### List all products

```http
GET /products HTTP/1.1
Host: localhost
Content-Type: application/json
Authorization: Bearer 3|lV9hl72MwmTkFaaOynP4bZiD0LlXdPrpWs9ObbD2
```

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8
{
    {
        "id": 1,
        "name": "Atque.",
        "description": "Inventore magni qui qui esse. Ab porro eum animi quibusdam provident ut ab. Debitis magnam aperiam molestiae voluptate nulla explicabo.",
        "price": 663.37,
        "quantity": 43,
        "created_at": "2025-02-12T20:40:54.000000Z",
        "updated_at": "2025-02-12T20:40:54.000000Z"
    },
    {
        "id": 2,
        "name": "Nostrum fugit.",
        "description": "Et et distinctio amet aut ullam quibusdam. Et ut quos et aperiam. Voluptatem sunt perspiciatis aut.",
        "price": 838.25,
        "quantity": 2,
        "created_at": "2025-02-12T20:40:54.000000Z",
        "updated_at": "2025-02-12T20:40:54.000000Z"
    },
}

```

#### View one product

```http
GET /products/{id} HTTP/1.1
Host: localhost
Content-Type: application/json
Authorization: Bearer 3|lV9hl72MwmTkFaaOynP4bZiD0LlXdPrpWs9ObbD2
```

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8
{
    "id": 1,
    "name": "Atque.",
    "description": "Inventore magni qui qui esse. Ab porro eum animi quibusdam provident ut ab. Debitis magnam aperiam molestiae voluptate nulla explicabo.",
    "price": 663.37,
    "quantity": 43,
    "created_at": "2025-02-12T20:40:54.000000Z",
    "updated_at": "2025-02-12T20:40:54.000000Z"
}

```

#### Add product to cart

```http
POST /cart/add HTTP/1.1
Host: localhost
Content-Type: application/json
Authorization: Bearer 3|lV9hl72MwmTkFaaOynP4bZiD0LlXdPrpWs9ObbD2
```

> ### Request object example

```json
{
    "product_id": 2,
    "quantity": 1
}
```

| `product_id` | _int_ | must be existing product_id
| `quantity` | _int_ | Minimum 1<br>

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8
{
    "message": "Product added"
}

```

#### Remove product from cart

```http
POST /cart/remove HTTP/1.1
Host: localhost
Content-Type: application/json
Authorization: Bearer 3|lV9hl72MwmTkFaaOynP4bZiD0LlXdPrpWs9ObbD2
```

> ### Request object example

```json
{
    "product_id": 2
}
```

| `product_id` | _int_ | must be existing product_id

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8
{
    "message": "Product removed"
}

```

#### List cart contents

```http
GET /cart HTTP/1.1
Host: localhost
Content-Type: application/json
Authorization: Bearer 3|lV9hl72MwmTkFaaOynP4bZiD0LlXdPrpWs9ObbD2
```

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8
{
        "id": 1,
        "user_id": 2,
        "created_at": "2025-02-12T20:55:03.000000Z",
        "updated_at": "2025-02-12T20:55:03.000000Z",
        "products": [
            {
                "id": 1,
                "name": "Atque.",
                "description": "Inventore magni qui qui esse. Ab porro eum animi quibusdam provident ut ab. Debitis magnam aperiam molestiae voluptate nulla explicabo.",
                "price": 663.37,
                "quantity": 43,
                "created_at": "2025-02-12T20:40:54.000000Z",
                "updated_at": "2025-02-12T20:40:54.000000Z",
                "pivot": {
                    "cart_id": 1,
                    "product_id": 1,
                    "quantity": 2,
                    "created_at": "2025-02-12T20:55:03.000000Z",
                    "updated_at": "2025-02-12T20:55:03.000000Z"
                }
            }
        ]
    }

```

```http
POST /order/checkout HTTP/1.1
Host: localhost
Content-Type: application/json
Authorization: Bearer 3|lV9hl72MwmTkFaaOynP4bZiD0LlXdPrpWs9ObbD2
```

> ### Request object example

```json
{
    
}
```

No need for any request object

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8
{
    "message": "Order Created"
}

```

#### List of orders

```http
GET /orders HTTP/1.1
Host: localhost
Content-Type: application/json
Authorization: Bearer 3|lV9hl72MwmTkFaaOynP4bZiD0LlXdPrpWs9ObbD2
```

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8
{
    "id": 1,
    "user_id": 2,
    "status": "paid",
    "total_price": "1326.74",
    "created_at": "2025-02-12T20:56:08.000000Z",
    "updated_at": "2025-02-12T20:56:08.000000Z",
    "products": [
        {
            "id": 1,
            "name": "Atque.",
            "description": "Inventore magni qui qui esse. Ab porro eum animi quibusdam provident ut ab. Debitis magnam aperiam molestiae voluptate nulla explicabo.",
            "price": 663.37,
            "quantity": 43,
            "created_at": "2025-02-12T20:40:54.000000Z",
            "updated_at": "2025-02-12T20:40:54.000000Z",
            "pivot": {
                "order_id": 1,
                "product_id": 1,
                "quantity": 2,
                "created_at": "2025-02-12T20:56:08.000000Z",
                "updated_at": "2025-02-12T20:56:08.000000Z"
            }
        }
    ]
}
```

#### Error handling and responses

Validation and processing erorrs will return http 422 status code
with the relevant validation error message.

Example:

```http
HTTP/1.1 422 Unprocessable entity
Content-Type: application/json; charset=UTF-8
{
    "product_id": [
        "The selected product id is invalid."
    ]
}
```
