Weather Data Collector
======================

This is the deliverable of an assessment project by [Theofanis Vardatsikos](https://www.linkedin.com/in/theofanis-vardatsikos/) ([vardtheo@gmail.com](mailto:vardtheo@gmail.com)). 

# Request 

> **TL;DR** Laravel app to collect weather data for multiple locations from multiple weather data providers.

The goal is to develop an application that will fetch weather forecast data for a single location, on daily basis. 
Data will be retrieved from 2 different weather API and will be stored in a database.
The application should be dockerized and ready to be installed and run.

- Fetch and store temperature and precipitation (on hourly and daily step).
- Fetch will take place once per day
- Application will be implemented in such way to support additions of more locations and API.

# Installation

The system is built with PHP 8.1 & Laravel 10.
For docker setup [Laravel Sail](https://laravel.com/docs/10.x/sail) is used, you must already have Docker installed.

To download project and install required dependencies and start Sail (Docker) run: 

```bash
git clone https://github.com/theofanisv/weather-data-collector.git
cd weather-data-collector

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
    
./vendor/bin/sail up
```

Afterwards, log into container's bash and set up the Laravel app:

```bash
php artisan migrate:fresh --seed
```

