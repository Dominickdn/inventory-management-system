# Prerequisites
- Docker  
- Docker Compose  
- Composer

# Getting Started
### Adding env files
Add an .env file in the main project directory with the following details - this is an example you should add your own details
```
COMPOSE_PROJECT_NAME="symfony"
DB_ROOT_PASSWORD="root"
DB_NAME="symfony"
DB_USER="user"
DB_PASSWORD="root"
DATABASE_URL="mysql://user:root@db/symfony?serverVersion=12.1.2-MariaDB&charset=utf8mb4"
```
You should also add an env file to the symfony directory - this is also just an example. 
Should work out of the box.
```
APP_ENV=dev
APP_SECRET=
DB_PASSWORD="root"
DEFAULT_URI=http://localhost
DATABASE_URL="mysql://user:root@db/symfony?serverVersion=12.1.2-MariaDB&charset=utf8mb4"
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
MAILER_DSN=null://null

```
### Install dependencies
- cd symfony
- composer install 

# Running the application
### Start the application
- docker-compose up or docker-compose up -d

### Stop Server
- docker-compose down

### clear db an cache
- docker-compose down -v

