# Simple screen builder with multi-user data

This is a very simple screen builder with ability for users to live edit content.

This does not have any database and is to be used for very simple purposes.

### Install and run

1. Unzip the demo database in database.zip (it should create a database folder with .json in it)

2. `docker compose up -d`

### Run as developer

1. In a terminal `cd web/; php -S localhost:8000`

2. Go on localhost:8000, the initial user is admin:admin.

### Use the API (programmatic access)

With any application that can do POST:

```
POST http://localhost:8000/api.php
{
    "group": 5,
    "username": "admin",
    "password": "admin",
    "data": {
        "securite_message": "Incendie !"
    }
}
```

For instance with CURL:

`curl -XPOST -H 'content-type: application/json' http://localhost:8000/api.php -d '{"group": 5, "username": "admin", "password": "admin", "data": {"securite_message": "Incendie !"}}'`
