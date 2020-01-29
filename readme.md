# API NEWS

API News for test.

## Prerequisites

**Install NGINX**

```bash
sudo apt install -y nginx
```

**Install MySQL**

```bash
sudo apt update
sudo apt install -y mysql-server
```

**Install PHP and required service**

```bash
sudo apt install -y php-fpm php-mbstring php-gd php-xml php-mysql php-cli php-zip unzip curl openssl pkg-config git autoconf automake libxml2-dev libcurl4-openssl-dev libssl-dev openssl gettext libicu-dev libmcrypt-dev libmcrypt4 libbz2-dev libreadline-dev gettext build-essential libmhash-dev libmhash2 libicu-dev libxslt-dev zlib1g-dev libzip-dev make
```

**Install PHPBrew**

Please check the [Official PHPBrew Documentation](https://github.com/phpbrew/phpbrew) for installation.

**Install PHP 7.3 on PHPBrew**

```bash
phpbrew install 7.3 +default +fpm +mysql +pdo +xdebug
# See installed php version with phpbrew list
phpbrew switch php-7.3.x
```

**Install Composer**

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
```

## Installation

Please check the official lumen installation guide for server requirements before you start. [Lumen Official Documentation](https://lumen.laravel.com/docs/6.x)

**Clone this repository**

```bash
git clone git@github.com:humamalamin/api_news.git
# Switch to the repository folder
cd api_news
chmod +x artisan
chmod 777 -R storage/

```

**Install Dependencies**

```bash
composer install
```

**Copy the `.env.example` file to `.env`**

```bash
cp .env.example .env
```

Make the required configuration changes in the `.env` file

## API Documentation

We use [Swagger](https://swagger.io/) for api documentation

**Run the swagger api generator to generate documentation**

```bash
php artisan swagger-lume:generate
```

API Documentation can be accessed at `{base_url}/api/documentation`

## Testing

**Prerequisites**

* Create database testing with prefix `_test`
* Copy the `.env.example` file to `.env.testing`
    ```bash
    cp .env.example .env.testing
    ```
* Change `APP_ENV=local` to `APP_ENV=testing` and make the required configuration changes in the `.env.testing` file

**Run Testing**

```bash
# Check code metric
vendor/bin/phpmd app text phpmd_rulesets.xml
vendor/bin/phpmd tests text phpmd_rulesets.xml
# Check code standard
vendor/bin/phpcs app --standard=PSR2 -n
vendor/bin/phpcs tests --standard=PSR2 -n
# Unit testing
vendor/bin/phpunit --debug --stop-on-failure --stop-on-error
# Check code coverage
php artisan news:coverage-check
```

or you can run all of these commands with the `make` command

```bash
make test
```

## Directory Structure

* `app\Models` - Contains all the Eloquent models
* `app/Http/Controllers` - Contains all the api controllers
* `app/Http/Middleware` - Contains all the api middleware
* `app/Http/Resources` - Contains all the data resources
* `config` - Contains all the application configuration files
* `database/factories` - Contains the model factory for all the models
* `routes` - Contains all the api routes defined in api.php file
* `tests` - Contains all the application tests

For a more complete explanation, please visit [Laravel Directory Structure](https://laravel.com/docs/6.x/structure)

**Note** :

* Please make sure to update tests as appropriate.

* It's recommended to run **Testing** command before submit a pull request.

## Reference

* [Laravel CORS](https://github.com/spatie/laravel-cors)
* [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [PHPMD](https://github.com/phpmd/phpmd)
