# Simple screen builder with multi-user data

This is a very simple screen builder with ability for users to live edit content.

This does not have any database and is to be used for very simple purposes.

### Install and run

1. Unzip the demo database in knauf-insulation.zip (it should create a database folder with multiple .json in it)

2. `docker compose up -d`

(Alternatively you can also directly run docker compose up, then go to http://localhost:8000/install.php and finally use the import feature inside the application)

### Backoffice documentation

#### What is in the initial database

The initial database contains:

- 2 screens with different layout
- 4 different variables input forms (called "Groupes de variables")
- 5 users (1 for each individual input form and one is the admin, each user's password is the same as the username)

#### See it in action

1. Click on "Écrans"
2. Click on the link looking like `/screen.php?id=X`
3. The url you just opened will be the url to use in the screens using for instance this tool https://github.com/RomaricMourgues/simple-electron-display

#### Update the screen content

1. Click on any of the first 4 menu items, for instance "Sécurité"
2. Edit the empty field and put "Incendie"
3. Press "Envoyer la mise à jour"
4. The screen will update and display a general message

#### Users and access restriction

So we saw that there is two sides: the displays "Écrans" and the inputs.

The inputs forms structures are defined by what is in "Groupes de variables".

As admin you can access everything but you'll notice that if you log in as "chargement:chargement" you will only see the chargement input form. This access restrictions are configured in the "Utilisateurs" section.

### Use the API (programmatic access) to update variables

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

### Update the software

1. `git pull`

2. `docker compose up -d --build`

### Run it as developer

1. In a terminal `cd web/; php -S localhost:8000`

2. Go on localhost:8000, the initial user is admin:admin.
