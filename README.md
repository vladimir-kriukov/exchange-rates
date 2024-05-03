# Get exchange rates via Symfony

## Description

* Import currency rates from different sources
  * https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml
  * https://api.coindesk.com/v1/bpi/currentprice.json
* Get rate in a format "2 BTC = 118867.60780000 USD"
* Covered with PhpUnit tests

## Two commands available

* _rates:import_ - Import exchange rates
* _rates:get_ - Get rates

## 1. Quick start

### Requirements

Docker Desktop (Windows, MacOS) or docker-compose (Linux)

### Installation

```bash
git clone https://github.com/vladimir-kriukov/exchange-rates.git
cd exchange-rates
docker compose up -d --build
docker compose exec php composer install 
docker compose exec php bin/console doctrine:database:create --if-not-exists
docker compose exec php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec php bin/console rates:import
docker compose exec php bin/console rates:get 2 BTC USD
```

### Usage

Import currency rates

```bash
php bin/console rates:import
```

Use

```bash
php bin/console rates:get <amount> <from> <to>
```

Example

```bash
php bin/console rates:get 2 BTC USD
```

should output

```bash
[OK] 2 BTC = 118867.60780000 USD
```

### Testing

```bash
cd exchange-rates
docker compose exec php composer install --no-interaction
docker compose exec php bin/console --env=test doctrine:database:create
docker compose exec php bin/console --env=test doctrine:migrations:migrate --no-interaction
docker compose exec php bin/phpunit
```

## How to add a new provider

* Implement `\App\Providers\RatesProviderInterface` or extend `\App\Providers\RatesProvider`
* Implement `\App\Providers\RatesProvider::transform(array $data): RateDto[]` method.
* Add provider to _config/services.yaml_ with arguments and `app.rates_provider` tag.
* Add URL and base currency to _.env_ file.
