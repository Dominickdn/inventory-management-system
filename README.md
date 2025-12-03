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

### Access DB and site
Website: http://localhost:80
Adminer: http://localhost:8000/adminer

# How it works
- First navigate to Users and one
- Then Navigate to Inventory and click Add Inventory

--Once this is done you need to adjust the stock Limit and total/ total cannot be over stock limit so this needs to be changed first. Available will update automatically once total is incremented.

- You can click into inventory by clicking the table this will allow you to assign a user to an inventory item

- To unassign you can click the into the users table and it will allow you to unassign a device

- Assigned/Available tab allows you to view available inventory as well as all the inventory that has been assigned 

# Things I would like to change or add?
- Some of the code is a bit messy, I'd like to keep learning and go over everything again to see where I am not following best practices.
- I over engineered the one table component, I'd like to see how I can simplify it. 
- I'd like to also fix some bugs, notably the state on the navbar doesnt persist when clicking through on the table in inventory and some pop up's aren't showing because of how I set them up.
- Adding pagination to all the tables.
- Figuring out how to install dependencies automatically.
- Adding some form of authentication.

