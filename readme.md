## TTC Surveyplatform

### Requirements

- php 5.5.9+ or 7 with openssl, mbstring and tokenizer
- composer
- npm
- grunt-cli globally installed `npm install -g grunt-cli`

### Installation notes

- create a database
- create a test database if you want to run unit tests
- copy `.env.example` to `.env` and configure database and other relevant settings
- run `install.sh` in the `scripts` dir to install this app. 

### Development

- run `php artisan db:seed --class=DevelopmentSeeder` after install to seed test data
- run `php artisan serve` and you are ready to go

- backend js/css if found in `/resources/assets/asimov` and are compiled with `grunt` command
- backend js/css if found in `/resources/assets/frontend` and are compiled with `grunt` command

- a `grunt watch` command is available for development

### Production

- Run `php artisan db:sseed`
- Now you can login with the credentials you specified in .env AUTH_DEFAULT_EMAIL/PASS

### Cron

Add this line to the crontab: `* * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1`. This will take care of all the scheduled jobs.

There are two jobs that are run:

- `survey:close` closes all surveys that are expired (and reopens them with new start/end in case of repeat. (every minute).
- `profile:export` sends all updated profiles to the Mash system (every hour). <-- disabled

### Testing

- run `./vendor/phpunit/phpunit/phpunit` from the project root to run unit tests
