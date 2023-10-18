<span name="readme-top"></span>

# Roulette API

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
php artisan migrate:fresh --seed
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

## Contribute

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right"><a href="#readme-top">back to top</a></p>

## License

Distributed under the MIT License. See `LICENSE` for more information.

<p align="right"><a href="#readme-top">back to top</a></p>

## Contact

- [MARECHAL Armand](https://github.com/Penfu)

<p align="right"><a href="#readme-top">back to top</a></p>
