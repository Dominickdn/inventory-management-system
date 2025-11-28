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
```
You should also add an env file to the symfony directory - this is also just an example.
```
APP_ENV=dev
APP_SECRET=
DB_PASSWORD="root"
DEFAULT_URI=http://localhost
```

### Install dependencies
- cd symfony
- composer install 
- cd ..

# Running the application
### Start the application
- docker-compose up --build
### clear db an cache
- docker-compose down -v

