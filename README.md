# Roulette-API

## Local development

Clone the repo into your environement.

```bash
git clone https://github.com/Penfu/Roulette-API.git
```

### Package manager

Install packages dependencies.

```bash
composer install
npm install
```

### Environment and Database

Setup the environment variables by making your own **.env** file from the example one.
Then run the migrations and seeders.

```bash
php artisan migrate --seed
```

### Public resources

Build the resources to public directory.

```bash
npm run dev
```

Optionaly watch the changes and auto build.

```bash
npm run watch
```

### Run web server

Generate the encryption key

```bash
php artisan key:generate
php artisan serve
```

### Run websockets

```bash
php artisan websockets:serve
```

### Run the game

```bash
php artisan short-schedule:run
```
