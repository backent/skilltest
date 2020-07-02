# Laravel with docker

## Installation
Install composer
```bash
./composer install
```

Make .env from .env.example
```bash
cp .env.example .env
```


Build containers using docker-compose
```bash
docker-compose up -d
```
Generate app key
```bash
./app_bash php artisan key:generate
```

Migrate Database
```bash
./app_bash php artisan migrate
```

Install Passport
```bash
./app_bash php artisan passport:install
```
Storage link
```bash
./app_bash php artisan storage:link
```

Install package yarn
```bash
./app_bash yarn
```


Set Project permission
```bash
./set-permission
```
Now you can play with it ^^

## docker command helper

### Run Composer
Execute
```bash
./composer {argument}
```
Example
```bash
./composer install
```

## Database
This project using mysql database.
You can config the database at docker-compose.yml



